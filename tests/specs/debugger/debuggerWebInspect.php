<?php

namespace DebuggerWebInspectSpec;

use ASample;
use Haijin\Debugger;

$spec->describe("When debugging with a Debugger", function () {

    $this->beforeEach(function () {
        ob_start();
    });

    $this->it("inspects a string", function () {

        Debugger::webInspect("123");

        $this->expect(ob_get_clean())->to()->equal("<br>" . '"123"');

    });

    $this->it("inspects an integer", function () {

        Debugger::webInspect(123);

        $this->expect(ob_get_clean())->to()->equal("<br>" . '123');

    });

    $this->it("inspects a null", function () {

        Debugger::webInspect(null);

        $this->expect(ob_get_clean())->to()->equal("<br>" . 'null');

    });

    $this->it("inspects a double", function () {

        Debugger::webInspect(1.1);

        $this->expect(ob_get_clean())->to()->equal("<br>" . '1.1');

    });

    $this->it("inspects a boolean", function () {

        Debugger::webInspect(true);

        $this->expect(ob_get_clean())->to()->equal("<br>" . 'true');

        ob_start();

        Debugger::webInspect(false);

        $this->expect(ob_get_clean())->to()->equal("<br>" . 'false');

    });

    $this->it("inspects an array", function () {

        Debugger::webInspect(['a', 'b', 'c']);

        $this->expect(ob_get_clean())->to()->equal(
            '<br>[<br>&nbsp;&nbsp;&nbsp;0 => "a"<br>&nbsp;&nbsp;&nbsp;1 => "b"<br>&nbsp;&nbsp;&nbsp;2 => "c"<br>]'
        );

    });

    $this->it("inspects a nested array", function () {

        $array = [1, 2, 3];

        Debugger::webInspect(['a', $array, 'c']);

        $this->expect(ob_get_clean())->to()->equal(
            '<br>[<br>&nbsp;&nbsp;&nbsp;0 => "a"<br>&nbsp;&nbsp;&nbsp;1 => [<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0 => 1<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 => 2<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2 => 3<br>&nbsp;&nbsp;&nbsp;]<br>&nbsp;&nbsp;&nbsp;2 => "c"<br>]'
        );

    });

    $this->it("inspects an object", function () {

        Debugger::webInspect(new Sample('a', 'b', 'c'));

        $this->expect(ob_get_clean())->to()->equal(
            '<br>a DebuggerWebInspectSpec\Sample (1) {<br>&nbsp;&nbsp;&nbsp;p_1 => "a"<br>&nbsp;&nbsp;&nbsp;p_2 => "b"<br>&nbsp;&nbsp;&nbsp;p_3 => "c"<br>}'
        );

    });

    $this->it("inspects a class object beginning with a vowel", function () {

        Debugger::webInspect(new ASample('a', 'b', 'c'));

        $this->expect(ob_get_clean())->to()->equal(
            '<br>an ASample (1) {<br>}'
        );

    });

    $this->it("inspects an nested object", function () {

        $object = new Sample('1', '2', '3');

        Debugger::webInspect(new Sample('a', $object, 'c'));

        $this->expect(ob_get_clean())->to()->equal(
            '<br>a DebuggerWebInspectSpec\Sample (1) {<br>&nbsp;&nbsp;&nbsp;p_1 => "a"<br>&nbsp;&nbsp;&nbsp;p_2 => a DebuggerWebInspectSpec\Sample (2) {<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;p_1 => "1"<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;p_2 => "2"<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;p_3 => "3"<br>&nbsp;&nbsp;&nbsp;}<br>&nbsp;&nbsp;&nbsp;p_3 => "c"<br>}'
        );

    });

    $this->it("inspects an nested reference", function () {

        $object = new Sample(1, 2, 3);

        $object->p_3 = $object;

        Debugger::webInspect($object);

        $this->expect(ob_get_clean())->to()->equal(
            '<br>a DebuggerWebInspectSpec\Sample (1) {<br>&nbsp;&nbsp;&nbsp;p_1 => 1<br>&nbsp;&nbsp;&nbsp;p_2 => 2<br>&nbsp;&nbsp;&nbsp;p_3 => reference to object (1)<br>}'
        );

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