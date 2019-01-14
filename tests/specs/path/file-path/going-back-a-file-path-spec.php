<?php

use Haijin\Tools\FilePath;

$spec->describe( "When going back a FilePath", function() {

    $this->it( "goes back one folder", function() {

        $file_path = ( new FilePath( 'home/dev/src' ) )->back();

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev' );

    });

    $this->it( "goes back n folders", function() {

        $file_path = ( new FilePath( 'home/dev/src' ) )->back( 0 );
        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

        $file_path = ( new FilePath( 'home/dev/src' ) )->back( 2 );
        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

        $file_path = ( new FilePath( 'home/dev/src' ) )->back( 3 );
        $this->expect( $file_path->to_string() ) ->to() ->equal( '' );

        $file_path = ( new FilePath( 'home/dev/src' ) )->back( 4 );
        $this->expect( $file_path->to_string() ) ->to() ->equal( '' );

    });

    $this->it( "raises an error if n is negative", function() {

        $this->expect(
            function() {

            ( new FilePath( 'home/dev/src' ) )->back( -1 );

        }) ->to() ->raise(
            'Haijin\Tools\PathError',
            function($error) {

                $this->expect( $error->getMessage() ) ->to()
                    ->equal( "Haijin\Tools\FilePath->back( -1 ): invalid parameter -1." );

        });

    });

    $this->it( "does not modify the receiver instance", function() {

        $file_path = new FilePath( 'home/dev/src' );
        $backed_path = $file_path->back();

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );
        $this->expect( $backed_path->to_string() ) ->to() ->equal( 'home/dev' );

    });

});