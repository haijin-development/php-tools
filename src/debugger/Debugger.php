<?php

namespace Haijin;

class Debugger
{
    static public function inspect($object)
    {
        echo "\n" . self::print_string( $object );
    }

    static public function web_inspect($object)
    {
        echo "<br>" . self::web_string( $object );
    }

    static public function print_string($object)
    {
        return self::new_object_inspector_on( $object )->print_string();
    }

    static public function web_string($object)
    {
        return self::new_object_inspector_on( $object )->web_string();
    }

    static public function new_object_inspector_on($object)
    {
        return new Object_Inspector( $object );
    }
}