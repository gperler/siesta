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
class EntityManagerTransformer implements Transformer
{

    const ENTITY_XSL = "/xslt/Manager.xsl";

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
     * @param EntityGeneratorSource $entity
     * @param string $baseDir
     *
     * @return void
     */
    public function transform(EntityGeneratorSource $entity, $baseDir)
    {

        // build xml source file for transformation
        $xmlEntityBuilder = new XMLEntityBuilder($entity);

        // get xml source
        $domDocument = $xmlEntityBuilder->getDOMDocument();


        $entityManager = $entity->getEntityManagerSource();

        // store it xml
        $domDocument->formatOutput = true;
        $name = str_replace("\\", ".", $entityManager->getClassNamespace()) . "." . $entityManager->getClassName();
        $xmlTargetPath = __DIR__ . "/../../../tests/xml/" . $name .  ".xml";
        $domDocument->save($xmlTargetPath);

        // create target directory
        $path = $entity->getAbsoluteTargetPath($baseDir);
        $path->createDir();

        $managerFileName = $baseDir . "/" . $entityManager->getTargetPath() . "/" . $entityManager->getClassName() . ".php";

        // transform xml to php class
        $this->xsltProcessor->transformToUri($domDocument, $managerFileName);
    }

}