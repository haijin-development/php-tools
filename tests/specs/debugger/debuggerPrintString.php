<?php

namespace DebuggerPrintStringSpec;

use ASample;
use Haijin\Debugger;

$spec->describe("When debugging with a Debugger", function () {

    $this->it("inspects a string", function () {

        $string = Debugger::printString("123");

        $this->expect($string)->to()->equal('"123"');

    });

    $this->it("inspects an integer", function () {

        $string = Debugger::printString(123);

        $this->expect($string)->to()->equal('123');

    });

    $this->it("inspects a null", function () {

        $string = Debugger::printString(null);

        $this->expect($string)->to()->equal('null');

    });

    $this->it("inspects a double", function () {

        $string = Debugger::printString(1.0);

        $this->expect($string)->to()->equal('1.0');

    });

    $this->it("inspects a boolean", function () {

        $string = Debugger::printString(true);

        $this->expect($string)->to()->equal('true');


        $string = Debugger::printString(false);

        $this->expect($string)->to()->equal('false');

    });

    $this->it("inspects an array", function () {

        $string = Debugger::printString(['a', 'b', 'c']);

        $this->expect($string)->to()->equal("[
   0 => \"a\"
   1 => \"b\"
   2 => \"c\"
]");

    });

    $this->it("inspects a nested array", function () {

        $array = [1, 2, 3];

        $string = Debugger::printString(['a', $array, 'c']);

        $this->expect($string)->to()->equal("[
   0 => \"a\"
   1 => [
      0 => 1
      1 => 2
      2 => 3
   ]
   2 => \"c\"
]");

    });

    $this->it("inspects an object", function () {

        $string = Debugger::printString(new Sample('a', 'b', 'c'));

        $this->expect($string)->to()->equal("a DebuggerPrintStringSpec\Sample (1) {
   p_1 => \"a\"
   p_2 => \"b\"
   p_3 => \"c\"
}");

    });

    $this->it("inspects a class object beginning with a vowel", function () {

        $string = Debugger::printString(new ASample('a', 'b', 'c'));

        $this->expect($string)->to()->equal("an ASample (1) {
}");

    });

    $this->it("inspects an nested object", function () {

        $object = new Sample('1', '2', '3');

        $string = Debugger::printString(new Sample('a', $object, 'c'));

        $this->expect($string)->to()->equal('a DebuggerPrintStringSpec\Sample (1) {
   p_1 => "a"
   p_2 => a DebuggerPrintStringSpec\Sample (2) {
      p_1 => "1"
      p_2 => "2"
      p_3 => "3"
   }
   p_3 => "c"
}');

    });

    $this->it("inspects an nested reference", function () {

        $object = new Sample(1, 2, 3);

        $object->p_3 = $object;

        $string = Debugger::printString($object);

        $this->expect($string)->to()->equal('a DebuggerPrintStringSpec\Sample (1) {
   p_1 => 1
   p_2 => 2
   p_3 => reference to object (1)
}');

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