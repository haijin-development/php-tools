<?php

use Haijin\Errors\PathError;
use Haijin\FilePath;

$spec->describe("When going back a FilePath", function () {

    $this->it("goes back one directory", function () {

        $filePath = (new FilePath('home/dev/src'))->back();

        $this->expect($filePath->toString())->to()->equal('home/dev');

    });

    $this->it("goes back n directorys", function () {

        $filePath = (new FilePath('home/dev/src'))->back(0);
        $this->expect($filePath->toString())->to()->equal('home/dev/src');

        $filePath = (new FilePath('home/dev/src'))->back(2);
        $this->expect($filePath->toString())->to()->equal('home');

        $filePath = (new FilePath('home/dev/src'))->back(3);
        $this->expect($filePath->toString())->to()->equal('');

        $filePath = (new FilePath('home/dev/src'))->back(4);
        $this->expect($filePath->toString())->to()->equal('');

    });

    $this->it("raises an error if n is negative", function () {

        $this->expect(
            function () {

                (new FilePath('home/dev/src'))->back(-1);

            })->to()->raise(
            PathError::class,
            function ($error) {

                $this->expect($error->getMessage())->to()
                    ->equal("Haijin\FilePath->back( -1 ): invalid parameter -1.");

            });

    });

    $this->it("does not modify the receiver instance", function () {

        $filePath = new FilePath('home/dev/src');
        $backedPath = $filePath->back();

        $this->expect($filePath->toString())->to()->equal('home/dev/src');
        $this->expect($backedPath->toString())->to()->equal('home/dev');

    });

});