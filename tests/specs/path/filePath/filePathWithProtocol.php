<?php

use Haijin\FilePath;

$spec->describe("A FilePath with protocol", function () {

    $this->it("is created from a string", function () {

        $filePath = new FilePath('http://home');

        $this->expect($filePath->toString())->to()->equal('http://home');

        $this->expect($filePath->isRelative())->to()->be()->false();

    });

    $this->it("is absolute", function () {

        $filePath = new FilePath('http://home');

        $this->expect($filePath->isRelative())->to()->be()->false();
        $this->expect($filePath->isAbsolute())->to()->be()->true();

    });

    $this->it("preserves the protocol when creating a path from another path", function () {

        $filePath = new FilePath(new FilePath('http://home/dev/src'));

        $this->expect($filePath->toString())->to()
            ->equal('http://home/dev/src');

        $this->expect($filePath->hasProtocol())->to()
            ->be()->true();

    });

    $this->it("preserves the protocol when concatenanting a path", function () {

        $filePath = new FilePath('http://home');

        $concatenatedFilePath = $filePath->concat('dev/src');

        $this->expect($concatenatedFilePath->toString())->to()
            ->equal('http://home/dev/src');

        $this->expect($concatenatedFilePath->hasProtocol())->to()
            ->be()->true();

    });

    $this->it("preserves the protocol when appending a path", function () {

        $filePath = new FilePath('http://home');

        $filePath->append('dev/src');

        $this->expect($filePath->toString())->to()
            ->equal('http://home/dev/src');

        $this->expect($filePath->hasProtocol())->to()
            ->be()->true();

    });

    $this->it("preserves the protocol when going back a path", function () {

        $filePath = new FilePath('http://home/dev/src');

        $backedFilePath = $filePath->back();

        $this->expect($backedFilePath->toString())->to()
            ->equal('http://home/dev');

        $this->expect($backedFilePath->hasProtocol())->to()
            ->be()->true();

    });

    $this->it("preserves the protocol when dropping a path", function () {

        $filePath = new FilePath('http://home/dev/src');

        $filePath->drop();

        $this->expect($filePath->toString())->to()
            ->equal('http://home/dev');

        $this->expect($filePath->hasProtocol())->to()
            ->be()->true();

    });

});