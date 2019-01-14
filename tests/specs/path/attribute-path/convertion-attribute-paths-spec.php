<?php

use Haijin\Tools\AttributePath;

$spec->describe( "When converting an AttributePath", function() {

    $this->it( "converts to an array", function() {

        $attribute_path = new AttributePath();

        $this->expect( $attribute_path->to_array() ) ->to() ->equal( [] );

        $attribute_path = new AttributePath('user.address.street');

        $this->expect( $attribute_path->to_array() ) ->to()
            ->equal( ['user', 'address', 'street' ] );

    });

    $this->it( "Converts to a default string", function() {

        $attribute_path = new AttributePath();

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( '' );

        $attribute_path = new AttributePath( 'user.address.street' );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });

    $this->it( "converts to a string with a given separator", function() {

        $attribute_path = new AttributePath();

        $this->expect( $attribute_path->to_string( '/' ) ) ->to() ->equal( '' );

        $attribute_path = new AttributePath( 'user.address.street' );

        $this->expect( $attribute_path->to_string( '/' ) ) ->to() ->equal( 'user/address/street' );

    });

});
