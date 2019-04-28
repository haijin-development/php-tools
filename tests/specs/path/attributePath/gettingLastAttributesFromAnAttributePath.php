<?php

use Haijin\AttributePath;

$spec->describe("When getting the last attribute from an AttributePath", function () {

    $this->it("get the last attribute from an empty path", function () {

        $attributePath = new AttributePath();

        $this->expect($attributePath->getLastAttribute())->to()->equal('');

    });

    $this->it("get the last attribute from a non empty path", function () {

        $attributePath = new AttributePath('address.street');

        $this->expect($attributePath->getLastAttribute())->to()->equal('street');

    });

});
