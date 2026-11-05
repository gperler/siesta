<?php

declare(strict_types=1);

namespace SiestaTest\Functional\Database;

use PHPUnit\Framework\TestCase;
use Siesta\Util\Encryption;

class EncryptionTest extends TestCase
{

    private function key(): string
    {
        return base64_encode(str_repeat("k", 32));
    }

    public function testRoundTrip(): void
    {
        $key = $this->key();
        $plain = "max.mustermann@example.de";

        $cipher = Encryption::encrypt($plain, $key);

        $this->assertStringStartsWith(Encryption::ENCRYPTION_PREFIX . Encryption::ALGO_XCHACHA . ":", $cipher);
        $this->assertNotSame($plain, $cipher);
        $this->assertSame($plain, Encryption::decrypt($cipher, $key));
    }

    public function testEncryptionIsNonDeterministic(): void
    {
        $key = $this->key();
        // random nonce per call -> same plaintext yields different ciphertext
        $this->assertNotSame(Encryption::encrypt("secret", $key), Encryption::encrypt("secret", $key));
    }

    public function testLegacyPlaintextPassesThroughOnDecrypt(): void
    {
        // migration path: a value without the enc: prefix is returned unchanged
        $this->assertSame("legacy-plaintext", Encryption::decrypt("legacy-plaintext", $this->key()));
    }

    public function testNullIsPreserved(): void
    {
        $this->assertNull(Encryption::encrypt(null, $this->key()));
        $this->assertNull(Encryption::decrypt(null, $this->key()));
    }

    public function testNoKeyDisablesEncryption(): void
    {
        $this->assertSame("secret", Encryption::encrypt("secret", null));
        $this->assertSame("secret", Encryption::encrypt("secret", ""));

        // an encrypted value cannot be read back without a key -> unusable -> null
        $cipher = Encryption::encrypt("secret", $this->key());
        $this->assertNull(Encryption::decrypt($cipher, null));
    }

    public function testWrongKeyReturnsNull(): void
    {
        // unreadable ciphertext (wrong key) yields null rather than throwing, so the caller can
        // treat the secret as missing and let the user re-enter it
        $cipher = Encryption::encrypt("secret", $this->key());
        $this->assertNull(Encryption::decrypt($cipher, base64_encode(str_repeat("x", 32))));
    }

    public function testTamperedCiphertextReturnsNull(): void
    {
        $cipher = Encryption::encrypt("secret", $this->key());
        // flip one character in the base64 payload (after the "enc:x:" header)
        $flipAt = strlen(Encryption::ENCRYPTION_PREFIX) + 4;
        $cipher[$flipAt] = $cipher[$flipAt] === "A" ? "B" : "A";

        $this->assertNull(Encryption::decrypt($cipher, $this->key()));
    }

    public function testUnknownAlgorithmReturnsNull(): void
    {
        $this->assertNull(Encryption::decrypt(Encryption::ENCRYPTION_PREFIX . "z:" . base64_encode("whatever"), $this->key()));
    }

    public function testCorruptPrefixedValueReturnsNull(): void
    {
        // a prefixed value that is not well-formed ciphertext (e.g. a stray/garbled row) -> null
        $this->assertNull(Encryption::decrypt("enc:hello", $this->key()));
        $this->assertNull(Encryption::decrypt(Encryption::ENCRYPTION_PREFIX . "x:tooshort", $this->key()));
    }

    public function testMaxPlaintextLength(): void
    {
        $this->assertSame(29, Encryption::maxPlaintextLength(100));
        // columns too small to hold even the header + overhead yield 0
        $this->assertSame(0, Encryption::maxPlaintextLength(10));
        $this->assertSame(0, Encryption::maxPlaintextLength(0));
    }

    public function testMaxPlaintextLengthMatchesRealEncryption(): void
    {
        $key = $this->key();

        foreach ([64, 100, 255, 500, 1000] as $columnLength) {
            $max = Encryption::maxPlaintextLength($columnLength);

            // a value of exactly the reported length must fit within the column
            $atLimit = Encryption::encrypt(str_repeat("a", $max), $key);
            $this->assertLessThanOrEqual(
                $columnLength,
                strlen($atLimit),
                "ciphertext for $max bytes must fit VARCHAR($columnLength)"
            );

            // one byte more must overflow it - proving the limit is tight, not conservative
            $overLimit = Encryption::encrypt(str_repeat("a", $max + 1), $key);
            $this->assertGreaterThan(
                $columnLength,
                strlen($overLimit),
                "ciphertext for " . ($max + 1) . " bytes must overflow VARCHAR($columnLength)"
            );
        }
    }

}
