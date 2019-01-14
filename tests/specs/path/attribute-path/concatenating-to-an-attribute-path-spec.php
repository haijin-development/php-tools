<?php

use Haijin\Tools\AttributePath;

$spec->describe( "When concatenating to an AttributePath", function() {

    $this->it( "concatenates a string", function() {

        $attribute_path = ( new AttributePath( 'user' ) )->concat( 'address.street' );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });

    $this->it( "testConcatenatesAnEmptyString", function() {

        $attribute_path = ( new AttributePath( 'user' ) )->concat( '' );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

    });

    $this->it( "testConcatenatesAnArray", function() {

        $attribute_path = ( new AttributePath( 'user' ) )->concat( [ 'address', 'street' ] );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });


    $this->it( "testConcatenatesAnEmptyArray", function() {

        $attribute_path = ( new AttributePath( 'user' ) )->concat( [] );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

    });

    $this->it( "testConcatenatesAnAttributePath", function() {

        $attribute_path = ( new AttributePath( 'user' ) )->concat( new AttributePath( 'address.street' ) );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });

    $this->it( "testConcatenatesAnEmptyPath", function() {

        $attribute_path = ( new AttributePath( 'user' ) )->concat( new AttributePath() );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

    });

    $this->it( "testDoesNotModifyTheReceiverInstance", function() {

        $attribute_path = new AttributePath( 'user' );
        $concatenated_path = $attribute_path->concat( 'address.street' );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

        $this->expect( $concatenated_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });


});