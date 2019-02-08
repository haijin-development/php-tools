<?php

use Haijin\File_Path;

$spec->describe( "When concatenanting to a File_Path", function() {

    $this->it( "concatenates a string", function() {

        $file_path = ( new File_Path( 'home' ) )->concat( 'dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "concatenates an empty string", function() {

        $file_path = ( new File_Path( 'home' ) )->concat( '' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

    });

    $this->it( "concatenates a string starting with a slash /", function() {

        $file_path = ( new File_Path( 'home' ) )->concat( '/dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "concatenates an array", function() {

        $file_path = ( new File_Path( 'home' ) )->concat( [ 'dev', 'src' ] );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });


    $this->it( "concatenates an empty array", function() {

        $file_path = ( new File_Path( 'home' ) )->concat( [] );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

    });

    $this->it( "concatenates a File_Path", function() {

        $file_path = ( new File_Path( 'home' ) )->concat( new File_Path( 'dev/src' ) );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "concatenates an empty path", function() {

        $file_path = ( new File_Path( 'home' ) )->concat( new File_Path() );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

    });

    $this->it( "does not modify the receiver instance", function() {

        $file_path = new File_Path( 'home' );
        $concatenated_path = $file_path->concat( 'dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );
        $this->expect( $concatenated_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

});