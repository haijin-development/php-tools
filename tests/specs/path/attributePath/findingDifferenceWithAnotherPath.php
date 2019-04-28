<?php

use Haijin\AttributePath;

$spec->describe("When finding the difference", function () {

    $this->describe("with another AttributePath", function () {

        $this->it("returns an empty path if this path is empty", function () {

            $attributePath = new AttributePath();
            $anotherPath = new AttributePath('address.street');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('');

        });

        $this->it("returns this path if the other path is empty", function () {

            $attributePath = new AttributePath('address.street');
            $anotherPath = new AttributePath();

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('address.street');

        });

        $this->it("returns this path if there is not path in common", function () {

            $attributePath = new AttributePath('address.street');
            $anotherPath = new AttributePath('street');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('address.street');

        });

        $this->it("returns the difference if there is a path in common", function () {

            $attributePath = new AttributePath('address.street');
            $anotherPath = new AttributePath('address');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('street');

        });

        $this->it("returns an empty path if another path contains this path", function () {

            $attributePath = new AttributePath('address');
            $anotherPath = new AttributePath('address.street');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('');

        });

    });

    $this->describe("with an attributes string", function () {

        $this->it("returns an empty path if this path is empty", function () {

            $attributePath = new AttributePath();

            $this->expect(
                $attributePath->differenceWith('address.street')->toString()
            )->to()->equal('');

        });

        $this->it("returns this path if the other path is empty", function () {

            $attributePath = new AttributePath('address.street');

            $this->expect($attributePath->differenceWith('')->toString())
                ->to()->equal('address.street');

        });

        $this->it("returns this path if there is not path in common", function () {

            $attributePath = new AttributePath('address.street');

            $this->expect($attributePath->differenceWith('street')->toString())
                ->to()->equal('address.street');

        });

        $this->it("returns the difference if there is a path in common", function () {

            $attributePath = new AttributePath('address.street');

            $this->expect($attributePath->differenceWith('address')->toString())
                ->to()->equal('street');

        });

        $this->it("returns an empty path if another path contains this path", function () {

            $attributePath = new AttributePath('address');

            $this->expect(
                $attributePath->differenceWith('address.street')->toString()
            )->to()->equal('');

        });

    });

});