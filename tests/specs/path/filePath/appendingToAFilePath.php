<?php

use Haijin\FilePath;

$spec->describe("When appending to a FilePath", function () {

    $this->it("appends a string", function () {

        $filePath = (new FilePath('home'))->append('dev/src');

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

    });

    $this->it("appends an empty string", function () {

        $filePath = (new FilePath('home'))->append('');

        $this->expect($filePath->toString())->to()->equal('home');

    });

    $this->it("appends a string begining with a slash /", function () {

        $filePath = (new FilePath('home'))->append('/dev/src');

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

    });

    $this->it("appends an array", function () {

        $filePath = (new FilePath('home'))->append(['dev', 'src']);

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

    });


    $this->it("appends an empty array", function () {

        $filePath = (new FilePath('home'))->append([]);

        $this->expect($filePath->toString())->to()->equal('home');

    });

    $this->it("appends a FilePath", function () {

        $filePath = (new FilePath('home'))->append(new FilePath('dev/src'));

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

    });

    $this->it("appends an empty FilePath", function () {

        $filePath = (new FilePath('home'))->append(new FilePath());

        $this->expect($filePath->toString())->to()->equal('home');

    });

    $this->it("modifies the receiver instance", function () {

        $filePath = new FilePath('home');
        $filePath->append('dev/src');

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

    });

    $this->it("returns this instance", function () {

        $filePath = new FilePath('home');
        $concatenatedPath = $filePath->append('dev/src');

        $this->expect($concatenatedPath)->to()->be("===")->than($filePath);

    });

});