<?php

use Haijin\FilePath;

$spec->describe("When getting the last attribute from a FilePath", function () {

    $this->it("gets the last part from an empty path", function () {

        $filePath = new FilePath();

        $this->expect($filePath->getLastAttribute())->to()->be('');

    });

    $this->it("gets the last part from a non empty path", function () {

        $filePath = new FilePath('dev/src');

        $this->expect($filePath->getLastAttribute())->to()->be('src');

    });

});