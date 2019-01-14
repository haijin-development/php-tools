<?php

use Haijin\Tools\FilePath;

$spec->describe( "A FilePath", function() {

    $this->it( "gets the file name", function() {

        $file_path = new FilePath( __DIR__ . "/../../../file-samples/file-sample.txt" );

        $this->expect( $file_path->file_name() ) ->to() ->equal( "file-sample.txt" );

    });

    $this->it( "gets the file extension", function() {

        $file_path = new FilePath( __DIR__ . "/../../../file-samples/file-sample.txt" );

        $this->expect( $file_path->file_extension() ) ->to() ->equal( "txt" );

    });

    $this->it( "gets the file contents", function() {

        $file_path = new FilePath( __DIR__ . "/../../../file-samples/file-sample.txt" );

        $this->expect( $file_path->file_contents() ) ->to() ->equal( "Sample" );

    });

});