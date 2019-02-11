<?php

namespace Haijin;

class Object_Inspector
{
    protected $object;
    protected $already_inspected;
    protected $cr;

    public function __construct($object)
    {
        $this->object = $object;
        $this->already_inspected = [];
        $this->cr = null;
    }

    /// Printing

    public function print_string()
    {
        $this->cr = "\n";

        return $this->object_print_string( $this->object );
    }

    public function web_string()
    {
        $this->cr = "<br>";

        return $this->object_print_string( $this->object );
    }

    protected function object_print_string($object, $indentation = 0, $line = 0)
    {
        if( $object === null ) {
            return 'null';
        }

        if( $object === true ) {
            return 'true';
        }

        if( $object === false ) {
            return 'false';
        }

        if( is_string( $object ) ) {
            return '"' . $object . '"';
        }

        if( is_array( $object ) ) {

            $string = $this->array_print_string( $object, $indentation );

            return $string;
        }

        if( is_object( $object ) ) {

            $i = array_search( $object, $this->already_inspected );

            if( $i !== false ) {
                $i += 1;
                return "circular reference to object ($i)";
            }

            $this->already_inspected[] = $object;
            $n = count( $this->already_inspected );

            $string  = $this->object_type_string( $object ) . " ($n)" . " {" . $this->cr;

            $string .= $this->object_instance_variables_print( $object, $indentation );

            $string .= $this->indent( $indentation ) . "}";

            return $string;
        }

        return (string) $object;
    }

    protected function object_type_string($object)
    {
        $type = $this->object_class_name( $object );

        if( $object === null ) {
            return $type;
        }

        if( preg_match( "/^[aeiouAEIOU]/", $type ) ) {

            return "an " . $type;

        } else {

            return "a " . $type;

        }
    }

    protected function array_print_string($array, $indentation)
    {
        $string = "";

        $string .= "[" . $this->cr;

        foreach( $array as $key => $value ) {

            $string .= $this->indent( $indentation + 1 );

            $string .= $key . " => ";

            $string .= $this->object_print_string( $value, $indentation + 1 ) . $this->cr;

        }

        $string .= $this->indent( $indentation ) . "]";

        return $string;
    }

    protected function object_instance_variables_print($object, $indentation)
    {
        $string = "";

        foreach( $this->get_instance_variables_of( $object ) as $inst_var ) {

            $string .= $this->indent( $indentation + 1 );

            $string .= $inst_var->getName() . " => ";

            $inst_var->setAccessible( true );
            $value = $inst_var->getValue( $object );

            $string .= $this->object_print_string( $value, $indentation + 1 ) . $this->cr;

        }

        return $string;
    }

    protected function indent($indentation)
    {
        return str_repeat( "   ", $indentation );
    }

    /// Querying

    protected function object_class_name($object)
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
            return get_class( $object );
        }

        return "Unkown type";
    }

    protected function get_instance_variables_of($object)
    {
        return $this->reflection_on( $object )->getProperties();
    }

    protected function reflection_on($object)
    {
        return new \ReflectionClass( $object );
    }
}