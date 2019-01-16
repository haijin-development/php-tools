<?php

use Haijin\Attribute_Path;

$spec->describe( "When going back an Attribute_Path", function() {

    $this->it( "goes back one attribute", function() {

        $attribute_path = ( new Attribute_Path( 'user.address.street' ) )->back();

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address' );

    });

    $this->it( "goes back several attributes", function() {

        $attribute_path = ( new Attribute_Path( 'user.address.street' ) )->back( 0 );
        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

        $attribute_path = ( new Attribute_Path( 'user.address.street' ) )->back( 2 );
        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

        $attribute_path = ( new Attribute_Path( 'user.address.street' ) )->back( 3 );
        $this->expect( $attribute_path->to_string() ) ->to() ->equal( '' );

        $attribute_path = ( new Attribute_Path( 'user.address.street' ) )->back( 4 );
        $this->expect( $attribute_path->to_string() ) ->to() ->equal( '' );

    });

    $this->it( "testRaisesAnErrorIfNIsNegative", function() {

        $this->expect( function() {

            ( new Attribute_Path( 'user.address.street' ) )->back( -1 );

        }) ->to() ->raise(
            'Haijin\Path_Error',
            function($error) {
                $this->expect( $error->getMessage() ) ->to()
                    ->equal( "Haijin\Attribute_Path->back( -1 ): invalid parameter -1." );
        });

    });

    $this->it( "testDoesNotModifyTheReceiverInstance", function() {

        $attribute_path = new Attribute_Path( 'user.address.street' );
        $backed_path = $attribute_path->back();

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

        $this->expect( $backed_path->to_string() ) ->to() ->equal( 'user.address' );

    });

});
