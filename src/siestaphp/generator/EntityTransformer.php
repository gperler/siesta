<?php

namespace siestaphp\generator;

use Codeception\Util\Debug;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\util\File;
use siestaphp\xmlbuilder\XMLEntityBuilder;

/**
 * Class EntityTransformer
 * @package siestaphp\generator
 */
class EntityTransformer implements Transformer
{

    const ENTITY_XSL = "/xslt/Entity.xsl";

    /**
     * @var \XSLTProcessor
     */
    private $xsltProcessor;

    /**
     *
     */
    public function __construct()
    {
        $xslFile = new File(__DIR__ . self::ENTITY_XSL);

        $this->xsltProcessor = $xslFile->loadAsXSLTProcessor();
    }

    /**
     * transforms the entity to the main php persistence class

     *
*@param EntityGeneratorSource $entity
     * @param string $baseDir
     */
    public function transform(EntityGeneratorSource $entity, $baseDir)
    {

        // build xml source file for transformation
        $xmlEntityBuilder = new XMLEntityBuilder($entity);

        // get xml source
        $domDocument = $xmlEntityBuilder->getDOMDocument();

        // store it xml
        $domDocument->formatOutput = true;
        $name = str_replace("\\", ".", $entity->getClassNamespace());
        $xmlTargetPath = __DIR__ . "/../../../tests/xml/" . $name . "." . $entity->getClassName() . ".xml";
        $domDocument->save($xmlTargetPath);

        // create target directory
        $path = $entity->getAbsoluteTargetPath($baseDir);
        $path->createDir();

        // delete existing file
        $targetFilePath = $entity->getTargetEntityFile($baseDir);
        $targetFilePath->delete();

        // transform xml to php class
        $this->xsltProcessor->transformToUri($domDocument, $targetFilePath->getAbsoluteFileName());
    }

}