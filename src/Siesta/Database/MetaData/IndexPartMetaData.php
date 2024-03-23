<?php
declare(strict_types = 1);
namespace Siesta\Database\MetaData;

/**
 * @author Gregor Müller
 */
interface IndexPartMetaData
{

    /**
     * @return string
     */
    public function getColumnName() : string;

    /**
     * @return string
     */
    public function getSortOrder() : string;

    /**
     * @return int|null
     */
    public function getLength(): ?int;
}

