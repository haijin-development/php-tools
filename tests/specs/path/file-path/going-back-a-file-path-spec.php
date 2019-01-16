<?php

use Haijin\File_Path;

$spec->describe( "When going back a File_Path", function() {

    $this->it( "goes back one folder", function() {

        $file_path = ( new File_Path( 'home/dev/src' ) )->back();

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev' );

    });

    $this->it( "goes back n folders", function() {

        $file_path = ( new File_Path( 'home/dev/src' ) )->back( 0 );
        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

        $file_path = ( new File_Path( 'home/dev/src' ) )->back( 2 );
        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

        $file_path = ( new File_Path( 'home/dev/src' ) )->back( 3 );
        $this->expect( $file_path->to_string() ) ->to() ->equal( '' );

        $file_path = ( new File_Path( 'home/dev/src' ) )->back( 4 );
        $this->expect( $file_path->to_string() ) ->to() ->equal( '' );

    });

    $this->it( "raises an error if n is negative", function() {

        $this->expect(
            function() {

            ( new File_Path( 'home/dev/src' ) )->back( -1 );

        }) ->to() ->raise(
            'Haijin\Path_Error',
            function($error) {

                $this->expect( $error->getMessage() ) ->to()
                    ->equal( "Haijin\File_Path->back( -1 ): invalid parameter -1." );

        });

    });

    $this->it( "does not modify the receiver instance", function() {

        $file_path = new File_Path( 'home/dev/src' );
        $backed_path = $file_path->back();

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );
        $this->expect( $backed_path->to_string() ) ->to() ->equal( 'home/dev' );

    });

});