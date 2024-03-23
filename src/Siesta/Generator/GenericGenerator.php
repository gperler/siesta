<?php
declare(strict_types=1);

namespace Siesta\Generator;

use Siesta\Config\GenericGeneratorConfig;
use Siesta\Contract\Generator;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class GenericGenerator
{

    /**
     * @var GenericGeneratorConfig
     */
    protected GenericGeneratorConfig $config;

    /**
     * @var Generator
     */
    protected Generator $generator;

    /**
     * ModelGenerator constructor.
     *
     * @param GenericGeneratorConfig $config
     */
    public function __construct(GenericGeneratorConfig $config)
    {
        $this->config = $config;
        $this->setup();
    }

    /**
     */
    protected function setup(): void
    {
        $generatorClassName = $this->config->getClassName();
        $this->generator = new $generatorClassName;
        foreach ($this->config->getPluginList() as $plugin) {
            $this->generator->addPlugin(new $plugin);
        }
    }

    /**
     * @param Entity $entity
     * @param string $baseDir
     */
    public function generate(Entity $entity, string $baseDir): void
    {
        $this->generator->generate($entity, $baseDir);
    }

}