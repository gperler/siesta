<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;

class ToXMLPlugin extends BasePlugin
{

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     * @return void
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->setup($entity, $classGenerator);

        // create a new method and add a parameter
        $method = $this->classGenerator->addPublicMethod("toXML");
        $method->addParameter('\DOMElement', 'element');

        // iterate all attributes
        foreach ($entity->getAttributeList() as $attribute) {
            $phpType = $attribute->getPhpType();
            $name = $attribute->getPhpName();
            $memberName = '$this->' . $name;

            // primitive types can be set directly
            if ($phpType === PHPType::BOOL || $phpType === PHPType::INT || $phpType === PHPType::FLOAT || $phpType === PHPType::STRING) {

                $method->addIfStart($memberName . ' !== null');
                $method->addCodeLine('$element->setAttribute("' . $name . '", ' . $memberName . ');');
                $method->addIfEnd();
                continue;
            }

            // datetime need method call
            if ($phpType === PHPType::SIESTA_DATE_TIME) {
                $method->addIfStart($memberName . ' !== null');
                $method->addCodeLine('$element->setAttribute("' . $name . '", ' . $memberName . '->getSQLDateTime());');
                $method->addIfEnd();
            }
        }
    }

}