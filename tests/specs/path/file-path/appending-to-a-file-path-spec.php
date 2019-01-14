<?php

use Haijin\Tools\FilePath;

$spec->describe( "When appending to a FilePath", function() {

    $this->it( "testConcatenatesAString", function() {

        $file_path = ( new FilePath( 'home' ) )->append( 'dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "testConcatenatesAnEmptyString", function() {

        $file_path = ( new FilePath( 'home' ) )->append( '' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

    });

    $this->it( "testConcatenatesAnArray", function() {

        $file_path = ( new FilePath( 'home' ) )->append( [ 'dev', 'src' ] );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });


    $this->it( "testConcatenatesAnEmptyArray", function() {

        $file_path = ( new FilePath( 'home' ) )->append( [] );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

    });

    $this->it( "testConcatenatesAnFilePath", function() {

        $file_path = ( new FilePath( 'home' ) )->append( new FilePath( 'dev/src' ) );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "testConcatenatesAnEmptyPath", function() {

        $file_path = ( new FilePath( 'home' ) )->append( new FilePath() );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home' );

    });

    $this->it( "testModifiesTheReceiverInstance", function() {

        $file_path = new FilePath( 'home' );
        $file_path->append( 'dev/src' );

        $this->expect( $file_path->to_string() ) ->to() ->equal( 'home/dev/src' );

    });

    $this->it( "testReturnsThisInstance", function() {

        $file_path = new FilePath( 'home' );
        $concatenated_path = $file_path->append( 'dev/src' );

        $this->expect( $concatenated_path ) ->to() ->be( "===" ) ->than( $file_path );

    });

});