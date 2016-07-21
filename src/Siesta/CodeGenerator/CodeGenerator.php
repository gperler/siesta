<?php

declare(strict_types = 1);

namespace Siesta\CodeGenerator;

use Siesta\Util\File;

/**
 * @author Gregor Müller
 */
class CodeGenerator
{
    const IDENT = "    ";

    /**
     * @var string
     */
    protected $code;

    /**
     * @var int
     */
    protected $currentIndent;

    /**
     * CodeGenerator constructor.
     */
    public function __construct()
    {
        $this->currentIndent = 0;
        $this->code = "<?php" . PHP_EOL . PHP_EOL . 'declare(strict_types = 1);' . PHP_EOL . PHP_EOL;
    }

    /**
     * @param $content
     * @param int $linebreaks
     */
    public function addLine(string $content, int $linebreaks = 1)
    {
        for ($i = 0; $i < $this->currentIndent; $i++) {
            $this->code .= self::IDENT;
        }
        $this->code .= $content;
        $this->newLine($linebreaks);
    }

    /**
     * @param int $count
     */
    public function newLine($count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            $this->code .= PHP_EOL;
        }
    }

    /**
     * @param string $namespace
     */
    public function addNamespace(string $namespace)
    {
        $this->addLine("namespace " . $namespace . ";", 2);
    }

    /**
     * @param string $className
     */
    public function addUse(string $className)
    {
        $this->addLine("use " . $className . ";");
    }

    /**
     * @param array $lineList
     */
    public function addDocComment(array $lineList)
    {
        $this->addLine("/**");
        foreach ($lineList as $lineItem) {
            $this->addLine(" * " . $lineItem);
        }
        $this->addLine(" */");
    }

    /**
     * @param string $classShortName
     * @param string $extends
     * @param string $implements
     */
    public function addClassStart(string $classShortName, string $extends = null, string $implements = null)
    {
        $line = "class $classShortName";
        if ($extends !== null) {
            $line .= " extends $extends";
        }
        if ($implements !== null) {
            $line .= " implements $implements";
        }

        $this->addLine($line);
        $this->addLine("{");

        $this->currentIndent++;
    }

    /**
     *
     */
    public function addClassEnd()
    {
        $this->currentIndent--;
        $this->addLine("}", 0);
    }

    /**
     * @param string $name
     * @param string $value
     * @param bool $quote
     */
    public function addConstant(string $name, string $value, $quote = true)
    {
        if ($quote) {
            $value = '"' . $value . '"';
        }

        $this->addLine("const " . strtoupper($name) . ' = ' . $value . ";", 2);
    }

    /**
     * @param string $memberName
     * @param string $memberType
     */
    public function addProtectedMember(string $memberName, string $memberType = null)
    {
        $this->addMember("protected", false, $memberName, $memberType);
    }

    /**
     * @param string $memberName
     * @param string|null $memberType
     */
    public function addStaticProtectedMember(string $memberName, string $memberType = null)
    {
        $this->addMember("protected", true, $memberName, $memberType);

    }

    /**
     * @param string $modifier
     * @param bool $static
     * @param string $memberName
     * @param string|null $memberType
     */
    public function addMember(string $modifier, bool $static, string $memberName, string $memberType = null)
    {
        if ($memberType !== null) {
            $this->addDocComment(['@var ' . $memberType]);
        }
        if ($static) {
            $this->addLine($modifier . ' static $' . $memberName . ";", 2);
        } else {
            $this->addLine($modifier . ' $' . $memberName . ";", 2);
        }

    }

    /**
     * @param string $name
     * @param string $modifier
     * @param bool $static
     *
     * @return MethodGenerator
     */
    public function newMethod(string $name, string $modifier, bool $static)
    {
        return new MethodGenerator($this, $name, $modifier, $static);
    }

    /**
     * @param string $name
     *
     * @return MethodGenerator
     */
    public function newPublicMethod(string $name) : MethodGenerator
    {
        return new MethodGenerator($this, $name, "public", false);
    }

    /**
     * @param string $name
     *
     * @return MethodGenerator
     */
    public function newPublicStaticMethod(string $name) : MethodGenerator
    {
        return new MethodGenerator($this, $name, "public", true);
    }

    /**
     * @return MethodGenerator
     */
    public function newPublicConstructor()
    {
        $methodGenerator = new MethodGenerator($this, '__construct', "public", false);
        $methodGenerator->setIsConstructor(true);
        return $methodGenerator;
    }

    /**
     * @param File $file
     */
    public function writeTo(File $file)
    {
        $file->putContents($this->code);
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

}