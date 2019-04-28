<?php

use Haijin\AttributePath;

$spec->describe("When appending attributes to an AttributePath", function () {

    $this->it("concatenates a string", function () {

        $attributePath = (new AttributePath('user'))->append('address.street');

        $this->expect($attributePath->toString())->to()->equal('user.address.street');

    });

    $this->it("concatenates an empty string", function () {

        $attributePath = (new AttributePath('user'))->append('');

        $this->expect($attributePath->toString())->to()->equal('user');

    });

    $this->it("concatenates an array", function () {

        $attributePath = (new AttributePath('user'))->append(['address', 'street']);

        $this->expect($attributePath->toString())->to()->equal('user.address.street');

    });


    $this->it("concatenates an empty array", function () {

        $attributePath = (new AttributePath('user'))->append([]);

        $this->expect($attributePath->toString())->to()->equal('user');

    });

    $this->it("concatenates an AttributePath", function () {

        $attributePath = (new AttributePath('user'))->append(new AttributePath('address.street'));

        $this->expect($attributePath->toString())->to()->equal('user.address.street');

    });

    $this->it("concatenates an empty path", function () {

        $attributePath = (new AttributePath('user'))->append(new AttributePath());

        $this->expect($attributePath->toString())->to()->equal('user');

    });

    $this->it("modifies the receiver instance", function () {

        $attributePath = new AttributePath('user');
        $attributePath->append('address.street');

        $this->expect($attributePath->toString())->to()->equal('user.address.street');

    });

    $this->it("returns this instance", function () {

        $attributePath = new AttributePath('user');
        $concatenatedPath = $attributePath->append('address.street');

        $this->expect($concatenatedPath)->to()->be("===")->than($attributePath);

    });


});