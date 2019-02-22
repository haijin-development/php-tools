<?php

use Haijin\Attribute_Path;

$spec->describe( "When finding the difference with another Attribute_Path", function() {

    $this->it( "returns an empty path if this path is empty", function() {

        $attribute_path = new Attribute_Path();
        $another_path = new Attribute_Path( 'address.street' );

        $this->expect( $attribute_path->difference_with( $another_path )->to_string() )
            ->to() ->equal( '' );

    });

    $this->it( "returns this path if the other path is empty", function() {

        $attribute_path = new Attribute_Path( 'address.street' );
        $another_path = new Attribute_Path();

        $this->expect( $attribute_path->difference_with( $another_path )->to_string() )
            ->to() ->equal( 'address.street' );

    });

    $this->it( "returns this path if there is not path in common", function() {

        $attribute_path = new Attribute_Path( 'address.street' );
        $another_path = new Attribute_Path( 'street' );

        $this->expect( $attribute_path->difference_with( $another_path )->to_string() )
            ->to() ->equal( 'address.street' );

    });

    $this->it( "returns the difference if there is a path in common", function() {

        $attribute_path = new Attribute_Path( 'address.street' );
        $another_path = new Attribute_Path( 'address' );

        $this->expect( $attribute_path->difference_with( $another_path )->to_string() )
            ->to() ->equal( 'street' );

    });

    $this->it( "returns an empty path if another path contains this path", function() {

        $attribute_path = new Attribute_Path( 'address' );
        $another_path = new Attribute_Path( 'address.street' );

        $this->expect( $attribute_path->difference_with( $another_path )->to_string() )
            ->to() ->equal( '' );

    });

});