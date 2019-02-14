<?php

use Haijin\Attribute_Path;

$spec->describe( "When concatenating to an Attribute_Path", function() {

    $this->it( "concatenates a string", function() {

        $attribute_path = ( new Attribute_Path( 'user' ) )->concat( 'address.street' );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });

    $this->it( "concatenates an empty string", function() {

        $attribute_path = ( new Attribute_Path( 'user' ) )->concat( '' );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

    });

    $this->it( "concatenates an array", function() {

        $attribute_path = ( new Attribute_Path( 'user' ) )->concat( [ 'address', 'street' ] );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });


    $this->it( "concatenates an empty array", function() {

        $attribute_path = ( new Attribute_Path( 'user' ) )->concat( [] );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

    });

    $this->it( "concatenates an Attribute_Path", function() {

        $attribute_path = ( new Attribute_Path( 'user' ) )->concat( new Attribute_Path( 'address.street' ) );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });

    $this->it( "concatenates an empty path", function() {

        $attribute_path = ( new Attribute_Path( 'user' ) )->concat( new Attribute_Path() );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

    });

    $this->it( "does not modify the receiver instance", function() {

        $attribute_path = new Attribute_Path( 'user' );
        $concatenated_path = $attribute_path->concat( 'address.street' );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

        $this->expect( $concatenated_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });


});