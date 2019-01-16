<?php

namespace Closure_Context_Spec;

use Haijin\Closure_Context;

$spec->describe( "When evaluating a Closure_Context", function() {

    $this->it( "evaluates the closure", function() {

        $object = new Closure_ContextEvaluation();

        $closure_context = new Closure_Context( $object, function() {
            return $this->get_number();
        });

        $this->expect( $closure_context->evaluate() ) ->to() ->equal( 1 );

    });

    $this->it( "evaluates the closure with parameters", function() {

        $object = new Closure_ContextEvaluation();

        $closure_context = new Closure_Context( $object, function($a, $b) {
            return $this->sum( $a, $b );
        });

        $this->expect( $closure_context->evaluate( 3, 4 ) ) ->to() ->equal( 7 );

    });

});

class Closure_ContextEvaluation
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
