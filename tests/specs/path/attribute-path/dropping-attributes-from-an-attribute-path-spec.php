<?php

use Haijin\Attribute_Path;

$spec->describe( "When dropping attributes from an Attribute_Path", function() {

    $this->it( "drops the last attribute", function() {

        $attribute_path = ( new Attribute_Path( 'user.address.street' ) )->drop();

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address' );

    });

    $this->it( "drops the last n attributes", function() {

        $attribute_path = ( new Attribute_Path( 'user.address.street' ) )->drop( 0 );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address.street' );

        $attribute_path = ( new Attribute_Path( 'user.address.street' ) )->drop( 2 );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user' );

        $attribute_path = ( new Attribute_Path( 'user.address.street' ) )->drop( 3 );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( '' );

        $attribute_path = ( new Attribute_Path( 'user.address.street' ) )->drop( 4 );

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( '' );

    });

    $this->it( "raises an error if n is negative", function() {

        $this->expect( function() {

            ( new Attribute_Path( 'user.address.street' ) )->drop( -1 );

        }) ->to() ->raise(
            'Haijin\Path_Error',
            function($error) {

                $this->expect( $error->getMessage() ) ->to()
                    ->equal( "Haijin\Attribute_Path->drop( -1 ): invalid parameter -1." );

        });

    });

    $this->it( "modifies the receiver instance", function() {

        $attribute_path = new Attribute_Path( 'user.address.street' );
        $attribute_path->drop();

        $this->expect( $attribute_path->to_string() ) ->to() ->equal( 'user.address' );

    });

    $this->it( "returns this instance", function() {

        $attribute_path = new Attribute_Path( 'user.address.street' );
        $dropped_path = $attribute_path->drop();

        $this->expect( $dropped_path ) ->to() ->be( "===" ) ->than( $attribute_path  );

    });

});
