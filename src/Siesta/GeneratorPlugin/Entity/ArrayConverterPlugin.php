<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\CodeGenerator\MethodGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\GeneratorPlugin\ServiceClass\NewInstancePlugin;
use Siesta\Model\Attribute;
use Siesta\Model\Collection;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;
use Siesta\Model\Reference;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class ArrayConverterPlugin extends BasePlugin
{

    const METHOD_TO_ARRAY = "toArray";

    const METHOD_FROM_ARRAY = "fromArray";

    const TYPE_ARRAY_ACCESSOR_MAPPING = [
        PHPType::BOOL => "getBooleanValue",
        PHPType::INT => "getIntegerValue",
        PHPType::FLOAT => "getFloatValue",
        PHPType::STRING => "getStringValue",
        PHPType::SIESTA_DATE_TIME => "getDateTime",
        PHPType::ARRAY => "getArray"
    ];

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        $useClassList = [
            'Siesta\Util\ArrayAccessor',
            'Siesta\Contract\ArraySerializable',
            'Siesta\Util\DefaultCycleDetector',
            'Siesta\Contract\CycleDetector'
        ];
        foreach ($entity->getReferenceList() as $reference) {
            $foreignEntity = $reference->getForeignEntity();
            $serviceFactory = $foreignEntity->getServiceFactoryClass();
            if ($serviceFactory !== null) {
                $useClassList[] = $serviceFactory;
            }
        }
        return $useClassList;
    }

    /**
     * @return string[]
     */
    public function getDependantPluginList() : array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getInterfaceList() : array
    {
        return ['ArraySerializable'];
    }

    /**
     * @param Entity $entity
     * @param CodeGenerator $codeGenerator
     */
    public function generate(Entity $entity, CodeGenerator $codeGenerator)
    {
        $this->setup($entity, $codeGenerator);
        $this->generateFromArray();
        $this->generateToArray();
    }

    /**
     *
     */
    protected function generateFromArray()
    {
        $method = $this->codeGenerator->newPublicMethod(self::METHOD_FROM_ARRAY);
        $method->addParameter("array", "data");

        // store raw data
        $method->addLine('$this->_rawJSON = $data;');
        $method->addLine('$arrayAccessor = new ArrayAccessor($data);');

        $this->generateAttributeListFromArray($method);

        $this->addCheckExisting($method);

        $this->generateReferenceListFromArray($method);

        $this->generateCollectionListFromArray($method);

        $method->end();
    }

    /**
     *
     */
    protected function generateAttributeListFromArray(MethodGenerator $method)
    {
        foreach ($this->entity->getAttributeList() as $attribute) {
            $this->generateAttributeFromArray($method, $attribute);
            $this->generateObjectAttributeFromArray($method, $attribute);
        }
    }

    /**
     * @param MethodGenerator $method
     * @param Attribute $attribute
     */
    protected function generateAttributeFromArray(MethodGenerator $method, Attribute $attribute)
    {
        $name = $attribute->getPhpName();

        $accessorMethod = ArrayUtil::getFromArray(self::TYPE_ARRAY_ACCESSOR_MAPPING, $attribute->getPhpType());
        if ($accessorMethod === null) {
            return;
        }

        $method->addLine('$this->set' . $attribute->getMethodName() . '($arrayAccessor->' . $accessorMethod . '("' . $name . '"));');
    }

    /**
     * @param MethodGenerator $method
     * @param Attribute $attribute
     */
    protected function generateObjectAttributeFromArray(MethodGenerator $method, Attribute $attribute)
    {
        if (!$attribute->getIsObject() || !$attribute->implementsArraySerializable()) {
            return;
        }

        $name = $attribute->getPhpName();
        $type = $attribute->getPhpType();

        // access array raw data and make sure it is not null
        $method->addLine('$' . $name . 'Array = $arrayAccessor->getArray("' . $name . '");');
        $method->addIfStart('$' . $name . 'Array !== null');

        // instantiate new object and initialize it from array
        $method->addLine('$' . $name . ' = new ' . $type . '();');
        $method->addLine('$' . $name . '->fromArray($' . $name . 'Array);');

        // invoke setter to store object
        $method->addLine('$this->set' . $attribute->getMethodName() . '($' . $name . ');');

        // done
        $method->addIfEnd();
    }

    /**
     * @param MethodGenerator $method
     */
    protected function generateReferenceListFromArray(MethodGenerator $method)
    {
        foreach ($this->entity->getReferenceList() as $reference) {
            $this->generateReferenceFromArray($method, $reference);
        }
    }

    /**
     * @param MethodGenerator $method
     * @param Reference $reference
     */
    protected function generateReferenceFromArray(MethodGenerator $method, Reference $reference)
    {
        $foreignEntity = $reference->getForeignEntity();
        $name = $reference->getName();

        // get data from array and make sure it is not null
        $method->addLine('$' . $name . 'Array = $arrayAccessor->getArray("' . $name . '");');
        $method->addIfStart('$' . $name . 'Array !== null');

        // instantiate new object and initialize it from array
        $method->addLine('$' . $name . ' = ' . $foreignEntity->getServiceAccess() . '->' . NewInstancePlugin::METHOD_NEW_INSTANCE . '();');
        $method->addLine('$' . $name . '->' . self::METHOD_FROM_ARRAY . '($' . $name . 'Array);');

        // invoke setter to store attribute
        $method->addLine('$this->set' . $reference->getMethodName() . '($' . $name . ');');

        $method->addIfEnd();
    }

    /**
     * @param MethodGenerator $method
     */
    protected function generateCollectionListFromArray(MethodGenerator $method)
    {
        foreach ($this->entity->getCollectionList() as $collection) {
            $this->generateCollectionFromArray($method, $collection);
        }
    }

    /**
     * @param MethodGenerator $method
     * @param Collection $collection
     */
    protected function generateCollectionFromArray(MethodGenerator $method, Collection $collection)
    {
        $foreignEntity = $collection->getForeignEntity();
        $name = $collection->getName();

        // get collection data and make sure it exists
        $method->addLine('$' . $name . 'Array = $arrayAccessor->getArray("' . $name . '");');
        $method->addIfStart('$' . $name . 'Array !== null');

        // iterate array data
        $method->addForeachStart('$' . $name . 'Array as $entityArray');

        // instantiate new foreign entity initialize it and add it to the collection
        $method->addLine('$' . $name . ' = ' . $foreignEntity->getServiceAccess() . '->' . NewInstancePlugin::METHOD_NEW_INSTANCE . '();');
        $method->addLine('$' . $name . '->' . self::METHOD_FROM_ARRAY . '($entityArray);');
        $method->addLine('$this->' . CollectorGetterSetter::METHOD_ADD_TO_PREFIX . $collection->getMethodName() . '($' . $name . ');');

        $method->addForeachEnd();

        $method->addIfEnd();

    }

    /**
     * @param MethodGenerator $method
     */
    protected function addCheckExisting(MethodGenerator $method)
    {
        if (!$this->entity->hasPrimaryKey()) {
            return;
        }

        $pkCheckList = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $attribute) {
            $pkCheckList[] = '($this->' . $attribute->getPhpName() . ' !== null)';
        }

        $pkCheck = implode(" && ", $pkCheckList);

        $method->addLine('$this->_existing = ' . $pkCheck . ';');
    }

    /**
     *
     */
    protected function generateToArray()
    {
        $method = $this->codeGenerator->newPublicMethod(self::METHOD_TO_ARRAY);
        $method->addParameter('CycleDetector', 'cycleDetector', 'null');
        $method->setReturnType('array', true);

        $this->generateCycleDetection($method);

        $this->generateAttributeListToArray($method);

        $this->generateReferenceListToArray($method);

        $this->generateCollectionListToArray($method);

        $method->addLine('return $result;');
        $method->end();
    }

    /**
     * @param MethodGenerator $method
     */
    protected function generateCycleDetection(MethodGenerator $method)
    {
        $method->addIfStart('$cycleDetector === null');
        $method->addLine('$cycleDetector = new DefaultCycleDetector();');
        $method->addIfEnd();
        $method->newLine();

        // canProceed
        $method->addIfStart('!$cycleDetector->canProceed(self::TABLE_NAME, $this)');
        $method->addLine('return null;');
        $method->addIfEnd();
        $method->newLine();
    }

    /**
     * @param MethodGenerator $method
     */
    protected function generateAttributeListToArray(MethodGenerator $method)
    {

        $method->addLine('$result = [');
        $method->incrementIndent();
        foreach ($this->entity->getAttributeList() as $index => $attribute) {
            $line = $this->generateAttributeToArray($method, $attribute);
            if (($index + 1) !== sizeof($this->entity->getAttributeList())) {
                $line .= ",";
            }
            $method->addLine($line);
        }
        $method->decrementIndex();
        $method->addLine('];');
    }

    /**
     * @param MethodGenerator $method
     * @param Attribute $attribute
     *
     * @return string
     */
    protected function generateAttributeToArray(MethodGenerator $method, Attribute $attribute)
    {
        $name = $attribute->getPhpName();
        $type = $attribute->getPhpType();
        $methodName = 'get' . $attribute->getMethodName();

        if ($attribute->getIsObject() && $attribute->implementsArraySerializable()) {
            return '"' . $name . '" => ($this->' . $methodName . '() !== null) ? $this->' . $methodName . '()->toArray() : null';
        }

        if ($type === PHPType::SIESTA_DATE_TIME) {
            return '"' . $name . '" => ($this->' . $methodName . '() !== null) ? $this->' . $methodName . '()->getJSONDateTime() : null';
        }

        return '"' . $name . '" => $this->' . $methodName . '()';
    }

    /**
     * @param MethodGenerator $method
     */
    protected function generateReferenceListToArray(MethodGenerator $method)
    {
        foreach ($this->entity->getReferenceList() as $reference) {
            $this->generateReferenceToArray($method, $reference);
        }
    }

    /**
     * @param MethodGenerator $method
     * @param Reference $reference
     */
    protected function generateReferenceToArray(MethodGenerator $method, Reference $reference)
    {
        $name = $reference->getName();
        $method->addIfStart('$this->' . $name . ' !== null');
        $method->addLine('$result["' . $name . '"] = $this->' . $name . '->' . self::METHOD_TO_ARRAY . '($cycleDetector);');
        $method->addIfEnd();
    }

    /**
     * @param MethodGenerator $method
     */
    protected function generateCollectionListToArray(MethodGenerator $method)
    {
        foreach ($this->entity->getCollectionList() as $collection) {
            $this->generateCollectionToArray($method, $collection);
        }
    }

    /**
     * @param MethodGenerator $method
     * @param Collection $collection
     */
    protected function generateCollectionToArray(MethodGenerator $method, Collection $collection)
    {
        $name = $collection->getName();
        $method->addLine('$result["' . $name . '"] = [];');

        $method->addIfStart('$this->' . $name . ' !== null');
        $method->addForeachStart('$this->' . $name . ' as $entity');
        $method->addLine('$result["' . $name . '"][] = $entity->' . self::METHOD_TO_ARRAY . '($cycleDetector);');
        $method->addForeachEnd();
        $method->addIfEnd();
    }
}
