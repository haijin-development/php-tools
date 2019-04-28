<?php

use Haijin\AttributePath;
use Haijin\Errors\PathError;

$spec->describe("When dropping attributes from an AttributePath", function () {

    $this->it("drops the last attribute", function () {

        $attributePath = (new AttributePath('user.address.street'))->drop();

        $this->expect($attributePath->toString())->to()->equal('user.address');

    });

    $this->it("drops the last n attributes", function () {

        $attributePath = (new AttributePath('user.address.street'))->drop(0);

        $this->expect($attributePath->toString())->to()->equal('user.address.street');

        $attributePath = (new AttributePath('user.address.street'))->drop(2);

        $this->expect($attributePath->toString())->to()->equal('user');

        $attributePath = (new AttributePath('user.address.street'))->drop(3);

        $this->expect($attributePath->toString())->to()->equal('');

        $attributePath = (new AttributePath('user.address.street'))->drop(4);

        $this->expect($attributePath->toString())->to()->equal('');

    });

    $this->it("raises an error if n is negative", function () {

        $this->expect(function () {

            (new AttributePath('user.address.street'))->drop(-1);

        })->to()->raise(
            PathError::class,
            function ($error) {

                $this->expect($error->getMessage())->to()
                    ->equal("Haijin\AttributePath->drop( -1 ): invalid parameter -1.");

            });

    });

    $this->it("modifies the receiver instance", function () {

        $attributePath = new AttributePath('user.address.street');
        $attributePath->drop();

        $this->expect($attributePath->toString())->to()->equal('user.address');

    });

    $this->it("returns this instance", function () {

        $attributePath = new AttributePath('user.address.street');
        $droppedPath = $attributePath->drop();

        $this->expect($droppedPath)->to()->be("===")->than($attributePath);

    });

});
