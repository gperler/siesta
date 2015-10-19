<?php

namespace siestaphp\runtime;

use siestaphp\driver\ResultSet;

/**
 * Interface ORMEntity
 * @package siestaphp\runtime
 */
interface ORMEntity
{

    /**
     * @return bool
     */
    public function validate();

    /**
     * @param bool $cascade
     * @param Passport $passport
     *
     * @return void
     */
    public function save($cascade = false, $passport = null);

    /**
     * @param ResultSet $res
     *
     * @return void
     */
    public function initializeFromResultSet(ResultSet $res);

    /**
     * @param HttpRequest $req
     *
     * @return void
     */
    public function initializeFromHttpRequest(HttpRequest $req);

    /**
     * @param string $jsonString
     *
     * @return void
     */
    public function fromJSON($jsonString);

    /**
     * @param array $data
     *
     * @return void
     */
    public function fromArray(array $data);

    /**
     * @return string
     */
    public function toJSON();

    /**
     * @param Passport $passport
     *
     * @return array
     */
    public function toArray($passport = null);

    /**
     * @param Passport $passport
     *
     * @return void
     **/
    public function linkRelations($passport = null);

    /**
     * @param $entity
     *
     * @return bool
     */
    public function arePrimaryKeyIdentical($entity);

}