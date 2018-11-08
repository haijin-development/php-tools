<?php

namespace Haijin\Tools;


/**
 * Holds a closure and an object to bind the $this pseudo-variable when evaluating the block.
 *
 * Example:
 *
 *      // Create a sample object
 *
 *      class SampleClass
 *      {
 *          public function sum($a, $b)
 *          {
 *              return $a + $b;
 *          }
 *      }
 *
 *      $object = new SampleClass();
 *
 *
 *      // Create a ClosureContext from a closure and bind it to $object.
 *
 *      $block = new ClosureContext( $object, function($a, $b) {
 *          var_dump( $this );
 *
 *          return $this->sum( $a, $b );
 *      });
 *
 *      // Evaluate the ClosureContext. Within its closure $this will point to $object.
 *
 *      $result = $block->evaluate( 3, 4 );
 *
 *      print $result . "\n";
 */
class ClosureContext
{
    /**
     * Initializes the instance with binding for '$this' pseudo-variable and a closure.
     *
     * @param object $binding The object to be bound to the '$this' pseudo-variable when evaluating the closure.
     * @param callable $closure The closure to evaluate. 
     */
    public function __construct($binding, $closure)
    {
        $this->binding = $binding;
        $this->closure = $closure;
    }

    /**
     * Evaluates the closure binding '$this' pseudo-variable to $this->binding object.
     */
    public function evaluate(...$params)
    {
        return $this->closure->call( $this->binding, ...$params );
    }
}