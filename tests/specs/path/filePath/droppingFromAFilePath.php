<?php

use Haijin\Errors\PathError;
use Haijin\FilePath;

$spec->describe("When dropping from a FilePath", function () {

    $this->it("drops the last directory", function () {

        $filePath = (new FilePath('home/dev/src'))->drop();

        $this->expect($filePath->toString())->to()->equal('home/dev');

    });

    $this->it("drops the n last directorys", function () {

        $filePath = (new FilePath('home/dev/src'))->drop(0);
        $this->expect($filePath->toString())->to()->equal('home/dev/src');

        $filePath = (new FilePath('home/dev/src'))->drop(2);
        $this->expect($filePath->toString())->to()->equal('home');

        $filePath = (new FilePath('home/dev/src'))->drop(3);
        $this->expect($filePath->toString())->to()->equal('');

        $filePath = (new FilePath('home/dev/src'))->drop(4);
        $this->expect($filePath->toString())->to()->equal('');

    });

    $this->it("raises an error if n is negative", function () {

        $this->expect(function () {

            (new FilePath('home/dev/src'))->drop(-1);

        })->to()->raise(
            PathError::class,
            function ($error) {

                $this->expect($error->getMessage())->to()
                    ->equal("Haijin\FilePath->drop( -1 ): invalid parameter -1.");

            });

    });

    $this->it("modifies the receiver instance", function () {

        $filePath = new FilePath('home/dev/src');
        $filePath->drop();

        $this->expect($filePath->toString())->to()->equal('home/dev');

    });

    $this->it("returns this instance", function () {

        $filePath = new FilePath('home/dev/src');
        $droppedPath = $filePath->drop();

        $this->expect($droppedPath)->to()->be("===")->than($filePath);

    });

});