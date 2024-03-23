<?php

declare(strict_types=1);

namespace Siesta\Database;

use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class ConnectionData
{

    const NAME = "name";

    const DRIVER = "driver";

    const HOST = "host";

    const PORT = "port";

    const DATABASE = "database";

    const USER = "user";

    const PASSWORD = "password";

    const CHARSET = "charSet";

    const IS_DEFAULT = "isDefault";

    const POST_CONNECT_STATEMENT_LIST = "postConnectStatementList";

    /**
     * @var string|null
     */
    public ?string $name;

    /**
     * @var string|null
     */
    public ?string $driver;

    /**
     * @var string|null
     */
    public ?string $host;

    /**
     * @var int|null
     */
    public ?int $port;

    /**
     * @var string|null
     */
    public ?string $database;

    /**
     * @var string|null
     */
    public ?string $user;

    /**
     * @var string|null
     */
    public ?string $password;

    /**
     * @var string[]
     */
    public array $postConnectStatementList;

    /**
     * @var string|null
     */
    public ?string $charSet;

    /**
     * @var bool|null
     */
    public ?bool $isDefault;

    /**
     * ConnectionData constructor.
     *
     * @param string|null $name
     * @param string|null $driver
     * @param string|null $host
     * @param int|null $port
     * @param string|null $database
     * @param string|null $user
     * @param string|null $password
     * @param string|null $charSet
     * @param array|null $postConnectStatements
     */
    public function __construct(string $name = null, string $driver = null, string $host = null, int $port = null, string $database = null, string $user = null, string $password = null, string $charSet = null, array $postConnectStatements = null)
    {
        $this->postConnectStatementList = $postConnectStatements ?? [];
        $this->name = $name;
        $this->driver = $driver;
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
        $this->charSet = $charSet;
    }

    /**
     * @param array $values
     */
    public function fromArray(array $values): void
    {
        $this->name = ArrayUtil::getFromArray($values, self::NAME);
        $this->driver = ArrayUtil::getFromArray($values, self::DRIVER);
        $this->host = ArrayUtil::getFromArray($values, self::HOST);
        $this->port = ArrayUtil::getFromArray($values, self::PORT);
        $this->database = ArrayUtil::getFromArray($values, self::DATABASE);
        $this->user = ArrayUtil::getFromArray($values, self::USER);
        $this->password = ArrayUtil::getFromArray($values, self::PASSWORD);
        $this->charSet = ArrayUtil::getFromArray($values, self::CHARSET);
        $this->isDefault = ArrayUtil::getFromArray($values, self::IS_DEFAULT);
        $this->postConnectStatementList = ArrayUtil::getFromArray($values, self::POST_CONNECT_STATEMENT_LIST);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::NAME => $this->name,
            self::DRIVER => $this->driver,
            self::HOST => $this->host,
            self::PORT => $this->port,
            self::DATABASE => $this->database,
            self::USER => $this->user,
            self::PASSWORD => $this->password,
            self::CHARSET => $this->charSet,
            self::IS_DEFAULT => $this->isDefault,
            self::POST_CONNECT_STATEMENT_LIST => $this->postConnectStatementList !== null ? $this->postConnectStatementList : []
        ];
    }

    /**
     * @param string $statement
     */
    public function addPostConnectStatement(string $statement): void
    {
        $this->postConnectStatementList[] = $statement;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode(PHP_EOL, [
            "Name " . $this->name,
            "Driver " . $this->driver,
            "Host " . $this->host,
            "Port " . $this->port,
            "Database " . $this->database,
            "User " . $this->user,
            "Charset " . $this->user,
            "Post Connect statement " . implode(";", $this->postConnectStatementList)
        ]);
    }
}