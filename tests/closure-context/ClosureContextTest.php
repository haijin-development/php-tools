<?php

use PHPUnit\Framework\TestCase;

use Haijin\Tools\ClosureContext;

class ClosureContextEvaluation
{
    public function get_number()
    {
        return 1;
    }

    public function sum($a, $b)
    {
        return $a + $b;
    }
}

class ClosureContextTest extends TestCase
{
    /**
     * Tests the evaluation of a ClosureContext
     */
    public function testEvaluate()
    {
        $object = new ClosureContextEvaluation();

        $block = new ClosureContext( $object, function() {
            return $this->get_number();
        });

        $this->assertEquals( 1, $block->evaluate() );
    }

    /**
     * Tests the evaluation with paramters of a ClosureContext
     */
    public function testEvaluateWithParameters()
    {
        $object = new ClosureContextEvaluation();

        $block = new ClosureContext( $object, function($a, $b) {
            return $this->sum($a, $b);
        });

        $this->assertEquals( 7, $block->evaluate( 3, 4 ) );
    }
}