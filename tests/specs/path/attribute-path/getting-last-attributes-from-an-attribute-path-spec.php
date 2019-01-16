<?php

use Haijin\Attribute_Path;

$spec->describe( "When getting the last attribute from an Attribute_Path", function() {

    $this->it( "get the last attribute from an empty path", function() {

        $attribute_path = new Attribute_Path();

        $this->expect( $attribute_path->get_last_attribute() ) ->to() ->equal( '' );

    });

    $this->it( "get the last attribute from a non empty path", function() {

        $attribute_path = new Attribute_Path('address.street');

        $this->expect( $attribute_path->get_last_attribute() ) ->to() ->equal( 'street' );

    });

});
