<?php

namespace Haijin;

class Debugger
{
    static public function inspect($object, $indentationLength = null)
    {
        echo "\n" . self::printString($object, $indentationLength);
    }

    static public function printString($object, $indentationLength = null)
    {
        return self::newObjectInspectorOn($object, $indentationLength)
            ->printString();
    }

    static public function newObjectInspectorOn($object)
    {
        return new ObjectInspector($object);
    }

    static public function webInspect($object, $indentationLength = null)
    {
        echo "<br>" . self::webString($object, $indentationLength);
    }

    static public function webString($object, $indentationLength = null)
    {
        return self::newObjectInspectorOn($object, $indentationLength)
            ->webString();
    }

    static public function objectTypeString($object)
    {
        return self::newObjectInspectorOn($object)->objectTypeString($object);
    }
}