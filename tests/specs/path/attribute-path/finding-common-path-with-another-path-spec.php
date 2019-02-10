<?php

use Haijin\Attribute_Path;

$spec->describe( "When finding the common root with another Attribute_Path", function() {

    $this->it( "returns an empty path if another path is empty", function() {

        $attribute_path = new Attribute_Path();

        $this->expect( $attribute_path->root_in_common_with(

            new Attribute_Path( 'address.street' ) )->to_string()

        ) ->to() ->equal( '' );


        $attribute_path = new Attribute_Path( 'address.street' );

        $this->expect( $attribute_path->root_in_common_with(

            new Attribute_Path() )->to_string()

        ) ->to() ->equal( '' );

    });

    $this->it( "returns an empty path if there is not root in common", function() {

        $attribute_path = new Attribute_Path( 'street' );

        $this->expect( $attribute_path->root_in_common_with(

            new Attribute_Path( 'address.street' ) )->to_string()

        ) ->to() ->equal( '' );


        $attribute_path = new Attribute_Path( 'address.street' );

        $this->expect( $attribute_path->root_in_common_with(

            new Attribute_Path( 'street' ) )->to_string()

        ) ->to() ->equal( '' );

    });

    $this->it( "returns the common path empty path if there is a common root", function() {

        $attribute_path = new Attribute_Path( 'user.address.street' );

        $this->expect( $attribute_path->root_in_common_with(

            new Attribute_Path( 'user.address' ) )->to_string()

        ) ->to() ->equal( 'user.address' );


        $attribute_path = new Attribute_Path( 'user.address' );

        $this->expect( $attribute_path->root_in_common_with(

            new Attribute_Path( 'user.address.street' ) )->to_string()

        ) ->to() ->equal( 'user.address' );

    });

});