<?php

namespace SiestaTest\TestUtil;

use Siesta\Config\GenericConfigLoader;
use Siesta\Database\ConnectionFactory;
use Siesta\Model\DataModel;
use Siesta\Model\ValidationLogger;
use Siesta\Util\File;
use Siesta\Validator\Validator;
use Siesta\XML\XMLReader;

class DataModelHelper
{

    /**
     * @param $fileName
     *
     * @return DataModel
     */
    public function readModel(string $fileName) : DataModel
    {

        $xmlReader = new XMLReader(new File($fileName));
        $xmlEntityList = $xmlReader->getEntityList();

        $dataModel = new DataModel();
        $dataModel->addXMLEntityList($xmlEntityList);
        $dataModel->update();
        return $dataModel;

    }

    /**
     * @param bool $silent
     *
     * @return ValidationLogger
     */
    public function getValidationLogger(bool $silent) : ValidationLogger
    {
        $codeceptionLogger = new CodeceptionLogger($silent);
        $validationLogger = new ValidationLogger();
        $validationLogger->setLogger($codeceptionLogger);
        return $validationLogger;
    }

    /**
     * @param bool $silent
     *
     * @return Validator
     */
    public function getValidator(bool $silent) : Validator
    {
        $validationLogger = $this->getValidationLogger($silent);
        $configLoader = new GenericConfigLoader();
        $generatorConfigList = $configLoader->loadAndValidate($validationLogger);

        $validator = new Validator();
        $validator->setup($generatorConfigList);
        return $validator;
    }

    public function createSchema(string $fileName, bool $silent)
    {
        $dmr = new DataModelHelper();

        $validationLogger = $dmr->getValidationLogger($silent);
        $validatior = $dmr->getValidator($silent);
        $model = $dmr->readModel($fileName);

        $validatior->validateDataModel($model, $validationLogger);

        $connection = ConnectionFactory::getConnection();
        $factory = $connection->getCreateStatementFactory();

        $statementList = [];
        foreach ($model->getEntityList() as $entity) {
            $statementList = array_merge($statementList, $factory->buildCreateTable($entity));
        }

        $connection->disableForeignKeyChecks();

        foreach ($statementList as $statement) {
            $connection->execute($statement);
        }

        $connection->enableForeignKeyChecks();

    }
}