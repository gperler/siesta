<?php
declare(strict_types=1);

namespace Siesta\Database\MetaData;

/**
 * @author Gregor Müller
 */
interface ColumnMetaData
{

    /**
     * @return string
     */
    public function getDBType(): string;

    /**
     * @return string
     */
    public function getDBName(): string;

    /**
     * @return string
     */
    public function getPHPType(): string;

    /**
     * @return bool
     */
    public function getIsRequired(): bool;

    /**
     * @return bool
     */
    public function getIsPrimaryKey(): bool;

    /**
     * @return string|null
     */
    public function getAutoValue(): ?string;
}