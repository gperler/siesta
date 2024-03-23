<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

class FromHttpRequest extends BasePlugin
{

    /**
     * @param Entity $entity
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity): array
    {
        return [
            'MyWebFramework\Http\Request'
        ];
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     * @return void
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {

        $method = $classGenerator->addPublicMethod("fromHttpRequest");
        $method->addParameter("Request", "request");

        foreach ($entity->getAttributeList() as $attribute) {

            $httpRequestParameter = $attribute->getCustomAttribute("httpParamName");
            $setterMethod = '$this->set' . $attribute->getMethodName();

            $method->addCodeLine($setterMethod . '($request->get("' . $httpRequestParameter . '"));');
        }
    }

}