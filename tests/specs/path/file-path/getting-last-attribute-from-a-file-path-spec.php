<?php

use Haijin\Tools\FilePath;

$spec->describe( "When getting the last attribute from a FilePath", function() {

    $this->it( "gets the last part from an empty path", function() {

        $file_path = new FilePath();

        $this->expect( $file_path->get_last_attribute() ) ->to() ->be( '' );

    });

    $this->it( "gets the last part from a non empty path", function() {

        $file_path = new FilePath('dev/src');

        $this->expect( $file_path->get_last_attribute() ) ->to() ->be( 'src' );

    });

});