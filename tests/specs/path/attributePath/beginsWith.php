<?php

use Haijin\AttributePath;

$spec->describe("When asking if an AttributePath begins with another path", function () {

    $this->it("returns true if another path is empty", function () {

        $attributePath = new AttributePath();

        $beginsWith = $attributePath->beginsWith('address.street');

        $this->expect($beginsWith)->to()->be()->false();

    });

    $this->it("returns true if the path begins with the other path", function () {

        $attributePath = new AttributePath('user.address.street');

        $beginsWith = $attributePath->beginsWith('user.address.street');

        $this->expect($beginsWith)->to()->be()->true();


        $attributePath = new AttributePath('user.address.street');

        $beginsWith = $attributePath->beginsWith('user.address');

        $this->expect($beginsWith)->to()->be()->true();


        $attributePath = new AttributePath('user.address.street');

        $beginsWith = $attributePath->beginsWith('');

        $this->expect($beginsWith)->to()->be()->true();

    });

    $this->it("returns false if the path does not begin with the other path", function () {

        $attributePath = new AttributePath('user.address.street');

        $beginsWith = $attributePath->beginsWith('other.path');

        $this->expect($beginsWith)->to()->be()->false();


        $attributePath = new AttributePath('user.address.street');

        $beginsWith = $attributePath->beginsWith('user.address.street.name');

        $this->expect($beginsWith)->to()->be()->false();


        $attributePath = new AttributePath('user.address.street');

        $beginsWith = $attributePath->beginsWith('address.street');

        $this->expect($beginsWith)->to()->be()->false();


        $attributePath = new AttributePath('');

        $beginsWith = $attributePath->beginsWith('address');

        $this->expect($beginsWith)->to()->be()->false();

    });

});