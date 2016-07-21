<?php
declare(strict_types = 1);
namespace Siesta\Database\MetaData;

/**
 * @author Gregor Müller
 */
interface IndexMetaData
{

    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return string
     */
    public function getType() : string;

    /**
     * @return bool
     */
    public function getIsUnique() : bool;

    /**
     * @return IndexPartMetaData[]
     */
    public function getIndexPartList() : array;
}