<?php

use Haijin\Tools\AttributePath;

$spec->describe( "When creating AttributePath instances", function() {

    $this->it( "creates an empty path", function() {

        $attribute_path = new AttributePath();

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( '' );

    });

    $this->it( "creates a path from an attributes string", function() {

        $attribute_path = new AttributePath( 'user.name.address' );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.name.address' );

    });

    $this->it( "creates a path from an attributes array", function() {

        $attribute_path = new AttributePath( ['user', 'name', 'address'] );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.name.address' );

    });

    $this->it( "creates a path from another path", function() {

        $attribute_path = new AttributePath( new AttributePath( 'user.name.address' ) );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.name.address' );

    });


});
