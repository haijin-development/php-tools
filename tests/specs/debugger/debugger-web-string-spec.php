<?php

namespace Debugger_Web_String_Spec;

use Haijin\Debugger;

$spec->describe( "When debugging with a Debugger", function() {

    $this->it( "inspects a string", function() {

        $string = Debugger::web_string( "123" );

        $this->expect( $string ) ->to() ->equal( '"123"' );

    });

    $this->it( "inspects an integer", function() {

        $string = Debugger::web_string( 123 );

        $this->expect( $string ) ->to() ->equal( '123' );

    });

    $this->it( "inspects a null", function() {

        $string = Debugger::web_string( null );

        $this->expect( $string ) ->to() ->equal( 'null' );

    });

    $this->it( "inspects a double", function() {

        $string = Debugger::web_string( 1.0 );

        $this->expect( $string ) ->to() ->equal( '1.0' );

    });

    $this->it( "inspects a boolean", function() {

        $string = Debugger::web_string( true );

        $this->expect( $string ) ->to() ->equal( 'true' );


        $string = Debugger::web_string( false );

        $this->expect( $string ) ->to() ->equal( 'false' );

    });

    $this->it( "inspects an array", function() {

        $string = Debugger::web_string( [ 'a', 'b', 'c' ] );

        $this->expect( $string ) ->to() ->equal( "[<br>   0 => \"a\"<br>   1 => \"b\"<br>   2 => \"c\"<br>]" );

    });

    $this->it( "inspects a nested array", function() {

        $array = [ 1, 2, 3 ];

        $string = Debugger::web_string( [ 'a', $array, 'c' ] );

        $this->expect( $string ) ->to() ->equal( "[<br>   0 => \"a\"<br>   1 => [<br>      0 => 1<br>      1 => 2<br>      2 => 3<br>   ]<br>   2 => \"c\"<br>]" );

    });

    $this->it( "inspects an object", function() {

        $string = Debugger::web_string( new Sample( 'a', 'b', 'c' ) );

        $this->expect( $string ) ->to() ->equal( "a Debugger_Web_String_Spec\Sample (1) {<br>   p_1 => \"a\"<br>   p_2 => \"b\"<br>   p_3 => \"c\"<br>}" );

    });

    $this->it( "inspects an nested object", function() {

        $object = new Sample( '1', '2', '3' );

        $string = Debugger::web_string( new Sample( 'a', $object, 'c' ) );

        $this->expect( $string ) ->to() ->equal( 'a Debugger_Web_String_Spec\Sample (1) {<br>   p_1 => "a"<br>   p_2 => a Debugger_Web_String_Spec\Sample (2) {<br>      p_1 => "1"<br>      p_2 => "2"<br>      p_3 => "3"<br>   }<br>   p_3 => "c"<br>}' );

    });

    $this->it( "inspects an nested circular reference", function() {

        $object = new Sample( 1, 2, 3 );

        $object->p_3 = $object;

        $string = Debugger::web_string( $object );

        $this->expect( $string ) ->to() ->equal( 'a Debugger_Web_String_Spec\Sample (1) {<br>   p_1 => 1<br>   p_2 => 2<br>   p_3 => circular reference to object (1)<br>}' );

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