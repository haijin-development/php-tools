<?php

use Haijin\File_Path;

$spec->describe( "When appending to a File_Path", function() {

    $this->it( "appends a string", function() {

        $file_path = ( new File_Path( 'home' ) )->append( 'dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "appends an empty string", function() {

        $file_path = ( new File_Path( 'home' ) )->append( '' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

    });

    $this->it( "appends a string begining with a slash /", function() {

        $file_path = ( new File_Path( 'home' ) )->append( '/dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "appends an array", function() {

        $file_path = ( new File_Path( 'home' ) )->append( [ 'dev', 'src' ] );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });


    $this->it( "appends an empty array", function() {

        $file_path = ( new File_Path( 'home' ) )->append( [] );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

    });

    $this->it( "appends a File_Path", function() {

        $file_path = ( new File_Path( 'home' ) )->append( new File_Path( 'dev/src' ) );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "appends an empty File_Path", function() {

        $file_path = ( new File_Path( 'home' ) )->append( new File_Path() );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

    });

    $this->it( "modifies the receiver instance", function() {

        $file_path = new File_Path( 'home' );
        $file_path->append( 'dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "returns this instance", function() {

        $file_path = new File_Path( 'home' );
        $concatenated_path = $file_path->append( 'dev/src' );

        $this->expect( $concatenated_path ) ->to() ->be( "===" ) ->than( $file_path );

    });

});