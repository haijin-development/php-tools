<?php

use Haijin\FilePath;
use Haijin\Errors\HaijinError;

$spec->describe( "When finding the common root with another FilePath", function() {

    $this->describe( "with relative paths", function() {

        $this->it( "returns an empty path if another path is empty", function() {

            $attributePath = new FilePath();

            $commonPath = $attributePath->rootInCommonWith( 'address/street' );

            $this->expect( $commonPath->toString() ) ->to() ->equal( '' );

        });

        $this->it( "returns an empty path if there is no root in common", function() {

            $attributePath = new FilePath( 'street' );

            $commonPath = $attributePath->rootInCommonWith( 'address/street' );

            $this->expect( $commonPath->toString() ) ->to() ->equal( '' );


            $attributePath = new FilePath( 'address/street' );

            $commonPath = $attributePath->rootInCommonWith( 'street' );

            $this->expect( $commonPath->toString() ) ->to() ->equal( '' );

        });

        $this->it( "returns the common path if there is a common root", function() {

            $attributePath = new FilePath( 'user/address/street' );

            $commonPath = $attributePath->rootInCommonWith( 'user/address' );

            $this->expect( $commonPath->toString() ) ->to() ->equal( 'user/address' );



            $attributePath = new FilePath( 'user/address' );

            $commonPath = $attributePath->rootInCommonWith( 'user/address/street' );

            $this->expect( $commonPath->toString() ) ->to() ->equal( 'user/address' );

        });

    });

    $this->describe( "with absolute paths", function() {

        $this->it( "returns a root path if there is no root in common", function() {

            $attributePath = new FilePath( '/street' );

            $commonPath = $attributePath->rootInCommonWith( '/address/street' );

            $this->expect( $commonPath->toString() ) ->to() ->equal( '/' );


            $attributePath = new FilePath( '/address/street' );

            $commonPath = $attributePath->rootInCommonWith( '/street' );

            $this->expect( $commonPath->toString() ) ->to() ->equal( '/' );

        });

        $this->it( "raises an error if one is absolute and the other one relative", function() {

            $this->expect( function() {

                $attributePath = new FilePath( '/address/street' );

                $commonPath = $attributePath->rootInCommonWith( 'address' );

            }) ->to() ->raise(
                HaijinError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->equal(
                        "Trying to get the common path between an absolute path and a relative path."
                    );
                }
            );

            $this->expect( function() {

                $attributePath = new FilePath( 'address/street' );

                $commonPath = $attributePath->rootInCommonWith( '/address' );


            }) ->to() ->raise(
                HaijinError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->equal(
                        "Trying to get the common path between an absolute path and a relative path."
                    );
                }
            );

        });

        $this->it( "returns the common path if there is a common root", function() {

            $attributePath = new FilePath( '/user/address/street' );

            $commonPath = $attributePath->rootInCommonWith( '/user/address' );

            $this->expect( $commonPath->toString() ) ->to() ->equal( '/user/address' );



            $attributePath = new FilePath( '/user/address' );

            $commonPath = $attributePath->rootInCommonWith( '/user/address/street' );

            $this->expect( $commonPath->toString() ) ->to() ->equal( '/user/address' );

        });

    });

    $this->describe( "with paths with protocols paths", function() {

        $this->it( "returns a root path if there is no root in common", function() {

            $attributePath = new FilePath( 'http://street' );

            $commonPath = $attributePath->rootInCommonWith( 'http://address/street' );

            $this->expect( $commonPath->toString() ) ->to() ->equal( 'http://' );
            $this->expect( $commonPath->hasProtocol() ) ->to() ->be() ->true();


            $attributePath = new FilePath( 'http://address/street' );

            $commonPath = $attributePath->rootInCommonWith( 'http://street' );

            $this->expect( $commonPath->toString() ) ->to() ->equal( 'http://' );
            $this->expect( $commonPath->hasProtocol() ) ->to() ->be() ->true();

        });

        $this->it( "raises an error if one has a protocol other one does not", function() {

            $this->expect( function() {

                $attributePath = new FilePath( 'http://address/street' );

                $attributePath->rootInCommonWith( 'address' );

            }) ->to() ->raise(
                HaijinError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->equal(
                        "Trying to get the common path between a path with protocol and a path with no protocol."
                    );
                }
            );


            $this->expect( function() {

                $attributePath = new FilePath( '/address/street' );

                $attributePath->rootInCommonWith( 'http://address' );

            }) ->to() ->raise(
                HaijinError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->equal(
                        "Trying to get the common path between a path with protocol and a path with no protocol."
                    );
                }
            );

        });

        $this->it( "returns the common path if there is a common root", function() {

            $attributePath = new FilePath( 'http://user/address/street' );

            $commonPath = $attributePath->rootInCommonWith( 'http://user/address' );

            $this->expect( $commonPath->toString() ) ->to()
                ->equal( 'http://user/address' );

            $this->expect( $commonPath->hasProtocol() ) ->to() ->be() ->true();



            $attributePath = new FilePath( 'http://user/address' );

            $commonPath = $attributePath->rootInCommonWith(
                    'http://user/address/street'
                );

            $this->expect( $commonPath->toString() ) ->to()
                ->equal( 'http://user/address' );

            $this->expect( $commonPath->hasProtocol() ) ->to() ->be() ->true();

        });

    });

});