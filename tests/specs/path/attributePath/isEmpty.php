<?php

use Haijin\AttributePath;

$spec->describe("When asking for emptiness to an AttributePath", function () {

    $this->it("returns true if it is empty", function () {

        $attributePath = new AttributePath();

        $this->expect($attributePath->isEmpty())->to()->be()->true();
        $this->expect($attributePath->notEmpty())->to()->be()->false();

    });

    $this->it("returns false if it is not empty", function () {

        $attributePath = new AttributePath('address.street');

        $this->expect($attributePath->isEmpty())->to()->be()->false();
        $this->expect($attributePath->notEmpty())->to()->be()->true();

    });

});
