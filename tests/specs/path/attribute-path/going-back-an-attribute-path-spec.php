<?php

use Haijin\Tools\AttributePath;

$spec->describe( "When going back an AttributePath", function() {

    $this->it( "goes back one attribute", function() {

        $attribute_path = ( new AttributePath( 'user.address.street' ) )->back();

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address' );

    });

    $this->it( "goes back several attributes", function() {

        $attribute_path = ( new AttributePath( 'user.address.street' ) )->back( 0 );
        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

        $attribute_path = ( new AttributePath( 'user.address.street' ) )->back( 2 );
        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

        $attribute_path = ( new AttributePath( 'user.address.street' ) )->back( 3 );
        $this->expect( $attribute_path->to_string() ) ->to() ->equal( '' );

        $attribute_path = ( new AttributePath( 'user.address.street' ) )->back( 4 );
        $this->expect( $attribute_path->to_string() ) ->to() ->equal( '' );

    });

    $this->it( "testRaisesAnErrorIfNIsNegative", function() {

        $this->expect( function() {

            ( new AttributePath( 'user.address.street' ) )->back( -1 );

        }) ->to() ->raise(
            'Haijin\Tools\PathError',
            function($error) {
                $this->expect( $error->getMessage() ) ->to()
                    ->equal( "Haijin\Tools\AttributePath->back( -1 ): invalid parameter -1." );
        });

    });

    $this->it( "testDoesNotModifyTheReceiverInstance", function() {

        $attribute_path = new AttributePath( 'user.address.street' );
        $backed_path = $attribute_path->back();

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

        $this->expect( $backed_path->to_string() ) ->to() ->equal( 'user.address' );

    });

});
