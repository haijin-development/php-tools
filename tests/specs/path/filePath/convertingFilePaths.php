<?php

use Haijin\FilePath;

$spec->describe("When converting a FilePath", function () {

    $this->it("converts the path to an array", function () {

        $filePath = new FilePath();
        $this->expect($filePath->toArray())->to()->equal([]);

        $filePath = new FilePath('home/dev/src');
        $this->expect($filePath->toArray())->to()->equal(['home', 'dev', 'src']);

    });

    $this->it("converts the path to a default string", function () {

        $filePath = new FilePath();
        $this->expect($filePath->toString())->to()->equal('');

        $filePath = new FilePath('home/dev/src');
        $this->expect($filePath->toString())->to()->equal('home/dev/src');

    });

    $this->it("converts the path to a string using a given separator", function () {

        $filePath = new FilePath();
        $this->expect($filePath->toString('/'))->to()->equal('');

        $filePath = new FilePath('home/dev/src');
        $this->expect($filePath->toString('/'))->to()->equal('home/dev/src');

    });

});