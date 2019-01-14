<?php

use Haijin\Tools\FilePath;

$spec->describe( "When creating FilePath instances", function() {

    $this->it( "creates an empty path", function() {

        $file_path = new FilePath();

        $this->expect( $file_path->to_string() ) ->to() ->equal( '' );

    });

    $this->it( "creates a path from an files string", function() {

        $file_path = new FilePath( 'home/dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "creates a path from an files array", function() {

        $file_path = new FilePath( ['home', 'dev', 'src'] );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "creates a path from another path", function() {

        $file_path = new FilePath( new FilePath( 'home/dev/src' ) );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

});