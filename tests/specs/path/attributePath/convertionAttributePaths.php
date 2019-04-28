<?php

use Haijin\AttributePath;

$spec->describe("When converting an AttributePath", function () {

    $this->it("converts to an array", function () {

        $attributePath = new AttributePath();

        $this->expect($attributePath->toArray())->to()->equal([]);

        $attributePath = new AttributePath('user.address.street');

        $this->expect($attributePath->toArray())->to()
            ->equal(['user', 'address', 'street']);

    });

    $this->it("Converts to a default string", function () {

        $attributePath = new AttributePath();

        $this->expect($attributePath->toString())->to()->equal('');

        $attributePath = new AttributePath('user.address.street');

        $this->expect($attributePath->toString())->to()->equal('user.address.street');

    });

    $this->it("converts to a string with a given separator", function () {

        $attributePath = new AttributePath();

        $this->expect($attributePath->toString('/'))->to()->equal('');

        $attributePath = new AttributePath('user.address.street');

        $this->expect($attributePath->toString('/'))->to()->equal('user/address/street');

    });

});
