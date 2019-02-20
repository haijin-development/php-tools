<?php

namespace Debugger_Print_String_Spec;

use Haijin\Debugger;

$spec->describe( "When debugging with a Debugger", function() {

    $this->it( "inspects a string", function() {

        $string = Debugger::print_string( "123" );

        $this->expect( $string ) ->to() ->equal( '"123"' );

    });

    $this->it( "inspects an integer", function() {

        $string = Debugger::print_string( 123 );

        $this->expect( $string ) ->to() ->equal( '123' );

    });

    $this->it( "inspects a null", function() {

        $string = Debugger::print_string( null );

        $this->expect( $string ) ->to() ->equal( 'null' );

    });

    $this->it( "inspects a double", function() {

        $string = Debugger::print_string( 1.0 );

        $this->expect( $string ) ->to() ->equal( '1.0' );

    });

    $this->it( "inspects a boolean", function() {

        $string = Debugger::print_string( true );

        $this->expect( $string ) ->to() ->equal( 'true' );


        $string = Debugger::print_string( false );

        $this->expect( $string ) ->to() ->equal( 'false' );

    });

    $this->it( "inspects an array", function() {

        $string = Debugger::print_string( [ 'a', 'b', 'c' ] );

        $this->expect( $string ) ->to() ->equal( "[
   0 => \"a\"
   1 => \"b\"
   2 => \"c\"
]" );

    });

    $this->it( "inspects a nested array", function() {

        $array = [ 1, 2, 3 ];

        $string = Debugger::print_string( [ 'a', $array, 'c' ] );

        $this->expect( $string ) ->to() ->equal( "[
   0 => \"a\"
   1 => [
      0 => 1
      1 => 2
      2 => 3
   ]
   2 => \"c\"
]" );

    });

    $this->it( "inspects an object", function() {

        $string = Debugger::print_string( new Sample( 'a', 'b', 'c' ) );

        $this->expect( $string ) ->to() ->equal( "a Debugger_Print_String_Spec\Sample (1) {
   p_1 => \"a\"
   p_2 => \"b\"
   p_3 => \"c\"
}" );

    });

    $this->it( "inspects an nested object", function() {

        $object = new Sample( '1', '2', '3' );

        $string = Debugger::print_string( new Sample( 'a', $object, 'c' ) );

        $this->expect( $string ) ->to() ->equal( 'a Debugger_Print_String_Spec\Sample (1) {
   p_1 => "a"
   p_2 => a Debugger_Print_String_Spec\Sample (2) {
      p_1 => "1"
      p_2 => "2"
      p_3 => "3"
   }
   p_3 => "c"
}' );

    });

    $this->it( "inspects an nested reference", function() {

        $object = new Sample( 1, 2, 3 );

        $object->p_3 = $object;

        $string = Debugger::print_string( $object );

        $this->expect( $string ) ->to() ->equal( 'a Debugger_Print_String_Spec\Sample (1) {
   p_1 => 1
   p_2 => 2
   p_3 => reference to object (1)
}' );

    });

});

class Sample {
    private $p_1;
    protected $p_2;
    public $p_3;

    public function __construct($p_1, $p_2, $p_3)
    {
        $this->p_1 = $p_1;
        $this->p_2 = $p_2;
        $this->p_3 = $p_3;
    }
}