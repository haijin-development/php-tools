<?php

use Haijin\Tools\FilePath;

$spec->describe( "When converting a FilePath", function() {

    $this->it( "converts the path to an array", function() {

        $file_path = new FilePath();
        $this->expect( $file_path->to_array() ) ->to() ->equal( [] );

        $file_path = new FilePath('home/dev/src');
        $this->expect( $file_path->to_array() ) ->to() ->equal( ['home', 'dev', 'src' ] );

    });

    $this->it( "converts the path to a default string", function() {

        $file_path = new FilePath();
        $this->expect( $file_path->to_string() ) ->to() ->equal( '' );

        $file_path = new FilePath( 'home/dev/src' );
        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "converts the path to a string using a given separator", function() {

        $file_path = new FilePath();
        $this->expect( $file_path->to_string( '/' ) ) ->to() ->equal( '' );

        $file_path = new FilePath( 'home/dev/src' );
        $this->expect( $file_path->to_string( '/' ) ) ->to() ->equal( 'home/dev/src' );

    });

});