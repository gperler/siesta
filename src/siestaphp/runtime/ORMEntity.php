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
     */
    public function save($cascade = false, $passport = null);

    /**
     * @param ResultSet $res
     */
    public function initializeFromResultSet(ResultSet $res);

    /**
     * @param HttpRequest $req
     */
    public function initializeFromHttpRequest(HttpRequest $req);

    /**
     * @param string $jsonString
     */
    public function fromJSON($jsonString);

    /**
     * @param array $data
     */
    public function fromArray(array $data);

    /**
     * @return string
     */
    public function toJSON();

    /**
     * @param Passport $passport
     *
     * @return array|void
     */
    public function toArray($passport = null);

    /**
     * @param Passport $passport
     **/
    public function linkRelations($passport = null);

    /**
     * @param $entity
     *
     * @return bool
     */
    public function arePrimaryKeyIdentical($entity);

}