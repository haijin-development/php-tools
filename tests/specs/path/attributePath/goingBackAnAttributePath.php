<?php

use Haijin\AttributePath;
use Haijin\Errors\PathError;

$spec->describe("When going back an AttributePath", function () {

    $this->it("goes back one attribute", function () {

        $attributePath = (new AttributePath('user.address.street'))->back();

        $this->expect($attributePath->toString())->to()->equal('user.address');

    });

    $this->it("goes back several attributes", function () {

        $attributePath = (new AttributePath('user.address.street'))->back(0);
        $this->expect($attributePath->toString())->to()->equal('user.address.street');

        $attributePath = (new AttributePath('user.address.street'))->back(2);
        $this->expect($attributePath->toString())->to()->equal('user');

        $attributePath = (new AttributePath('user.address.street'))->back(3);
        $this->expect($attributePath->toString())->to()->equal('');

        $attributePath = (new AttributePath('user.address.street'))->back(4);
        $this->expect($attributePath->toString())->to()->equal('');

    });

    $this->it("testRaisesAnErrorIfNIsNegative", function () {

        $this->expect(function () {

            (new AttributePath('user.address.street'))->back(-1);

        })->to()->raise(
            PathError::class,
            function ($error) {
                $this->expect($error->getMessage())->to()
                    ->equal("Haijin\AttributePath->back( -1 ): invalid parameter -1.");
            });

    });

    $this->it("testDoesNotModifyTheReceiverInstance", function () {

        $attributePath = new AttributePath('user.address.street');
        $backedPath = $attributePath->back();

        $this->expect($attributePath->toString())->to()->equal('user.address.street');

        $this->expect($backedPath->toString())->to()->equal('user.address');

    });

});
