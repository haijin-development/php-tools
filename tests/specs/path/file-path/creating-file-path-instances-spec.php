<?php

use Haijin\File_Path;

$spec->describe( "When creating File_Path instances", function() {

    $this->it( "creates an empty path", function() {

        $file_path = new File_Path();

        $this->expect( $file_path->to_string() ) ->to() ->equal( '' );

    });

    $this->it( "creates a path from an files string", function() {

        $file_path = new File_Path( 'home/dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "creates a path from an files array", function() {

        $file_path = new File_Path( ['home', 'dev', 'src'] );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "creates a path from another path", function() {

        $file_path = new File_Path( new File_Path( 'home/dev/src' ) );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

});