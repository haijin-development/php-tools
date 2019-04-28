<?php

namespace DebuggerInspectSpec;

use ASample;
use Haijin\Debugger;

$spec->describe("When debugging with a Debugger", function () {

    $this->beforeEach(function () {
        ob_start();
    });

    $this->it("inspects a string", function () {

        Debugger::inspect("123");

        $this->expect(ob_get_clean())->to()->equal("\n" . '"123"');

    });

    $this->it("inspects an integer", function () {

        Debugger::inspect(123);

        $this->expect(ob_get_clean())->to()->equal("\n" . '123');

    });

    $this->it("inspects a null", function () {

        Debugger::inspect(null);

        $this->expect(ob_get_clean())->to()->equal("\n" . 'null');

    });

    $this->it("inspects a double", function () {

        Debugger::inspect(1.0);

        $this->expect(ob_get_clean())->to()->equal("\n" . '1.0');

    });

    $this->it("inspects a boolean", function () {

        Debugger::inspect(true);

        $this->expect(ob_get_clean())->to()->equal("\n" . 'true');

        ob_start();

        Debugger::inspect(false);

        $this->expect(ob_get_clean())->to()->equal("\n" . 'false');

    });

    $this->it("inspects an array", function () {

        Debugger::inspect(['a', 'b', 'c']);

        $this->expect(ob_get_clean())->to()->equal("\n" . "[
   0 => \"a\"
   1 => \"b\"
   2 => \"c\"
]");

    });

    $this->it("inspects a nested array", function () {

        $array = [1, 2, 3];

        Debugger::inspect(['a', $array, 'c']);

        $this->expect(ob_get_clean())->to()->equal("\n" . "[
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

        Debugger::inspect(new Sample('a', 'b', 'c'));

        $this->expect(ob_get_clean())->to()->equal("\n" . "a DebuggerInspectSpec\Sample (1) {
   p_1 => \"a\"
   p_2 => \"b\"
   p_3 => \"c\"
}");

    });

    $this->it("inspects a class object beginning with a vowel", function () {

        Debugger::inspect(new ASample('a', 'b', 'c'));

        $this->expect(ob_get_clean())->to()->equal("\n" . "an ASample (1) {
}");

    });

    $this->it("inspects an nested object", function () {

        $object = new Sample('1', '2', '3');

        Debugger::inspect(new Sample('a', $object, 'c'));

        $this->expect(ob_get_clean())->to()->equal("\n" . 'a DebuggerInspectSpec\Sample (1) {
   p_1 => "a"
   p_2 => a DebuggerInspectSpec\Sample (2) {
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

        Debugger::inspect($object);

        $this->expect(ob_get_clean())->to()->equal("\n" . 'a DebuggerInspectSpec\Sample (1) {
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