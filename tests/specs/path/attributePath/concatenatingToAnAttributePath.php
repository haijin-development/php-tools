<?php

use Haijin\AttributePath;

$spec->describe("When concatenating to an AttributePath", function () {

    $this->it("concatenates a string", function () {

        $attributePath = (new AttributePath('user'))->concat('address.street');

        $this->expect($attributePath->toString())->to()->equal('user.address.street');

    });

    $this->it("concatenates an empty string", function () {

        $attributePath = (new AttributePath('user'))->concat('');

        $this->expect($attributePath->toString())->to()->equal('user');

    });

    $this->it("concatenates an array", function () {

        $attributePath = (new AttributePath('user'))->concat(['address', 'street']);

        $this->expect($attributePath->toString())->to()->equal('user.address.street');

    });


    $this->it("concatenates an empty array", function () {

        $attributePath = (new AttributePath('user'))->concat([]);

        $this->expect($attributePath->toString())->to()->equal('user');

    });

    $this->it("concatenates an AttributePath", function () {

        $attributePath = (new AttributePath('user'))->concat(new AttributePath('address.street'));

        $this->expect($attributePath->toString())->to()->equal('user.address.street');

    });

    $this->it("concatenates an empty path", function () {

        $attributePath = (new AttributePath('user'))->concat(new AttributePath());

        $this->expect($attributePath->toString())->to()->equal('user');

    });

    $this->it("does not modify the receiver instance", function () {

        $attributePath = new AttributePath('user');
        $concatenatedPath = $attributePath->concat('address.street');

        $this->expect($attributePath->toString())->to()->equal('user');

        $this->expect($concatenatedPath->toString())->to()->equal('user.address.street');

    });


});