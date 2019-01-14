<?php

use Haijin\Tools\AttributePath;

/**
 * Tests the $path->append( $path ) behaviour.
 */
$spec->describe( "When appending attributes to an AttributePath", function() {

    $this->it( "concatenates a string", function() {

        $attribute_path = ( new AttributePath( 'user' ) )->append( 'address.street' );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });

    $this->it( "testConcatenatesAnEmptyString", function() {

        $attribute_path = ( new AttributePath( 'user' ) )->append( '' );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

    });

    $this->it( "testConcatenatesAnArray", function() {

        $attribute_path = ( new AttributePath( 'user' ) )->append( [ 'address', 'street' ] );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });


    $this->it( "testConcatenatesAnEmptyArray", function() {

        $attribute_path = ( new AttributePath( 'user' ) )->append( [] );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

    });

    $this->it( "testConcatenatesAnAttributePath", function() {

        $attribute_path = ( new AttributePath( 'user' ) )->append( new AttributePath( 'address.street' ) );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });

    $this->it( "testConcatenatesAnEmptyPath", function() {

        $attribute_path = ( new AttributePath( 'user' ) )->append( new AttributePath() );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

    });

    $this->it( "testModifiesTheReceiverInstance", function() {

        $attribute_path = new AttributePath( 'user' );
        $attribute_path->append( 'address.street' );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

    });

    $this->it( "testReturnsThisInstance", function() {

        $attribute_path = new AttributePath( 'user' );
        $concatenated_path = $attribute_path->append( 'address.street' );

        $this->expect( $concatenated_path ) ->to() ->be( "===" ) ->than( $attribute_path );

    });


});