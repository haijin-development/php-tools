<?php

use Haijin\Errors\HaijinError;
use Haijin\FilePath;

$spec->describe("When asking if a FilePath begins with another path", function () {

    $this->describe("with relatives path", function () {

        $this->it("returns true if another path is empty", function () {

            $filePath = new FilePath();

            $beginsWith = $filePath->beginsWith('address/street');

            $this->expect($beginsWith)->to()->be()->false();

        });

        $this->it("returns true if the path begins with the other path", function () {

            $filePath = new FilePath('user/address/street');

            $beginsWith = $filePath->beginsWith('user/address/street');

            $this->expect($beginsWith)->to()->be()->true();


            $filePath = new FilePath('user/address/street');

            $beginsWith = $filePath->beginsWith('user/address');

            $this->expect($beginsWith)->to()->be()->true();


            $filePath = new FilePath('user/address/street');

            $beginsWith = $filePath->beginsWith('');

            $this->expect($beginsWith)->to()->be()->true();

        });

        $this->it("returns false if the path does not begin with the other path", function () {

            $filePath = new FilePath('user/address/street');

            $beginsWith = $filePath->beginsWith('other/path');

            $this->expect($beginsWith)->to()->be()->false();


            $filePath = new FilePath('user/address/street');

            $beginsWith = $filePath->beginsWith('user/address/street/name');

            $this->expect($beginsWith)->to()->be()->false();


            $filePath = new FilePath('user/address/street');

            $beginsWith = $filePath->beginsWith('address/street');

            $this->expect($beginsWith)->to()->be()->false();


            $filePath = new FilePath('');

            $beginsWith = $filePath->beginsWith('address');

            $this->expect($beginsWith)->to()->be()->false();

        });

    });

    $this->describe("with absolute path", function () {

        $this->it("returns true if another path is empty", function () {

            $filePath = new FilePath('/');

            $beginsWith = $filePath->beginsWith('/address/street');

            $this->expect($beginsWith)->to()->be()->false();

        });

        $this->it("returns true if the path begins with the other path", function () {

            $filePath = new FilePath('/user/address/street');

            $beginsWith = $filePath->beginsWith('/user/address/street');

            $this->expect($beginsWith)->to()->be()->true();


            $filePath = new FilePath('/user/address/street');

            $beginsWith = $filePath->beginsWith('/user/address');

            $this->expect($beginsWith)->to()->be()->true();


            $filePath = new FilePath('/user/address/street');

            $beginsWith = $filePath->beginsWith('/');

            $this->expect($beginsWith)->to()->be()->true();

        });

        $this->it("returns false if the path does not begin with the other path", function () {

            $filePath = new FilePath('/user/address/street');

            $beginsWith = $filePath->beginsWith('/other/path');

            $this->expect($beginsWith)->to()->be()->false();


            $filePath = new FilePath('/user/address/street');

            $beginsWith = $filePath->beginsWith('/user/address/street/name');

            $this->expect($beginsWith)->to()->be()->false();


            $filePath = new FilePath('/user/address/street');

            $beginsWith = $filePath->beginsWith('/address/street');

            $this->expect($beginsWith)->to()->be()->false();


            $filePath = new FilePath('');

            $beginsWith = $filePath->beginsWith('address');

            $this->expect($beginsWith)->to()->be()->false();

        });

        $this->it("raises an error if one path is absolute and the other one is not", function () {

            $this->expect(function () {

                $filePath = new FilePath('/address/street');

                $commonPath = $filePath->rootInCommonWith('address');

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "Trying to get the common path between an absolute path and a relative path."
                    );
                }
            );

            $this->expect(function () {

                $filePath = new FilePath('address/street');

                $commonPath = $filePath->rootInCommonWith('/address');

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "Trying to get the common path between an absolute path and a relative path."
                    );
                }
            );

        });

    });

    $this->describe("with a protocol path", function () {

        $this->it("returns true if another path is empty", function () {

            $filePath = new FilePath('http://');

            $beginsWith = $filePath->beginsWith('http://address/street');

            $this->expect($beginsWith)->to()->be()->false();

        });

        $this->it("returns true if the path begins with the other path", function () {

            $filePath = new FilePath('http://user/address/street');

            $beginsWith = $filePath->beginsWith('http://user/address/street');

            $this->expect($beginsWith)->to()->be()->true();


            $filePath = new FilePath('http://user/address/street');

            $beginsWith = $filePath->beginsWith('http://user/address');

            $this->expect($beginsWith)->to()->be()->true();


            $filePath = new FilePath('http://user/address/street');

            $beginsWith = $filePath->beginsWith('http://');

            $this->expect($beginsWith)->to()->be()->true();

        });

        $this->it("returns false if the path does not begin with the other path", function () {

            $filePath = new FilePath('http://user/address/street');

            $beginsWith = $filePath->beginsWith('http://other/path');

            $this->expect($beginsWith)->to()->be()->false();


            $filePath = new FilePath('http://user/address/street');

            $beginsWith = $filePath->beginsWith('http://user/address/street/name');

            $this->expect($beginsWith)->to()->be()->false();


            $filePath = new FilePath('http://user/address/street');

            $beginsWith = $filePath->beginsWith('http://address/street');

            $this->expect($beginsWith)->to()->be()->false();


            $filePath = new FilePath('http://');

            $beginsWith = $filePath->beginsWith('http://address');

            $this->expect($beginsWith)->to()->be()->false();

        });

        $this->it("raises an error if one path has protocol and the other one does not", function () {

            $this->expect(function () {

                $filePath = new FilePath('http://user/address/street');

                $commonPath = $filePath->beginsWith('/user');

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "Trying to answer if a path with protocol begins with a path with no protocol."
                    );
                }
            );

            $this->expect(function () {

                $filePath = new FilePath('/user/address/street');

                $commonPath = $filePath->beginsWith('http://user');

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "Trying to answer if a path with protocol begins with a path with no protocol."
                    );
                }
            );

        });

    });

});