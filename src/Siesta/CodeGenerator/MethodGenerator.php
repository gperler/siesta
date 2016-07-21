<?php

namespace Siesta\CodeGenerator;

use Siesta\Model\Attribute;
use Siesta\Model\DBType;
use Siesta\Model\PHPType;
use Siesta\Model\ReferenceMapping;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class MethodGenerator
{

    const TYPE_ECAPE_MAPPING = [
        PHPType::BOOL => "quoteBool",
        PHPType::INT => "quoteInt",
        PHPType::FLOAT => "quoteFloat",
        PHPType::SIESTA_DATE_TIME => "getDateTime"
    ];

    const DB_TYPE_ESCAPE_MAPPING = [
        "DATETIME" => "quoteDateTime",
        "DATE" => "quoteDate",
        "TIME" => "quoteTime"
    ];

    /**
     * @var string
     */
    protected $modifier;

    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var bool;
     */
    protected $isStatic;

    /**
     * @var MethodParameter[]
     */
    protected $parameterList;

    /**
     * @var MethodReturnType
     */
    protected $returnType;

    /**
     * @var string[]
     */
    protected $lineOfCode;

    /**
     * @var CodeGenerator
     */
    protected $codeGenerator;

    /**
     * @var int
     */
    protected $currentIndent;

    /**
     * @var bool
     */
    protected $isConstructor;

    /**
     * MethodGenerator constructor.
     *
     * @param CodeGenerator $codeGenerator
     * @param string $name
     * @param string $modifier
     * @param bool $static
     */
    public function __construct(CodeGenerator $codeGenerator, string $name, string $modifier = "public", bool $static = false)
    {
        $this->modifier = $modifier;
        $this->methodName = $name;
        $this->isStatic = $static;
        $this->codeGenerator = $codeGenerator;
        $this->parameterList = [];
        $this->lineOfCode = [];
        $this->returnType = new MethodReturnType();
        $this->currentIndent = 1;
    }

    /**
     * @param ReferenceMapping[] $mappingList
     * @param string $defaultValue
     */
    public function addReferenceMappingListParameter(array $mappingList, string $defaultValue = null)
    {
        foreach ($mappingList as $mapping) {
            $this->addReferenceMappingParameter($mapping, $defaultValue);
        }
    }

    /**
     * @param ReferenceMapping $mapping
     * @param string $defaultValue
     */
    public function addReferenceMappingParameter(ReferenceMapping $mapping, string $defaultValue = null)
    {
        $this->addAttributeParameter($mapping->getLocalAttribute(), $defaultValue);
    }

    /**
     * @param array $attributeList
     * @param string $defaultValue
     */
    public function addAttributeParameterList(array $attributeList, string $defaultValue = null)
    {
        foreach ($attributeList as $attribute) {
            $this->addAttributeParameter($attribute, $defaultValue);
        }
    }

    /**
     * @param Attribute $attribute
     * @param string $defaultValue
     */
    public function addAttributeParameter(Attribute $attribute, string $defaultValue = null)
    {
        $this->addParameter($attribute->getPhpType(), $attribute->getPhpName(), $defaultValue);
    }

    /**
     *
     */
    public function addConnectionNameParameter()
    {
        $this->addParameter(PHPType::STRING, 'connectionName', 'null');
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $defaultValue
     */
    public function addParameter(string $type, string $name, string $defaultValue = null)
    {
        $this->parameterList[] = new MethodParameter($type, $name, $defaultValue);
    }

    /**
     * @param string $type
     * @param bool $nullAble
     */
    public function setReturnType(string $type, bool $nullAble = false)
    {
        $this->returnType = new MethodReturnType($type, $nullAble);
    }

    /**
     *
     */
    protected function generateDocBlock()
    {
        $phpDocBlock = [];
        foreach ($this->parameterList as $parameter) {
            $phpDocBlock[] = $parameter->getPHPDocLine();
        }
        $phpDocBlock[] = '';
        if (!$this->isConstructor) {
            $phpDocBlock[] = $this->returnType->generatePHPDoc();
        }
        $this->codeGenerator->addDocComment($phpDocBlock);
    }

    /**
     *
     */
    protected function generateMethodDefinition()
    {
        $static = $this->isStatic ? " static " : " ";
        $definition = $this->modifier . $static . "function " . $this->methodName;
        $signature = $this->createSignature();
        $returnType = $this->returnType->generateSignatureReturnType();

        $this->codeGenerator->addLine($definition . "($signature)$returnType");
        $this->codeGenerator->addLine("{");
    }

    /**
     *
     */
    protected function createSignature()
    {
        $parameterList = [];
        foreach ($this->parameterList as $parameter) {
            $parameterList[] = $parameter->getSignaturePart();
        }
        return implode(", ", $parameterList);
    }

    /**
     *
     */
    public function end()
    {
        $this->generateDocBlock();
        $this->generateMethodDefinition();
        foreach ($this->lineOfCode as $line) {
            $this->codeGenerator->addLine($line);
        }
        $this->codeGenerator->addLine("}", 2);
    }

    /**
     * @param array $lineList
     */
    public function addLineList(array $lineList)
    {
        foreach ($lineList as $line) {
            $this->addLine($line);
        }
    }

    /**
     * @param $content
     * @param int $linebreaks
     */
    public function addLine(string $content, int $linebreaks = 1)
    {
        $line = "";
        for ($i = 0; $i < $this->currentIndent; $i++) {
            $line .= CodeGenerator::IDENT;
        }
        $this->lineOfCode[] = $line . $content;
        $this->newLine($linebreaks);
    }

    /**
     * @param int $count
     */
    public function newLine($count = 1)
    {
        for ($i = 1; $i < $count; $i++) {
            $position = sizeof($this->lineOfCode);
            $this->lineOfCode[$position - 1] .= PHP_EOL;
        }
    }

    /**
     *
     */
    public function incrementIndent()
    {
        $this->currentIndent++;
    }

    /**
     *
     */
    public function decrementIndex()
    {
        $this->currentIndent--;
    }

    /**
     * @param $condition
     */
    public function addIfStart(string $condition)
    {
        $this->addLine("if ($condition) {", 1);
        $this->currentIndent++;
    }

    /**
     *
     */
    public function addIfEnd()
    {
        $this->currentIndent--;
        $this->addLine("}");
    }

    /**
     * @param string $condition
     */
    public function addWhileStart(string $condition)
    {
        $this->addLine("while ($condition) {", 1);
        $this->currentIndent++;
    }

    /**
     *
     */
    public function addWhileEnd()
    {
        $this->currentIndent--;
        $this->addLine("}");
    }

    /**
     * @param string $condition
     */
    public function addForeachStart(string $condition)
    {
        $this->addLine("foreach ($condition) {", 1);
        $this->currentIndent++;
    }

    /**
     *
     */
    public function addForeachEnd()
    {
        $this->currentIndent--;
        $this->addLine("}");
    }

    /**
     * @return boolean
     */
    public function getIsConstructor(): bool
    {
        return $this->isConstructor;
    }

    /**
     * @param boolean $isConstructor
     */
    public function setIsConstructor(bool $isConstructor)
    {
        $this->isConstructor = $isConstructor;
    }

    /**
     *
     */
    public function addConnectionLookup()
    {
        $this->addLine('$connection = ConnectionFactory::getConnection($connectionName);');
    }

    /**
     * @param ReferenceMapping[] $referenceMappingList
     * @param bool $forceConnectionLookup
     * @param bool $fromMember
     */
    public function addQuoteReferenceMappingList(array $referenceMappingList, bool $forceConnectionLookup = false, bool $fromMember = false)
    {
        $attributeList = [];
        foreach ($referenceMappingList as $referenceMapping) {
            $attributeList[] = $referenceMapping->getLocalAttribute();
        }
        $this->addQuoteAttributeList($attributeList, $forceConnectionLookup, $fromMember);
    }

    /**
     * @param Attribute[] $attributeList
     * @param bool $forceConnectionLookup
     * @param bool $fromMember
     *
     * @return string[]
     */
    public function addQuoteAttributeList(array $attributeList, bool $forceConnectionLookup = false, bool $fromMember = false)
    {
        $hasString = false;
        foreach ($attributeList as $attribute) {
            if ($attribute->getPhpType() === PHPType::STRING) {
                $hasString = true;
            }
        }

        if ($hasString || $forceConnectionLookup) {
            $this->addConnectionLookup();
        }

        foreach ($attributeList as $attribute) {
            $name = $attribute->getPhpName();
            $variableName = '$' . $name;
            $source = ($fromMember) ? '$this->' . $name : $variableName;
            $quoteCall = $this->getQuoteCall($attribute->getPhpType(), $attribute->getDbType(), $source, $attribute->getIsObject());
            $this->addLine($variableName . ' = ' . $quoteCall . ';');
        }
    }

    /**
     * @param Attribute $attribute
     * @param $variableName
     *
     * @return string
     */
    public function addQuoteCallForAttribute(Attribute $attribute, $variableName)
    {
        $quoteCall = $this->getQuoteCall($attribute->getPhpType(), $attribute->getDbType(), $variableName, $attribute->getIsObject());
        $this->addLine($quoteCall);
    }

    /**
     * @param string $phpType
     * @param string $dbType
     * @param string $variableName
     * @param bool $isObject
     *
     * @return string
     */
    public function getQuoteCall(string $phpType, string $dbType = null, string $variableName, $isObject = false) : string
    {
        if ($isObject) {
            return 'Escaper::quoteObject($connection,' . $variableName . ')';
        }

        if ($dbType === DBType::DATETIME) {
            return 'Escaper::quoteDateTime(' . $variableName . ')';
        }
        if ($dbType === DBType::DATE) {
            return 'Escaper::quoteDate(' . $variableName . ')';
        }
        if ($dbType === DBType::TIME) {
            return 'Escaper::quoteTime(' . $variableName . ')';
        }

        $quoteFunction = ArrayUtil::getFromArray(self::TYPE_ECAPE_MAPPING, $phpType);
        if ($quoteFunction !== null) {
            return 'Escaper::' . $quoteFunction . '(' . $variableName . ')';
        }

        if ($phpType === PHPType::ARRAY) {
            return 'Escaper::quoteArray($connection, ' . $variableName . ')';
        }

        return 'Escaper::quoteString($connection, ' . $variableName . ')';
    }

    /**
     * @param ReferenceMapping[] $referenceMappingList
     *
     * @return string
     */
    public function getSPInvocationSignatureFromReferenceMapping(array $referenceMappingList) : string
    {
        $attributeList = [];
        foreach ($referenceMappingList as $reference) {
            $attributeList[] = $reference->getLocalAttribute();
        }
        return $this->getSPInvocationSignature($attributeList);
    }

    /**
     * @param Attribute[] $attributeList
     *
     * @return string
     */
    public function getSPInvocationSignature(array $attributeList) : string
    {
        $spParam = [];
        foreach ($attributeList as $attribute) {
            $spParam[] = '$' . $attribute->getPhpName();
        }
        return implode(",", $spParam);
    }

    /**
     * @param string $name
     * @param ReferenceMapping[] $mappingList
     * @param bool $result
     *
     * @return string
     */
    public function addExecuteStoredProcedureWithMappingList(string $name, array $mappingList, bool $result = true)
    {
        $signature = $this->getSPInvocationSignatureFromReferenceMapping($mappingList);
        $this->addExecuteStoredProcedure($name, $signature, $result);
    }

    /**
     * @param string $name
     * @param Attribute[] $attributeList
     * @param bool $result
     *
     * @return string
     */
    public function addExecuteStoredProcedureWithAttributeList(string $name, array $attributeList, bool $result = true)
    {
        $signature = $this->getSPInvocationSignature($attributeList);
        $this->addExecuteStoredProcedure($name, $signature, $result);
    }

    /**
     * @param string $name
     * @param string $invocationSignature
     * @param bool $result
     */
    public function addExecuteStoredProcedure(string $name, string $invocationSignature, bool $result = true)
    {
        if (!$result) {
            $this->addLine('$connection->execute("CALL ' . $name . '(' . $invocationSignature . ')");');
        } else {
            $this->addLine('$resultSet = $connection->execute("CALL ' . $name . '(' . $invocationSignature . ')");');
        }
    }

}



