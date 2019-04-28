<?php

namespace Haijin;

use ReflectionClass;

class ObjectInspector
{
    protected $object;
    protected $alreadyInspected;
    protected $crString;
    protected $indentationString;

    public function __construct($object, $indentationLength = 3)
    {
        $this->object = $object;
        $this->alreadyInspected = [];
        $this->indentationLength = $indentationLength;
        $this->crString = null;
        $this->indentationString = null;
    }

    /// Printing

    public function printString()
    {
        $this->crString = "\n";
        $this->indentationString = str_repeat(' ', $this->indentationLength);

        return $this->objectPrintString($this->object);
    }

    protected function objectPrintString($object, $indentation = 0, $line = 0)
    {
        if ($object === null) {
            return 'null';
        }

        if ($object === true) {
            return 'true';
        }

        if ($object === false) {
            return 'false';
        }

        if (is_string($object)) {
            return '"' . $object . '"';
        }

        if (is_array($object)) {

            $string = $this->arrayPrintString($object, $indentation);

            return $string;
        }

        if (is_object($object)) {

            $i = array_search($object, $this->alreadyInspected);

            if ($i !== false) {
                $i += 1;
                return "reference to object ($i)";
            }

            $this->alreadyInspected[] = $object;
            $n = count($this->alreadyInspected);

            $string = $this->objectTypeString($object) . " ($n)" . " {" .
                $this->crString;

            $string .= $this->objectInstanceVariablesPrint($object, $indentation);

            $string .= $this->indent($indentation) . "}";

            return $string;
        }

        return (string)$object;
    }

    protected function arrayPrintString($array, $indentation)
    {
        $string = "";

        $string .= "[" . $this->crString;

        foreach ($array as $key => $value) {

            $string .= $this->indent($indentation + 1);

            $string .= $key . " => ";

            $string .= $this->objectPrintString($value, $indentation + 1) .
                $this->crString;

        }

        $string .= $this->indent($indentation) . "]";

        return $string;
    }

    protected function indent($indentationDepth)
    {
        return str_repeat($this->indentationString, $indentationDepth);
    }

    public function objectTypeString($object)
    {
        if ($object === null) {
            return 'null';
        }

        if ($object === true) {
            return 'true';
        }

        if ($object === false) {
            return 'false';
        }

        if (is_string($object)) {
            return 'a string "' . substr($object, 0, 15) . '"';
        }

        if (is_array($object)) {
            $lenght = count($object);

            return "an Array($lenght)";
        }

        $type = $this->objectClassName($object);

        if (preg_match("/^[aeiouAEIOU]/", $type)) {

            return "an " . $type;

        } else {

            return "a " . $type;

        }
    }

    protected function objectClassName($object)
    {
        return get_class($object);
    }

    protected function objectInstanceVariablesPrint($object, $indentation)
    {
        $string = "";

        foreach ($this->getInstanceVariablesOf($object) as $instVar) {

            $string .= $this->indent($indentation + 1);

            $string .= $instVar->getName() . " => ";

            $instVar->setAccessible(true);
            $value = $instVar->getValue($object);

            $string .= $this->objectPrintString($value, $indentation + 1)
                . $this->crString;

        }

        return $string;
    }

    /// Querying

    protected function getInstanceVariablesOf($object)
    {
        return $this->reflectionOn($object)->getProperties();
    }

    protected function reflectionOn($object)
    {
        return new ReflectionClass($object);
    }

    public function webString()
    {
        $this->crString = '<br>';
        $this->indentationString = str_repeat('&nbsp;', $this->indentationLength);

        return $this->objectPrintString($this->object);
    }
}