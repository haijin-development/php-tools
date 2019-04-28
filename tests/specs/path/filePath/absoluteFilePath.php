<?php

use Haijin\FilePath;

$spec->describe("An absolute FilePath", function () {

    $this->it("is relative by default", function () {
        $filePath = new FilePath();

        $this->expect($filePath->toString())->to()->equal("");

        $this->expect($filePath->isRelative())->to()->be()->true();
        $this->expect($filePath->isAbsolute())->to()->be()->false();

    });

    $this->it("is created from a string", function () {

        $filePath = new FilePath('/home');

        $this->expect($filePath->toString())->to()->equal("/home");

        $this->expect($filePath->isRelative())->to()->be()->false();
        $this->expect($filePath->isAbsolute())->to()->be()->true();

    });

    $this->it("is created from an array", function () {

        $filePath = new FilePath(['home'], true);

        $this->expect($filePath->toString())->to()->equal("/home");

        $this->expect($filePath->isRelative())->to()->be()->false();
        $this->expect($filePath->isAbsolute())->to()->be()->true();

    });

    $this->it("is created from another absolute path", function () {

        $filePath = new FilePath(new FilePath('/home'));

        $this->expect($filePath->toString())->to()->equal("/home");

        $this->expect($filePath->isRelative())->to()->be()->false();
        $this->expect($filePath->isAbsolute())->to()->be()->true();

    });

    $this->it("preserves the absoluteness when creating a path from another path", function () {

        $filePath = new FilePath(new FilePath('/home/dev/src'));

        $this->expect($filePath->toString())->to()->equal("/home/dev/src");

        $this->expect($filePath->isRelative())->to()->be()->false();
        $this->expect($filePath->isAbsolute())->to()->be()->true();

    });

    $this->it("preserves the absoluteness when concatenanting a path", function () {

        $filePath = new FilePath('/home');

        $concatenatedFilePath = $filePath->concat('dev/src');

        $this->expect($concatenatedFilePath->toString())->to()->equal("/home/dev/src");

        $this->expect($concatenatedFilePath->isRelative())->to()->be()->false();
        $this->expect($concatenatedFilePath->isAbsolute())->to()->be()->true();

    });

    $this->it("preserves the absoluteness when appending a path", function () {

        $filePath = new FilePath('/home');

        $concatenatedFilePath = $filePath->append('dev/src');

        $this->expect($filePath->toString())->to()->equal("/home/dev/src");

        $this->expect($concatenatedFilePath->isRelative())->to()->be()->false();
        $this->expect($concatenatedFilePath->isAbsolute())->to()->be()->true();

    });

    $this->it("preserves the absoluteness when going back a path", function () {

        $filePath = new FilePath('/home/dev/src');

        $backedFilePath = $filePath->back();

        $this->expect($backedFilePath->toString())->to()->equal("/home/dev");

        $this->expect($backedFilePath->isRelative())->to()->be()->false();
        $this->expect($backedFilePath->isAbsolute())->to()->be()->true();

    });

    $this->it("preserves the absoluteness when dropping a path", function () {

        $filePath = new FilePath('/home/dev/src');

        $filePath->drop();

        $this->expect($filePath->toString())->to()->equal("/home/dev");

        $this->expect($filePath->isRelative())->to()->be()->false();
        $this->expect($filePath->isAbsolute())->to()->be()->true();

    });

});