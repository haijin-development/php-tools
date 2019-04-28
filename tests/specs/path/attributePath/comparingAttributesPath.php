<?php

use Haijin\AttributePath;

$spec->describe("When comparing Attribute_Paths", function () {

    $this->describe("with another AttributePath", function () {

        $this->it("returns true if both are empty", function () {

            $attributePath = new AttributePath();

            $this->expect($attributePath->equals(new AttributePath()))
                ->to()->be()->true();

        });

        $this->it("returns true if both are equal", function () {

            $attributePath = new AttributePath('address.street');

            $this->expect($attributePath->equals(new AttributePath('address.street')))
                ->to()->be()->true();

        });

        $this->it("returns false if are not equal", function () {

            $attributePath = new AttributePath('address');

            $this->expect($attributePath->equals(new AttributePath('address.street')))
                ->to()->be()->false();

        });

    });

    $this->describe("with an attributes string", function () {

        $this->it("returns true if both are empty", function () {

            $attributePath = new AttributePath('');

            $this->expect($attributePath->equals(''))
                ->to()->be()->true();

        });

        $this->it("returns true if both are equal", function () {

            $attributePath = new AttributePath('address.street');

            $this->expect($attributePath->equals('address.street'))
                ->to()->be()->true();

        });

        $this->it("returns false if are not equal", function () {

            $attributePath = new AttributePath('address');

            $this->expect($attributePath->equals('address.street'))
                ->to()->be()->false();

        });

    });

    $this->describe("with an attributes array", function () {

        $this->it("returns true if both are empty", function () {

            $attributePath = new AttributePath('');

            $this->expect($attributePath->equals([]))
                ->to()->be()->true();

        });

        $this->it("returns true if both are equal", function () {

            $attributePath = new AttributePath('address.street');

            $this->expect($attributePath->equals(['address', 'street']))
                ->to()->be()->true();

        });

        $this->it("returns false if are not equal", function () {

            $attributePath = new AttributePath('address');

            $this->expect($attributePath->equals(['address', 'street']))
                ->to()->be()->false();

        });

    });

});
