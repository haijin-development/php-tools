<?php

use Haijin\File_Path;

$spec->describe( "A File_Path", function() {

    $this->before_all( function() {

        if( !$this->tmp_folder->exists_folder() ) {

            $this->tmp_folder->create_folder_path();

        }

    });

    $this->after_all( function() {

        $this->tmp_folder->delete();

    });

    $this->let( "tmp_folder", function() {

        return new File_Path( __DIR__ . "/../../../tmp/" );

    });

    $this->it( "answers if the file exists", function() {

        $file_path = new File_Path( __DIR__ . "/../../../file-samples/file-sample.txt" );

        $this->expect( $file_path->exists_file() ) ->to() ->be() ->true();

        $file_path = new File_Path( __DIR__ . "/../../../file-samples/no-file.txt" );

        $this->expect( $file_path->exists_file() ) ->to() ->be() ->false();

        $file_path = new File_Path( __DIR__ . "/../../../file-samples" );

        $this->expect( $file_path->exists_file() ) ->to() ->be() ->false();

    });

    $this->it( "answers if the folder exists", function() {

        $file_path = new File_Path( __DIR__ . "/../../../file-samples" );

        $this->expect( $file_path->exists_folder() ) ->to() ->be() ->true();

        $file_path = new File_Path( __DIR__ . "/../../../no-folder" );

        $this->expect( $file_path->exists_folder() ) ->to() ->be() ->false();

        $file_path = new File_Path( __DIR__ . "/../../../file-samples/file-sample.txt" );

        $this->expect( $file_path->exists_folder() ) ->to() ->be() ->false();

    });

    $this->it( "gets the file name", function() {

        $file_path = new File_Path( __DIR__ . "/../../../file-samples/file-sample.txt" );

        $this->expect( $file_path->file_name() ) ->to() ->equal( "file-sample.txt" );

    });

    $this->it( "gets the file extension", function() {

        $file_path = new File_Path( __DIR__ . "/../../../file-samples/file-sample.txt" );

        $this->expect( $file_path->file_extension() ) ->to() ->equal( "txt" );

    });

    $this->it( "gets the file mofication time", function() {

        $file_path = new File_Path( __DIR__ . "/../../../file-samples/file-sample.txt" );

        $this->expect( $file_path->file_modification_time() ) ->to() ->be() ->int();
        $this->expect( $file_path->file_modification_time() ) ->to() ->be( ">" ) ->than( 0 );

    });

    $this->it( "gets the file contents", function() {

        $file_path = new File_Path( __DIR__ . "/../../../file-samples/file-sample.txt" );

        $this->expect( $file_path->file_contents() ) ->to() ->equal( "Sample" );

    });

    $this->it( "writes the file contents", function() {

        $file_path = $this->tmp_folder->concat( "file-sample.txt" );

        $file_path->write_contents( "123" );

        $this->expect( $file_path->file_contents() ) ->to() ->equal( "123" );

    });

    $this->it( "creates a recursive folder", function() {

        $file_path = $this->tmp_folder->concat( "subfolder-1/subfolder-2" );

        $file_path->create_folder_path();

        $this->expect( $file_path->exists_folder() ) ->to() ->be() ->true();

    });

    $this->it( "deletes a recursive folder", function() {

        $file_path = $this->tmp_folder->concat( "subfolder-1/subfolder-2" );

        if( ! $file_path->exists_folder() ) {
            $file_path->create_folder_path();
        }

        $file_path->back( 2 )->delete_folder();

        $this->expect( $file_path->exists_folder() ) ->to() ->be() ->false();

    });

});