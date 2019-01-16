<?php

use Haijin\Attribute_Path;

$spec->describe( "When creating Attribute_Path instances", function() {

    $this->it( "creates an empty path", function() {

        $attribute_path = new Attribute_Path();

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( '' );

    });

    $this->it( "creates a path from an attributes string", function() {

        $attribute_path = new Attribute_Path( 'user.name.address' );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.name.address' );

    });

    $this->it( "creates a path from an attributes array", function() {

        $attribute_path = new Attribute_Path( ['user', 'name', 'address'] );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.name.address' );

    });

    $this->it( "creates a path from another path", function() {

        $attribute_path = new Attribute_Path( new Attribute_Path( 'user.name.address' ) );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.name.address' );

    });


});
