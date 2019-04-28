<?php

use Haijin\AttributePath;

$spec->describe("When finding the common root with another AttributePath", function () {

    $this->it("returns an empty path if another path is empty", function () {

        $attributePath = new AttributePath();

        $commonPath = $attributePath->rootInCommonWith('address.street');

        $this->expect($commonPath->toString())->to()->equal('');

    });

    $this->it("returns an empty path if there is not root in common", function () {

        $attributePath = new AttributePath('street');

        $commonPath = $attributePath->rootInCommonWith('address.street');

        $this->expect($commonPath->toString())->to()->equal('');


        $attributePath = new AttributePath('address.street');

        $commonPath = $attributePath->rootInCommonWith('street');

        $this->expect($commonPath->toString())->to()->equal('');

    });

    $this->it("returns the common path if there is a common root", function () {

        $attributePath = new AttributePath('user.address.street');

        $commonPath = $attributePath->rootInCommonWith('user.address');

        $this->expect($commonPath->toString())->to()->equal('user.address');


        $attributePath = new AttributePath('user.address');

        $commonPath = $attributePath->rootInCommonWith('user.address.street');

        $this->expect($commonPath->toString())->to()->equal('user.address');

    });

});