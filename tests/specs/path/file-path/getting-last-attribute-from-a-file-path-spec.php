<?php

use Haijin\File_Path;

$spec->describe( "When getting the last attribute from a File_Path", function() {

    $this->it( "gets the last part from an empty path", function() {

        $file_path = new File_Path();

        $this->expect( $file_path->get_last_attribute() ) ->to() ->be( '' );

    });

    $this->it( "gets the last part from a non empty path", function() {

        $file_path = new File_Path('dev/src');

        $this->expect( $file_path->get_last_attribute() ) ->to() ->be( 'src' );

    });

});