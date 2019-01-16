<?php

use Haijin\File_Path;

$spec->describe( "When dropping from a File_Path", function() {

    $this->it( "drops the last folder", function() {

        $file_path = ( new File_Path( 'home/dev/src' ) )->drop();

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev' );

    });

    $this->it( "drops the n last folders", function() {

        $file_path = ( new File_Path( 'home/dev/src' ) )->drop( 0 );
        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

        $file_path = ( new File_Path( 'home/dev/src' ) )->drop( 2 );
        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

        $file_path = ( new File_Path( 'home/dev/src' ) )->drop( 3 );
        $this->expect( $file_path->to_string() ) ->to() ->equal( '' );

        $file_path = ( new File_Path( 'home/dev/src' ) )->drop( 4 );
        $this->expect( $file_path->to_string() ) ->to() ->equal( '' );

    });

    $this->it( "raises an error if n is negative", function() {

        $this->expect( function() {

            ( new File_Path( 'home/dev/src' ) )->drop( -1 );

        }) ->to() ->raise(
            'Haijin\Path_Error',
            function($error) {

                $this->expect( $error->getMessage() ) ->to()
                    ->equal( "Haijin\File_Path->drop( -1 ): invalid parameter -1." );

        });

    });

    $this->it( "modifies the receiver instance", function() {

        $file_path = new File_Path( 'home/dev/src' );
        $file_path->drop();

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev' );

    });

    $this->it( "returns this instance", function() {

        $file_path = new File_Path( 'home/dev/src' );
        $dropped_path = $file_path->drop();

        $this->expect( $dropped_path ) ->to() ->be( "===" ) ->than( $file_path );

    });

});