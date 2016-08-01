<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;

class ToXMLPlugin extends BasePlugin
{

    public function generate(Entity $entity, CodeGenerator $codeGenerator)
    {
        $this->setup($entity, $codeGenerator);

        // create a new method and add a parameter
        $method = $codeGenerator->newPublicMethod("toXML");
        $method->addParameter('\DOMElement', 'element');

        // iterate all attributes
        foreach ($entity->getAttributeList() as $attribute) {
            $phpType = $attribute->getPhpType();
            $name =  $attribute->getPhpName();
            $memberName = '$this->' . $name;

            // primitive types can be set directly
            if ($phpType === PHPType::BOOL || $phpType === PHPType::INT || $phpType === PHPType::FLOAT || $phpType === PHPType::STRING) {

                $method->addIfStart($memberName . ' !== null');
                $method->addLine('$element->setAttribute("' . $name . '", ' . $memberName . ');');
                $method->addIfEnd();
                continue;
            }

            // datetime need method call
            if ($phpType === PHPType::SIESTA_DATE_TIME) {
                $method->addIfStart($memberName . ' !== null');
                $method->addLine('$element->setAttribute("' . $name . '", ' . $memberName . '->getSQLDateTime());');
                $method->addIfEnd();
            }
        }

        // really important !!!
        $method->end();
    }

}