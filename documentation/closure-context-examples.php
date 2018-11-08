<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Haijin\Tools\ClosureContext;

// Create a sample object

class SampleClass
{
    public function sum($a, $b)
    {
        return $a + $b;
    }
}

$object = new SampleClass();


// Create a ClosureContext from a closure and bind it to $object.

$closure = new ClosureContext( $object, function($a, $b) {
    var_dump( $this );

    return $this->sum( $a, $b );
});

// Evaluate the ClosureContext. Within its closure $this will point to $object.

$result = $closure->evaluate( 3, 4 );

print $result . "\n";