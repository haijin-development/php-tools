<?php

use Haijin\Tools\FilePath;

$spec->describe( "An absolute FilePath", function() {

    $this->it( "is relative by default", function() {
        $file_path = new FilePath();

        $this->expect( $file_path->to_string() ) ->to() ->equal( "" );

        $this->expect( $file_path->is_relative() ) ->to() ->be() ->true();
        $this->expect( $file_path->is_absolute() ) ->to() ->be() ->false();

    });

    $this->it( "is created from a string", function() {

        $file_path = new FilePath( '/home' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( "/home" );

        $this->expect( $file_path->is_relative() ) ->to() ->be() ->false();
        $this->expect( $file_path->is_absolute() ) ->to() ->be() ->true();

    });

    $this->it( "is created from an array", function() {

        $file_path = new FilePath( [ '/home' ] );

        $this->expect( $file_path->to_string() ) ->to() ->equal( "/home" );

        $this->expect( $file_path->is_relative() ) ->to() ->be() ->false();
        $this->expect( $file_path->is_absolute() ) ->to() ->be() ->true();

    });

    $this->it( "is created from another absolute path", function() {

        $file_path = new FilePath( new FilePath( '/home' ) );

        $this->expect( $file_path->to_string() ) ->to() ->equal( "/home" );

        $this->expect( $file_path->is_relative() ) ->to() ->be() ->false();
        $this->expect( $file_path->is_absolute() ) ->to() ->be() ->true();

    });

    $this->it( "preserves the absoluteness when concatenanting a path", function() {

        $file_path = new FilePath( '/home' );

        $concatenated_file_path = $file_path->concat( 'dev/src' );

        $this->expect( $concatenated_file_path->to_string() ) ->to() ->equal( "/home/dev/src" );

        $this->expect( $concatenated_file_path->is_relative() ) ->to() ->be() ->false();
        $this->expect( $concatenated_file_path->is_absolute() ) ->to() ->be() ->true();

    });

    $this->it( "preserves the absoluteness when appending a path", function() {

        $file_path = new FilePath( '/home' );

        $concatenated_file_path = $file_path->append( 'dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( "/home/dev/src" );

        $this->expect( $concatenated_file_path->is_relative() ) ->to() ->be() ->false();
        $this->expect( $concatenated_file_path->is_absolute() ) ->to() ->be() ->true();

    });

    $this->it( "preserves the absoluteness when going back a path", function() {

        $file_path = new FilePath( '/home/dev/src' );

        $backed_file_path = $file_path->back();

        $this->expect( $backed_file_path->to_string() ) ->to() ->equal( "/home/dev" );

        $this->expect( $backed_file_path->is_relative() ) ->to() ->be() ->false();
        $this->expect( $backed_file_path->is_absolute() ) ->to() ->be() ->true();

    });

    $this->it( "preserves the absoluteness when dropping a path", function() {

        $file_path = new FilePath( '/home/dev/src' );

        $file_path->drop();

        $this->expect( $file_path->to_string() ) ->to() ->equal( "/home/dev" );

        $this->expect( $file_path->is_relative() ) ->to() ->be() ->false();
        $this->expect( $file_path->is_absolute() ) ->to() ->be() ->true();

    });

});