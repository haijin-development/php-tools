<?php

use Haijin\FilePath;

$spec->describe("When comparing File_Paths", function () {

    $this->describe("with another FilePath", function () {

        $this->it("returns true if both are empty", function () {

            $attributePath = new FilePath();

            $this->expect($attributePath->equals(new FilePath()))
                ->to()->be()->true();

        });

        $this->it("returns true if both are equal", function () {

            $attributePath = new FilePath('address/street');

            $this->expect($attributePath->equals(new FilePath('address/street')))
                ->to()->be()->true();

        });

        $this->it("returns false if are not equal", function () {

            $attributePath = new FilePath('address');

            $this->expect($attributePath->equals(new FilePath('address/street')))
                ->to()->be()->false();

        });

    });

    $this->describe("with an attributes string", function () {

        $this->it("returns true if both are empty", function () {

            $attributePath = new FilePath('');

            $this->expect($attributePath->equals(''))
                ->to()->be()->true();

        });

        $this->it("returns true if both are equal", function () {

            $attributePath = new FilePath('address/street');

            $this->expect($attributePath->equals('address/street'))
                ->to()->be()->true();

        });

        $this->it("returns false if are not equal", function () {

            $attributePath = new FilePath('address');

            $this->expect($attributePath->equals('address/street'))
                ->to()->be()->false();

        });

    });

    $this->describe("with an attributes array", function () {

        $this->it("returns true if both are empty", function () {

            $attributePath = new FilePath('');

            $this->expect($attributePath->equals([]))
                ->to()->be()->true();

        });

        $this->it("returns true if both are equal", function () {

            $attributePath = new FilePath('address/street');

            $this->expect($attributePath->equals(['address', 'street']))
                ->to()->be()->true();

        });

        $this->it("returns false if are not equal", function () {

            $attributePath = new FilePath('address');

            $this->expect($attributePath->equals(['address', 'street']))
                ->to()->be()->false();

        });

    });

});
