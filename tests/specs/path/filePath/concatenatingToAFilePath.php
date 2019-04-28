<?php

use Haijin\FilePath;

$spec->describe("When concatenanting to a FilePath", function () {

    $this->it("concatenates a string", function () {

        $filePath = (new FilePath('home'))->concat('dev/src');

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

    });

    $this->it("concatenates an empty string", function () {

        $filePath = (new FilePath('home'))->concat('');

        $this->expect($filePath->toString())->to()->equal('home');

    });

    $this->it("concatenates a string starting with a slash /", function () {

        $filePath = (new FilePath('home'))->concat('/dev/src');

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

    });

    $this->it("concatenates an array", function () {

        $filePath = (new FilePath('home'))->concat(['dev', 'src']);

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

    });


    $this->it("concatenates an empty array", function () {

        $filePath = (new FilePath('home'))->concat([]);

        $this->expect($filePath->toString())->to()->equal('home');

    });

    $this->it("concatenates a FilePath", function () {

        $filePath = (new FilePath('home'))->concat(new FilePath('dev/src'));

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

    });

    $this->it("concatenates an empty path", function () {

        $filePath = (new FilePath('home'))->concat(new FilePath());

        $this->expect($filePath->toString())->to()->equal('home');

    });

    $this->it("does not modify the receiver instance", function () {

        $filePath = new FilePath('home');
        $concatenatedPath = $filePath->concat('dev/src');

        $this->expect($filePath->toString())->to()->equal('home');
        $this->expect($concatenatedPath->toString())->to()->equal('home/dev/src');

    });

});