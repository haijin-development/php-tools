<?php

use Haijin\Tools\ObjectAttributeAccessor;

$spec->describe( "An ObjectAttributeAccessor", function() {

    $this->it( "gets an existing attribute", function() {

        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
            "address" => [
                "street" => "Evergreen 742"
            ]
        ];

        $accessor = new ObjectAttributeAccessor( $object );

        $this->expect( $accessor->get_value_at( "name" ) ) ->to() ->equal( "Lisa" );

        $this->expect( $accessor->get_value_at( "address.street" ) ) ->to()
            ->equal( "Evergreen 742" );

    });

    $this->it( "gets an existing attribute or evaluates an absent closure", function() {
        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
            "address" => [
                "street" => "Evergreen 742"
            ]
        ];

        $accessor = new ObjectAttributeAccessor( $object );

        $this->expect(

            $accessor->get_value_at_if_absent( "address.number",  function() {
                return "Absent value";
            })

        ) ->to() ->equal( "Absent value" );

        $this->expect(

            $accessor->get_value_at_if_absent( "address.number",  "Absent value" )

        ) ->to() ->equal( "Absent value" );

    });

    $this->it( "raises an error when trying to get an inexisting attribute", function() {

        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
            "address" => [
                "street" => "Evergreen 742"
            ]
        ];

        $accessor = new ObjectAttributeAccessor( $object );

        $this->expect( function() use($accessor) {

                $accessor->get_value_at( "address.number" );

        }) ->to() ->raise(
            "Haijin\Tools\MissingAttributeError",
            function($error) use($object) {

                $this->expect( $error->getMessage() ) ->to()
                    ->equal( 'The nested attribute "address.number" was not found.' );

                $this->expect( $error->get_full_attribute_path() ) ->to()
                    ->equal( "address.number" );

                $this->expect( $error->get_object() ) ->to() ->be( "===" )
                    ->than( $object );

        });

    });

    $this->it( "sets an existing attribute", function() {
        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
            "address" => [
                "street" => "Evergreen 742"
            ]
        ];

        $accessor = new ObjectAttributeAccessor( $object );
        $accessor->set_value_at( "address.street", "Evergreen" );

        $this->expect( $object["address"]["street"] ) ->to() ->equal( "Evergreen" );

    });

    $this->it( "raises an error when trying to set an inexisting attribute", function() {

        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
            "address" => [
                "street" => "Evergreen 742"
            ]
        ];

        $accessor = new ObjectAttributeAccessor( $object );

        $this->expect( function() use($accessor) {

                $accessor->set_value_at( "address.number", 742 );

        }) ->to() ->raise(
            "Haijin\Tools\MissingAttributeError",
            function($error) use($object) {

                $this->expect( $error->getMessage() ) ->to()
                    ->equal( 'The nested attribute "address.number" was not found.' );

                $this->expect( $error->get_full_attribute_path() ) ->to()
                    ->equal( "address.number" );

                $this->expect( $object, $error->get_object() ) ->to() ->be( "===" )
                    ->than( $object );

        });

    });

    $this->it( "creates and sets an inexisting attribute", function() {
        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
        ];

        $accessor = new ObjectAttributeAccessor( $object );
        $accessor->create_value_at( "addresses.[0].address.number", 742 );

        $this->expect( $object["addresses"][0]["address"]["number"] ) ->to() ->equal( 742 );

    });

    $this->it( "does not override existing attribute when creating inexisting attribute", function() {
        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
            "addresses" => [
                [
                    "address" => [
                        "street" => "Evergreen"
                    ]
                ]
            ]
        ];

        $accessor = new ObjectAttributeAccessor( $object );
        $accessor->create_value_at( "addresses.[0].address.number", 742 );

        $this->expect( $object["addresses"][0]["address"]["street"] ) ->to()
            ->equal( "Evergreen" );

        $this->expect( $object["addresses"][0]["address"]["number"] ) ->to()
            ->equal( 742 );

    });

});