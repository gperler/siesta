<?php

namespace SiestaTest\Functional\Validator;

use Codeception\Test\Unit;
use Siesta\Config\GenericConfigLoader;
use Siesta\Model\DataModel;
use Siesta\Model\ValidationLogger;
use Siesta\Util\File;
use Siesta\Validator\DefaultAttributeValidator;
use Siesta\Validator\DefaultCollectionManyValidator;
use Siesta\Validator\DefaultCollectionValidator;
use Siesta\Validator\DefaultDataModelValidator;
use Siesta\Validator\DefaultEntityValidator;
use Siesta\Validator\DefaultIndexValidator;
use Siesta\Validator\DefaultReferenceValidator;
use Siesta\Validator\DefaultStoredProcedureValidator;
use Siesta\Validator\Validator;
use Siesta\XML\XMLReader;
use SiestaTest\TestUtil\CodeceptionLogger;

class ValidatorTest extends Unit
{

    protected function getValidationLogger(bool $silent)
    {
        $codeceptionLogger = new CodeceptionLogger($silent);
        $validationLogger = new ValidationLogger();
        $validationLogger->setLogger($codeceptionLogger);
        return $validationLogger;
    }

    protected function getValidator(bool $silent)
    {
        $validationLogger = $this->getValidationLogger($silent);
        $configLoader = new GenericConfigLoader();
        $generatorConfigList = $configLoader->loadAndValidate($validationLogger);
        $this->assertSame(0, $validationLogger->getErrorCount());

        $validator = new Validator();
        $validator->setup($generatorConfigList);
        return $validator;
    }

    protected function loadDataModel(string $fileName)
    {

        $xmlReader = new XMLReader(new File(__DIR__ . $fileName));
        $xmlEntityList = $xmlReader->getEntityList();

        $dataModel = new DataModel();
        $dataModel->addXMLEntityList($xmlEntityList);
        $dataModel->update();
        return $dataModel;

    }

    /**
     *
     */
    public function testValidateDataModel()
    {
        $dataModel = $this->loadDataModel("/data/validate.datamodel.test.xml");
        $validator = $this->getValidator(true);

        $validationLogger = $this->getValidationLogger(true);
        $validator->validateDataModel($dataModel, $validationLogger);

        $errorCodeList = $validationLogger->getErrorCodeList();
        $this->assertTrue($validationLogger->hasError());

        $this->assertSame(2, sizeof($errorCodeList));
        $this->assertTrue(in_array(DefaultDataModelValidator::ERROR_DUPLICATE_CLASS_NAME_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultDataModelValidator::ERROR_DUPLICATE_TABLE_NAME_CODE, $errorCodeList));
    }

    /**
     *
     */
    public function testValidateEntity()
    {
        $dataModel = $this->loadDataModel("/data/validate.entity.test.xml");
        $validator = $this->getValidator(true);

        $validationLogger = $this->getValidationLogger(true);
        $validator->validateDataModel($dataModel, $validationLogger);

        $errorCodeList = $validationLogger->getErrorCodeList();
        $this->assertTrue($validationLogger->hasError());

        $this->assertSame(8, sizeof($errorCodeList));
        $this->assertTrue(in_array(DefaultEntityValidator::ERROR_INVALID_CLASS_NAME_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultEntityValidator::ERROR_INVALID_NAMESPACE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultEntityValidator::ERROR_DUPLICATE_ATT_REF_COL_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultEntityValidator::ERROR_DUPLICATE_DB_NAME_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultEntityValidator::ERROR_DUPLICATE_INDEX_CODE, $errorCodeList));

    }

    /**
     *
     */
    public function testValidateAttributes()
    {
        $dataModel = $this->loadDataModel("/data/validate.attribute.test.xml");
        $validator = $this->getValidator(true);

        $validationLogger = $this->getValidationLogger(true);
        $validator->validateDataModel($dataModel, $validationLogger);

        $errorCodeList = $validationLogger->getErrorCodeList();
        $this->assertTrue($validationLogger->hasError());
        $this->assertSame(5, sizeof($errorCodeList));
        $this->assertTrue(in_array(DefaultAttributeValidator::ERROR_INVALID_NAME_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultAttributeValidator::ERROR_NO_TYPE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultAttributeValidator::ERROR_INVALID_TYPE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultAttributeValidator::ERROR_INVALID_AUTO_VALUE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultAttributeValidator::ERROR_NO_DB_TYPE_CODE, $errorCodeList));

        $warningCodeList = $validationLogger->getWarningCodeList();
        $this->assertTrue($validationLogger->hasWarning());
        $this->assertSame(1, $validationLogger->getWarningCount());
        $this->assertTrue($validationLogger->hasWarningCode(DefaultAttributeValidator::WARN_NO_AUTO_VALUE_CODE));

    }

    /**
     *
     */
    public function testValidateReference()
    {
        $dataModel = $this->loadDataModel("/data/validate.reference.test.xml");
        $validator = $this->getValidator(true);

        $validationLogger = $this->getValidationLogger(true);
        $validator->validateDataModel($dataModel, $validationLogger);

        $errorCodeList = $validationLogger->getErrorCodeList();
        $this->assertSame(8, sizeof($errorCodeList));

        $this->assertTrue(in_array(DefaultReferenceValidator::ERROR_INVALID_FOREIGN_TABLE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultReferenceValidator::ERROR_INVALID_ON_X_VALID_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultReferenceValidator::ERROR_INVALID_LOCAL_REFERENCE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultReferenceValidator::ERROR_INVALID_FOREIGN_REFERENCE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultReferenceValidator::ERROR_MAPPING_HAS_NOT_SAME_DB_DATA_TYPE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultReferenceValidator::ERROR_MAPPING_HAS_NOT_SAME_PHP_DATA_TYPE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultReferenceValidator::ERROR_NO_REFERENCE_MAPPING_CODE, $errorCodeList));

    }

    /**
     *
     */
    public function testValidateIndex()
    {
        $dataModel = $this->loadDataModel("/data/validate.index.test.xml");
        $validator = $this->getValidator(true);

        $validationLogger = $this->getValidationLogger(true);
        $validator->validateDataModel($dataModel, $validationLogger);

        $errorCodeList = $validationLogger->getErrorCodeList();

        $this->assertSame(3, sizeof($errorCodeList));
        $this->assertTrue(in_array(DefaultIndexValidator::ERROR_INVALID_ATTRIBUTE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultIndexValidator::ERROR_NO_INDEX_PART_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultIndexValidator::ERROR_INVALID_INDEX_NAME_CODE, $errorCodeList));

    }

    /**
     *
     */
    public function testValidateCollection()
    {
        $dataModel = $this->loadDataModel("/data/validate.collection.test.xml");
        $validator = $this->getValidator(true);

        $validationLogger = $this->getValidationLogger(true);
        $validator->validateDataModel($dataModel, $validationLogger);

        $errorCodeList = $validationLogger->getErrorCodeList();

        $this->assertSame(4, sizeof($errorCodeList));
        $this->assertTrue(in_array(DefaultCollectionValidator::ERROR_FOREIGN_REFERENCE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultCollectionValidator::ERROR_FOREIGN_TABLE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultCollectionValidator::ERROR_INVALID_NAME_CODE, $errorCodeList));

    }

    /**
     *
     */
    public function testValidateCollectionMany()
    {
        $dataModel = $this->loadDataModel("/data/validate.collectionMany.test.xml");
        $validator = $this->getValidator(true);

        $validationLogger = $this->getValidationLogger(true);
        $validator->validateDataModel($dataModel, $validationLogger);

        $errorCodeList = $validationLogger->getErrorCodeList();

        $this->assertSame(7, sizeof($errorCodeList));
        $this->assertTrue(in_array(DefaultCollectionManyValidator::ERROR_FOREIGN_TABLE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultCollectionManyValidator::ERROR_INVALID_NAME_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultCollectionManyValidator::ERROR_MAPPING_TABLE_CODE, $errorCodeList));
    }

    /**
     *
     */
    public function testValidateCollectionManyReference()
    {
        $silent = true;
        $dataModel = $this->loadDataModel("/data/validate.collectionMany.references.test.xml");
        $validator = $this->getValidator($silent);

        $validationLogger = $this->getValidationLogger($silent);
        $validator->validateDataModel($dataModel, $validationLogger);

        $errorCodeList = $validationLogger->getErrorCodeList();

        $this->assertSame(4, sizeof($errorCodeList));
        $this->assertTrue(in_array(DefaultCollectionManyValidator::ERROR_FOREIGN_REFERENCE_CODE, $errorCodeList));
        $this->assertTrue(in_array(DefaultCollectionManyValidator::ERROR_MAPPING_REFERENCE_CODE, $errorCodeList));
    }


    /**
     *
     */
    public function testValidateStoredProcedure()
    {
        $silent = true;
        $dataModel = $this->loadDataModel("/data/validate.stored.procedure.test.xml");
        $validator = $this->getValidator($silent);

        $validationLogger = $this->getValidationLogger($silent);
        $validator->validateDataModel($dataModel, $validationLogger);

        $errorCodeList = $validationLogger->getErrorCodeList();

        $this->assertSame(1, sizeof($errorCodeList));
        $this->assertTrue(in_array(DefaultStoredProcedureValidator::ERROR_INVALID_RESULT_TYPE_CODE, $errorCodeList));
    }
}