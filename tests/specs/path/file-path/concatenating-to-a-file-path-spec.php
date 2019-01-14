<?php

use Haijin\Tools\FilePath;

$spec->describe( "When concatenanting to a FilePath", function() {

    $this->it( "concatenates a string", function() {

        $file_path = ( new FilePath( 'home' ) )->concat( 'dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "concatenates an empty string", function() {

        $file_path = ( new FilePath( 'home' ) )->concat( '' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

    });

    $this->it( "concatenates an array", function() {

        $file_path = ( new FilePath( 'home' ) )->concat( [ 'dev', 'src' ] );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });


    $this->it( "concatenates an empty array", function() {

        $file_path = ( new FilePath( 'home' ) )->concat( [] );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

    });

    $this->it( "concatenates a FilePath", function() {

        $file_path = ( new FilePath( 'home' ) )->concat( new FilePath( 'dev/src' ) );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "concatenates an empty path", function() {

        $file_path = ( new FilePath( 'home' ) )->concat( new FilePath() );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

    });

    $this->it( "does not modify the receiver instance", function() {

        $file_path = new FilePath( 'home' );
        $concatenated_path = $file_path->concat( 'dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );
        $this->expect( $concatenated_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

});