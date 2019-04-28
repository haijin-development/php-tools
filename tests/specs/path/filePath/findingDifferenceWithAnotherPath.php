<?php

use Haijin\Errors\HaijinError;
use Haijin\FilePath;

$spec->describe("When finding the difference with another FilePath", function () {

    $this->describe("with relative paths", function () {

        $this->it("returns an empty path if this path is empty", function () {

            $attributePath = new FilePath();
            $anotherPath = new FilePath('address/street');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('');

        });

        $this->it("returns this path if the other path is empty", function () {

            $attributePath = new FilePath('address/street');
            $anotherPath = new FilePath();

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('address/street');

        });

        $this->it("returns this path if there is not path in common", function () {

            $attributePath = new FilePath('address/street');
            $anotherPath = new FilePath('street');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('address/street');

        });

        $this->it("returns the difference if there is a path in common", function () {

            $attributePath = new FilePath('address/street');
            $anotherPath = new FilePath('address');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('street');

        });

        $this->it("returns an empty path if another path contains this path", function () {

            $attributePath = new FilePath('address');
            $anotherPath = new FilePath('address/street');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('');

        });

    });

    $this->describe("with absolute paths", function () {

        $this->it("returns an empty path if this path is empty", function () {

            $attributePath = new FilePath('/');
            $anotherPath = new FilePath('/address/street');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('');

        });

        $this->it("returns this path if the other path is empty", function () {

            $attributePath = new FilePath('/address/street');
            $anotherPath = new FilePath('/');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('address/street');

        });

        $this->it("returns this path if there is not path in common", function () {

            $attributePath = new FilePath('/address/street');
            $anotherPath = new FilePath('/street');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('address/street');

        });

        $this->it("returns the difference if there is a path in common", function () {

            $attributePath = new FilePath('/address/street');
            $anotherPath = new FilePath('/address');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('street');

        });

        $this->it("returns an empty path if another path contains this path", function () {

            $attributePath = new FilePath('/address');
            $anotherPath = new FilePath('/address/street');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('');

        });

        $this->it("raises an error if one path is absolute and the other one is relative", function () {

            $this->expect(function () {

                $attributePath = new FilePath('/address');

                $anotherPath = new FilePath('address/street');

                $attributePath->differenceWith($anotherPath);

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "Trying to get the path difference between an absolute path and a relative path."
                    );
                }
            );


            $this->expect(function () {

                $attributePath = new FilePath('address');

                $anotherPath = new FilePath('/address/street');

                $attributePath->differenceWith($anotherPath);

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "Trying to get the path difference between an absolute path and a relative path."
                    );
                }
            );

        });

    });

    $this->describe("with files with protocols", function () {

        $this->it("returns an empty path if this path is empty", function () {

            $attributePath = new FilePath('http://');
            $anotherPath = new FilePath('http://address/street');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('http://');

        });

        $this->it("returns this path if the other path is empty", function () {

            $attributePath = new FilePath('http://address/street');
            $anotherPath = new FilePath('http://');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('http://address/street');

        });

        $this->it("returns this path if there is not path in common", function () {

            $attributePath = new FilePath('http://address/street');
            $anotherPath = new FilePath('http://street');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('http://address/street');

        });

        $this->it("returns the difference if there is a path in common", function () {

            $attributePath = new FilePath('http://address/street');
            $anotherPath = new FilePath('http://address');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('http://street');

        });

        $this->it("returns an empty path if another path contains this path", function () {

            $attributePath = new FilePath('http://address');
            $anotherPath = new FilePath('http://address/street');

            $this->expect($attributePath->differenceWith($anotherPath)->toString())
                ->to()->equal('http://');

        });

        $this->it("raises an error if one path has protocol and the other one does not", function () {

            $this->expect(function () {

                $attributePath = new FilePath('http://address');

                $anotherPath = new FilePath('/address/street');

                $attributePath->differenceWith($anotherPath);

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "Trying to get the path difference between a path with protocol and a path with no protocol."
                    );
                }
            );


            $this->expect(function () {

                $attributePath = new FilePath('/address');

                $anotherPath = new FilePath('http://address/street');

                $attributePath->differenceWith($anotherPath);

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "Trying to get the path difference between a path with protocol and a path with no protocol."
                    );
                }
            );

        });

    });

    $this->describe("with path strings", function () {

        $this->it("returns an empty path if this path is empty", function () {

            $attributePath = new FilePath();

            $this->expect(
                $attributePath->differenceWith('address/street')->toString()
            )->to()->equal('');

        });

    });

});