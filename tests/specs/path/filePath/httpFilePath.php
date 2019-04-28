<?php

use Haijin\FilePath;

$spec->describe("An FilePath begining with http://", function () {

    $this->it("is absolute", function () {

        $filePath = new FilePath('http://site.com/file.js');

        $this->expect($filePath->isRelative())->to()->be()->false();
        $this->expect($filePath->isAbsolute())->to()->be()->true();

    });

    $this->it("renders back to a string", function () {

        $filePath = new FilePath('http://site.com/file.js');

        $this->expect($filePath->toString())
            ->to()->equal('http://site.com/file.js');

    });

    $this->it("gets its file protocol", function () {

        $filePath = new FilePath('http://site.com/file.js');

        $this->expect($filePath->getProtocol())->to()->equal('http');

    });

    $this->it("preserves the protocol when creating a new FilePath", function () {

        $filePath = new FilePath('http://site.com/file.js');

        $filePath = new FilePath($filePath);

        $this->expect($filePath->toString())
            ->to()->equal('http://site.com/file.js');

    });

});