<?php

namespace Closure_Context_Spec;

use Haijin\Tools\ClosureContext;

$spec->describe( "When evaluating a ClosureContext", function() {

    $this->it( "evaluates the closure", function() {

        $object = new ClosureContextEvaluation();

        $closure_context = new ClosureContext( $object, function() {
            return $this->get_number();
        });

        $this->expect( $closure_context->evaluate() ) ->to() ->equal( 1 );

    });

    $this->it( "evaluates the closure with parameters", function() {

        $object = new ClosureContextEvaluation();

        $closure_context = new ClosureContext( $object, function($a, $b) {
            return $this->sum( $a, $b );
        });

        $this->expect( $closure_context->evaluate( 3, 4 ) ) ->to() ->equal( 7 );

    });

});

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
