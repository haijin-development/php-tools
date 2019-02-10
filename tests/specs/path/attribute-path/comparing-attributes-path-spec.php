<?php

use Haijin\Attribute_Path;

$spec->describe( "When comparing Attribute_Paths", function() {

    $this->describe( "by equality", function() {

        $this->it( "returns true if both are empty", function() {

            $attribute_path = new Attribute_Path();

            $this->expect( $attribute_path->equals( new Attribute_Path() ) )
                ->to() ->be() ->true();

        });

        $this->it( "returns true if both are equal", function() {

            $attribute_path = new Attribute_Path( 'address.street' );

            $this->expect( $attribute_path->equals( new Attribute_Path( 'address.street' ) ) )
                ->to() ->be() ->true();

        });

        $this->it( "returns false if are not equal", function() {

            $attribute_path = new Attribute_Path( 'address' );

            $this->expect( $attribute_path->equals( new Attribute_Path( 'address.street' ) ) )
                ->to() ->be() ->false();

        });

    });

    $this->describe( "by a path to begin with another path", function() {

        $this->it( "returns true if the path begins with another path", function() {

            $attribute_path = new Attribute_Path();

            $this->expect( $attribute_path->begins_with( new Attribute_Path() ) )
                ->to() ->be() ->true();

            $attribute_path = new Attribute_Path( 'address.street' );

            $this->expect( $attribute_path->begins_with( new Attribute_Path() ) )
                ->to() ->be() ->true();


            $attribute_path = new Attribute_Path( 'address.street' );

            $this->expect( $attribute_path->begins_with( new Attribute_Path( 'address' ) ) )
                ->to() ->be() ->true();

        });

        $this->it( "returns false if the path does not begin with another path", function() {

            $attribute_path = new Attribute_Path();

            $this->expect( $attribute_path->begins_with(

                new Attribute_Path( 'address.street' ) )

            ) ->to() ->be() ->false();


            $attribute_path = new Attribute_Path( 'address' );

            $this->expect( $attribute_path->begins_with(

                new Attribute_Path( 'street.address' ) )

            ) ->to() ->be() ->false();

        });

    });

});
