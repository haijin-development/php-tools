<?php

use Haijin\FilePath;
use Haijin\Errors\HaijinError;

$spec->describe( "When finding the path walk from a FilePath to another FilePath", function() {

    $this->describe( "with relative paths", function() {

        $this->it( "returns the other path if this path is empty", function() {

            $filePath = new FilePath();

            $anotherPath = new FilePath( 'address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( 'address/street' );

        });

        $this->it( "returns this path if the other path is empty", function() {

            $filePath = new FilePath( 'address/street' );

            $anotherPath = new FilePath();

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '../..' );

        });

        $this->it( "returns the walk path if there is not path in common", function() {

            $filePath = new FilePath( 'address/street' );

            $anotherPath = new FilePath( 'user' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '../../user' );


            $filePath = new FilePath( 'user' );

            $anotherPath = new FilePath( 'address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '../address/street' );

        });

        $this->it( "returns the walk if there is a path in common", function() {

            $filePath = new FilePath( 'user/address/street' );

            $anotherPath = new FilePath( 'user' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '../..' );


            $filePath = new FilePath( 'user' );

            $anotherPath = new FilePath( 'user/address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( 'address/street' );

        });

        $this->it( "returns an empty walk if there are equal", function() {

            $filePath = new FilePath( 'user/address/street' );

            $anotherPath = new FilePath( 'user/address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '' );

        });

    });

    $this->describe( "with absolute paths", function() {

        $this->it( "returns the other path if this path is empty", function() {

            $filePath = new FilePath( '/' );

            $anotherPath = new FilePath( '/address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( 'address/street' );

        });

        $this->it( "returns this path if the other path is empty", function() {

            $filePath = new FilePath( '/address/street' );

            $anotherPath = new FilePath( '/' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '../..' );

        });

        $this->it( "returns the walk path if there is not path in common", function() {

            $filePath = new FilePath( '/address/street' );

            $anotherPath = new FilePath( '/user' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '../../user' );


            $filePath = new FilePath( '/user' );

            $anotherPath = new FilePath( '/address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '../address/street' );

        });

        $this->it( "returns the walk if there is a path in common", function() {

            $filePath = new FilePath( '/user/address/street' );

            $anotherPath = new FilePath( '/user' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '../..' );


            $filePath = new FilePath( '/user' );

            $anotherPath = new FilePath( '/user/address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( 'address/street' );

        });

        $this->it( "returns an empty walk if there are equal", function() {

            $filePath = new FilePath( '/user/address/street' );

            $anotherPath = new FilePath( '/user/address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '' );

        });

        $this->it( "raises an error if one path is absolute and the other one is relative", function() {

            $this->expect( function() {

                $filePath = new FilePath( '/user/address/street' );

                $anotherPath = new FilePath( 'user/address/street' );

                $walk = $filePath->walkTo( $anotherPath );

            }) ->to() ->raise(
                HaijinError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->equal(
                        "Trying to get the common path between an absolute path and a relative path."
                    );
                }
            );


            $this->expect( function() {

                $filePath = new FilePath( 'user/address/street' );

                $anotherPath = new FilePath( '/user/address/street' );

                $walk = $filePath->walkTo( $anotherPath );

            }) ->to() ->raise(
                HaijinError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->equal(
                        "Trying to get the common path between an absolute path and a relative path."
                    );
                }
            );

        });

    });

    $this->describe( "with files with protocols", function() {

        $this->it( "returns the other path if this path is empty", function() {

            $filePath = new FilePath( 'http://' );

            $anotherPath = new FilePath( 'http://address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( 'address/street' );

        });

        $this->it( "returns this path if the other path is empty", function() {

            $filePath = new FilePath( 'http://address/street' );

            $anotherPath = new FilePath( 'http://' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '../..' );

        });

        $this->it( "returns the walk path if there is not path in common", function() {

            $filePath = new FilePath( 'http://address/street' );

            $anotherPath = new FilePath( 'http://user' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '../../user' );


            $filePath = new FilePath( 'http://user' );

            $anotherPath = new FilePath( 'http://address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '../address/street' );

        });

        $this->it( "returns the walk if there is a path in common", function() {

            $filePath = new FilePath( 'http://user/address/street' );

            $anotherPath = new FilePath( 'http://user' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '../..' );


            $filePath = new FilePath( 'http://user' );

            $anotherPath = new FilePath( 'http://user/address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( 'address/street' );

        });

        $this->it( "returns an empty walk if there are equal", function() {

            $filePath = new FilePath( 'http://user/address/street' );

            $anotherPath = new FilePath( 'http://user/address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( '' );

        });

        $this->it( "raises an error if one path has protocol and the other one does not", function() {

            $this->expect( function() {

            $filePath = new FilePath( 'http://user/address/street' );

            $anotherPath = new FilePath( '/user/address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            }) ->to() ->raise(
                HaijinError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->equal(
                        "Trying to get the common path between a path with protocol and a path with no protocol."
                    );
                }
            );


            $this->expect( function() {

            $filePath = new FilePath( '/user/address/street' );

            $anotherPath = new FilePath( 'http://user/address/street' );

            $walk = $filePath->walkTo( $anotherPath );

            }) ->to() ->raise(
                HaijinError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->equal(
                        "Trying to get the common path between a path with protocol and a path with no protocol."
                    );
                }
            );

        });

    });

    $this->describe( "with string parameters", function() {

        $this->it( "does not fail", function() {

            $filePath = new FilePath( 'http://' );

            $anotherPath = 'http://address/street';

            $walk = $filePath->walkTo( $anotherPath );

            $this->expect( $walk->toString() ) ->to() ->equal( 'address/street' );

        });

    });

});