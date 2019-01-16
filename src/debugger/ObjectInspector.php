<?php

namespace Haijin;

class Object_Inspector
{
    protected $object;
    protected $already_inspected;
    protected $indentation;

    public function __construct($object)
    {
        $this->object = $object;
        $this->already_inspected = [];
        $this->indentation = 0;
    }

    /// Printing

    public function print()
    {
        print "\n";

        $this->print_object( $this->object );
    }

    public function print_object($object)
    {
        print $this->object_type_string( $object );

        if( is_object( $object ) || is_array( $object ) ) {

            print " {" . "\n";

            $this->print_object_instance_variables( $object );

            print $this->indent() . "}";

        }

        print "\n";
    }

    public function object_type_string($object)
    {
        if( $object === null ) {
            return  $this->object_class_name( $object );
        }

        $type = $this->object_class_name( $object );

        if( preg_match( "/^[aeiouAEIOU]/", $type ) ) {

            return "an " . $type;

        } else {

            return "a " . $type;

        }
    }

    public function print_object_instance_variables($object)
    {
        if( is_array( $object ) ) {

            $this->print_array_items( $object );

        }

        if( is_object( $object ) ) {

            $this->print_object_properties( $object );

        }
    }

    public function print_array_items($array)
    {
        $this->indentation += 1;

        foreach( $array as $index => $value ) {

            $this->print_instance_variable( $index, $value );

        }

        $this->indentation -= 1;
    }

    public function print_object_properties($object)
    {
        $this->indentation += 1;

        foreach( $this->instance_variables_of( $object ) as $name => $value ) {

            $this->print_instance_variable( $name, $value );

        }

        $this->indentation -= 1;
    }

    public function print_instance_variable( $index, $value )
    {
        print $this->indent() . $index . ": ";

        $this->print_object( $value );
    }

    public function indent()
    {
        $s = "";

        for( $i = 0; $i < $this->indentation; $i++ ) {
            $s .= "  ";
        }

        return $s;
    }

    /// Querying

    public function object_class_name($object)
    {
        if( $object === null ) {
            return "null";
        }

        if( is_array( $object ) ) {
            return "array";
        }

        if( is_string( $object ) ) {
            return "string";
        }

        if( is_object( $object ) ) {
            return get_class( $this->object );
        }

        return "Unkown type";
    }

    public function instance_variables_of($object)
    {
        return ( new \ReflectionClass( $object ) )->getDefaultProperties();
    }
}