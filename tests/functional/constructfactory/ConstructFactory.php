<?php

namespace siestaphp\tests\functional\constructfactory;

use siestaphp\tests\functional\constructfactory\ns1\Artist;
use siestaphp\tests\functional\constructfactory\ns2\Label;

/**
 * Class ConstructFactory
 * @package siestaphp\tests\functional\constructfactory
 */
class ConstructFactory
{

    /**
     * @return Artist
     */
    public static function newArtist() {
        return new Artist(true);
    }

    /**
     * @return Label
     */
    public static function newLabel() {
        return new Label(true);
    }
}