<?php

use Haijin\Tools\AttributePath;

$spec->describe( "When getting the last attribute from an AttributePath", function() {

    $this->it( "get the last attribute from an empty path", function() {

        $attribute_path = new AttributePath();

        $this->expect( $attribute_path->get_last_attribute() ) ->to() ->equal( '' );

    });

    $this->it( "get the last attribute from a non empty path", function() {

        $attribute_path = new AttributePath('address.street');

        $this->expect( $attribute_path->get_last_attribute() ) ->to() ->equal( 'street' );

    });

});
