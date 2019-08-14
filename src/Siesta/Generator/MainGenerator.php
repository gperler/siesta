<?php
declare(strict_types=1);

namespace Siesta\Generator;

use Siesta\Config\GenericGeneratorConfig;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class MainGenerator
{

    /**
     * @var GenericGenerator[]
     */
    protected $genericGeneratorList;

    /**
     * @var GenericGeneratorConfig[]
     */
    protected $genericGeneratorConfigList;

    /**
     * @param array $genericGeneratorConfigList
     */
    public function setup(array $genericGeneratorConfigList)
    {
        $this->genericGeneratorConfigList = $genericGeneratorConfigList;
        foreach ($this->genericGeneratorConfigList as $genericGeneratorConfig) {
            $genericGenerator = new GenericGenerator($genericGeneratorConfig);
            $this->genericGeneratorList[] = $genericGenerator;
        }
    }

    /**
     * @param DataModel $dataModel
     * @param string $baseDir
     */
    public function generate(DataModel $dataModel, string $baseDir)
    {
        foreach ($dataModel->getEntityList() as $entity) {
            $this->generateEntity($entity, $baseDir);
        }
    }

    /**
     * @param Entity $entity
     * @param string $baseDir
     */
    public function generateEntity(Entity $entity, string $baseDir)
    {
        foreach ($this->genericGeneratorList as $genericGenerator) {
            $genericGenerator->generate($entity, $baseDir);
        }
    }

}