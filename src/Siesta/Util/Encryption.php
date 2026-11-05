<?php

declare(strict_types=1);

namespace Siesta\Util;

use RuntimeException;

/**
 * Transparent at-rest encryption for attribute values, used on the write path (via Escaper) and
 * the read path (via the ResultSet). Uses XChaCha20-Poly1305 (libsodium): one code path on every
 * host (no AES-NI requirement) and a 24 byte nonce that is safe to pick at random per value.
 *
 * Stored format is self-describing: ENCRYPTION_PREFIX . <algo> . ":" . base64(nonce . ciphertext+tag)
 */
class Encryption
{

    /**
     * prefix identifying an encrypted value. The character following the prefix is the algorithm
     * tag (@see self::ALGO_XCHACHA), so the format is self-describing.
     */
    const ENCRYPTION_PREFIX = "enc:";

    /**
     * algorithm tag for XChaCha20-Poly1305. "g" is reserved for a possible future AES-256-GCM
     * default - old enc:x: values keep decrypting regardless, so switching needs no data migration.
     */
    const ALGO_XCHACHA = "x";

    /**
     * Encrypts a value for storage. Returns the value unchanged when it is null or when no key is
     * configured, so an entity can be generated with encrypted attributes and have encryption
     * turned on later by simply supplying a key.
     *
     * @param string|null $value plaintext
     * @param string|null $key configured encryption key (any length - normalized via deriveKey)
     *
     * @return string|null
     */
    public static function encrypt(?string $value, ?string $key): ?string
    {
        if ($value === null) {
            return null;
        }
        if ($key === null || $key === "") {
            return $value;
        }

        $binaryKey = self::deriveKey($key);
        $nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        $cipher = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt($value, "", $nonce, $binaryKey);

        return self::ENCRYPTION_PREFIX . self::ALGO_XCHACHA . ":" . base64_encode($nonce . $cipher);
    }

    /**
     * Given the character capacity of a column, returns how many bytes of plaintext actually fit
     * once the value is encrypted and encoded the way self::encrypt stores it. Encryption adds a
     * fixed nonce + authentication tag and the result is base64 encoded, so the usable length is
     * considerably smaller than the raw column length (e.g. a VARCHAR(100) holds 29 bytes).
     *
     * The returned value is a byte count: for plain ASCII it equals the number of characters, for
     * multi-byte UTF-8 compare it against strlen(), not mb_strlen(). Returns 0 when the column is
     * too small to hold even an empty encrypted value.
     *
     * @param int $columnLength character capacity of the target column
     *
     * @return int usable plaintext length in bytes
     */
    public static function maxPlaintextLength(int $columnLength): int
    {
        // stored form: ENCRYPTION_PREFIX . ALGO . ":" . base64(nonce . ciphertext . tag)
        $headerLength = strlen(self::ENCRYPTION_PREFIX) + strlen(self::ALGO_XCHACHA) + 1;
        $overheadBytes = SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES + SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_ABYTES;

        // characters left for the base64 blob; every 4 base64 chars encode 3 bytes
        $base64Budget = $columnLength - $headerLength;
        if ($base64Budget <= 0) {
            return 0;
        }
        $blobBytes = intdiv($base64Budget, 4) * 3;

        return max(0, $blobBytes - $overheadBytes);
    }

    /**
     * Reverses self::encrypt. A value that does not carry the encryption prefix is returned
     * unchanged - the migration path: legacy plaintext rows read fine and upgrade to ciphertext on
     * their next save.
     *
     * Any value that IS prefixed but cannot be turned back into plaintext - no key, wrong key,
     * unsupported algorithm or corrupted/tampered data - yields null. Such ciphertext is unusable
     * regardless, and null lets the caller treat it as "missing" (e.g. prompt the user to re-enter
     * the secret) instead of forcing exception handling at every read site. A breadcrumb is logged
     * so a misconfigured key - which would otherwise be invisible - is one log grep away.
     *
     * @param string|null $value stored value (ciphertext or legacy plaintext)
     * @param string|null $key configured encryption key
     *
     * @return string|null
     */
    public static function decrypt(?string $value, ?string $key): ?string
    {
        if ($value === null) {
            return null;
        }
        // migration passthrough: not an encrypted value, hand it back as-is
        if (!str_starts_with($value, self::ENCRYPTION_PREFIX)) {
            return $value;
        }

        try {
            return self::decryptPrefixedValue($value, $key);
        } catch (\Throwable $e) {
            error_log("Siesta Encryption: could not decrypt value (" . $e->getMessage() . "); returning null");
            return null;
        }
    }

    /**
     * Decrypts a value known to carry the encryption prefix. Throws on any problem; the caller
     * (self::decrypt) turns that into a null result.
     *
     * @param string $value
     * @param string|null $key
     *
     * @return string
     */
    private static function decryptPrefixedValue(string $value, ?string $key): string
    {
        if ($key === null || $key === "") {
            throw new RuntimeException("no encryption key configured");
        }

        $payload = substr($value, strlen(self::ENCRYPTION_PREFIX));
        $separatorPos = strpos($payload, ":");
        if ($separatorPos === false) {
            throw new RuntimeException("missing algorithm tag");
        }

        $algo = substr($payload, 0, $separatorPos);
        $data = base64_decode(substr($payload, $separatorPos + 1), true);
        if ($data === false) {
            throw new RuntimeException("invalid base64 payload");
        }

        $binaryKey = self::deriveKey($key);

        $plain = match ($algo) {
            self::ALGO_XCHACHA => self::decryptXChaCha($data, $binaryKey),
            default => throw new RuntimeException("unknown algorithm '" . $algo . "'"),
        };

        if ($plain === false) {
            throw new RuntimeException("authentication failed (wrong key or tampered data)");
        }
        return $plain;
    }

    /**
     * @param string $data nonce . ciphertext+tag
     * @param string $binaryKey 32 byte raw key
     *
     * @return string|false plaintext or false on authentication failure
     */
    private static function decryptXChaCha(string $data, string $binaryKey): string|false
    {
        $nonceLength = SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES;
        $nonce = substr($data, 0, $nonceLength);
        $cipher = substr($data, $nonceLength);
        return sodium_crypto_aead_xchacha20poly1305_ietf_decrypt($cipher, "", $nonce, $binaryKey);
    }

    /**
     * Normalizes an arbitrary configured key string into the 32 raw bytes libsodium requires.
     * Using a generic hash lets any key string work while producing a uniformly distributed key;
     * generate strong keys with sodium_crypto_aead_xchacha20poly1305_ietf_keygen().
     *
     * @param string $key
     *
     * @return string 32 raw bytes
     */
    private static function deriveKey(string $key): string
    {
        return sodium_crypto_generichash($key, "", SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES);
    }

}
