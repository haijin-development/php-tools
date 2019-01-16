<?php

namespace Haijin;

class Debugger
{
    static public function inspect($object)
    {
        ( new Object_Inspector( $object ) )->print();
    }
}