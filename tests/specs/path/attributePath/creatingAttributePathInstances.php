<?php

use Haijin\AttributePath;

$spec->describe("When creating AttributePath instances", function () {

    $this->it("creates an empty path", function () {

        $attributePath = new AttributePath();

        $this->expect($attributePath->toString())->to()->equal('');

        $this->expect($attributePath->isRelative())->to()->be()->true();
        $this->expect($attributePath->isAbsolute())->to()->be()->false();

    });

    $this->it("creates an empty string", function () {

        $attributePath = new AttributePath('');

        $this->expect($attributePath->toString())->to()->equal('');

        $this->expect($attributePath->isRelative())->to()->be()->true();
        $this->expect($attributePath->isAbsolute())->to()->be()->false();

    });

    $this->it("creates an empty array", function () {

        $attributePath = new AttributePath([]);

        $this->expect($attributePath->toString())->to()->equal('');

        $this->expect($attributePath->isRelative())->to()->be()->true();
        $this->expect($attributePath->isAbsolute())->to()->be()->false();

    });

    $this->it("creates a path from an attributes string", function () {

        $attributePath = new AttributePath('user.name.address');

        $this->expect($attributePath->toString())->to()->equal('user.name.address');

        $this->expect($attributePath->isRelative())->to()->be()->true();
        $this->expect($attributePath->isAbsolute())->to()->be()->false();
    });

    $this->it("creates a path from an attributes array", function () {

        $attributePath = new AttributePath(['user', 'name', 'address']);

        $this->expect($attributePath->toString())->to()->equal('user.name.address');

        $this->expect($attributePath->isRelative())->to()->be()->true();
        $this->expect($attributePath->isAbsolute())->to()->be()->false();
    });

    $this->it("creates a path from another path", function () {

        $attributePath = new AttributePath(new AttributePath('user.name.address'));

        $this->expect($attributePath->toString())->to()->equal('user.name.address');

    });


});
