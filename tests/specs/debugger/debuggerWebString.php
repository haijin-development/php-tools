<?php

namespace DebuggerWebStringSpec;

use Haijin\Debugger;

$spec->describe("When debugging with a Debugger", function () {

    $this->it("inspects a string", function () {

        $string = Debugger::webString("123");

        $this->expect($string)->to()->equal('"123"');

    });

    $this->it("inspects an integer", function () {

        $string = Debugger::webString(123);

        $this->expect($string)->to()->equal('123');

    });

    $this->it("inspects a null", function () {

        $string = Debugger::webString(null);

        $this->expect($string)->to()->equal('null');

    });

    $this->it("inspects a double", function () {

        $string = Debugger::webString(1.0);

        $this->expect($string)->to()->equal('1.0');

    });

    $this->it("inspects a boolean", function () {

        $string = Debugger::webString(true);

        $this->expect($string)->to()->equal('true');


        $string = Debugger::webString(false);

        $this->expect($string)->to()->equal('false');

    });

    $this->it("inspects an array", function () {

        $string = Debugger::webString(['a', 'b', 'c']);

        $this->expect($string)->to()->equal("[<br>&nbsp;&nbsp;&nbsp;0 => \"a\"<br>&nbsp;&nbsp;&nbsp;1 => \"b\"<br>&nbsp;&nbsp;&nbsp;2 => \"c\"<br>]");

    });

    $this->it("inspects a nested array", function () {

        $array = [1, 2, 3];

        $string = Debugger::webString(['a', $array, 'c']);

        $this->expect($string)->to()->equal("[<br>&nbsp;&nbsp;&nbsp;0 => \"a\"<br>&nbsp;&nbsp;&nbsp;1 => [<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0 => 1<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 => 2<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2 => 3<br>&nbsp;&nbsp;&nbsp;]<br>&nbsp;&nbsp;&nbsp;2 => \"c\"<br>]");

    });

    $this->it("inspects an object", function () {

        $string = Debugger::webString(new Sample('a', 'b', 'c'));

        $this->expect($string)->to()->equal("a DebuggerWebStringSpec\Sample (1) {<br>&nbsp;&nbsp;&nbsp;p_1 => \"a\"<br>&nbsp;&nbsp;&nbsp;p_2 => \"b\"<br>&nbsp;&nbsp;&nbsp;p_3 => \"c\"<br>}");

    });

    $this->it("inspects an nested object", function () {

        $object = new Sample('1', '2', '3');

        $string = Debugger::webString(new Sample('a', $object, 'c'));

        $this->expect($string)->to()->equal('a DebuggerWebStringSpec\Sample (1) {<br>&nbsp;&nbsp;&nbsp;p_1 => "a"<br>&nbsp;&nbsp;&nbsp;p_2 => a DebuggerWebStringSpec\Sample (2) {<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;p_1 => "1"<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;p_2 => "2"<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;p_3 => "3"<br>&nbsp;&nbsp;&nbsp;}<br>&nbsp;&nbsp;&nbsp;p_3 => "c"<br>}');

    });

    $this->it("inspects an nested circular reference", function () {

        $object = new Sample(1, 2, 3);

        $object->p_3 = $object;

        $string = Debugger::webString($object);

        $this->expect($string)->to()->equal('a DebuggerWebStringSpec\Sample (1) {<br>&nbsp;&nbsp;&nbsp;p_1 => 1<br>&nbsp;&nbsp;&nbsp;p_2 => 2<br>&nbsp;&nbsp;&nbsp;p_3 => reference to object (1)<br>}');

    });

});

class Sample
{
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