<?php

use Haijin\FilePath;

$spec->describe("When creating FilePath instances", function () {

    $this->it("creates an empty path", function () {

        $filePath = new FilePath();

        $this->expect($filePath->toString())->to()->equal('');

        $this->expect($filePath->isRelative())->to()->be()->true();
        $this->expect($filePath->isAbsolute())->to()->be()->false();

    });

    $this->it("creates an empty string", function () {

        $filePath = new FilePath('');

        $this->expect($filePath->toString())->to()->equal('');

        $this->expect($filePath->isRelative())->to()->be()->true();
        $this->expect($filePath->isAbsolute())->to()->be()->false();

    });

    $this->it("creates an empty array", function () {

        $filePath = new FilePath([]);

        $this->expect($filePath->toString())->to()->equal('');

        $this->expect($filePath->isRelative())->to()->be()->true();
        $this->expect($filePath->isAbsolute())->to()->be()->false();

    });

    $this->it("creates a path from an files string", function () {

        $filePath = new FilePath('home/dev/src');

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

        $this->expect($filePath->isRelative())->to()->be()->true();
        $this->expect($filePath->isAbsolute())->to()->be()->false();

    });

    $this->it("creates a path from a string ending with a slash /", function () {

        $filePath = new FilePath('home/dev/src/');

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

        $this->expect($filePath->isRelative())->to()->be()->true();
        $this->expect($filePath->isAbsolute())->to()->be()->false();
    });

    $this->it("creates a path from an files array", function () {

        $filePath = new FilePath(['home', 'dev', 'src']);

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

        $this->expect($filePath->isRelative())->to()->be()->true();
        $this->expect($filePath->isAbsolute())->to()->be()->false();
    });

    $this->it("creates a path from another path", function () {

        $filePath = new FilePath(new FilePath('home/dev/src'));

        $this->expect($filePath->toString())->to()->equal('home/dev/src');

    });

});