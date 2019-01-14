<?php

use Haijin\Tools\AttributePath;

$spec->describe( "When accessing an AttributePath", function() {

    $this->describe( "on associative arrays", function() {

        $this->it( "reads an attribute value from an associative array", function() {

            $object = [
                'name' => 'Lisa',
                'last_name' => 'Simpson',
                'address' => [
                    'street' => 'Evergreen 742'
                ]
            ];

            $attribute_path = new AttributePath('address.street');

            $this->expect( $attribute_path->get_value_from( $object ) ) ->to()
                ->equal( 'Evergreen 742' );

        });

        $this->it( "raises an error when reading an inexistent attribute value from an associative array", function() {

            $object = [
                'name' => 'Lisa',
                'last_name' => 'Simpson',
                'address' => [
                    'street' => 'Evergreen 742'
                ]
            ];

            $attribute_path = new AttributePath( 'address.number' );

            $this->expect( function() use ($object, $attribute_path) {

                $attribute_path->get_value_from( $object );

            }) ->to() ->raise(
                'Haijin\Tools\MissingAttributeError',
                function($exception) use ($object, $attribute_path) {

                    $this->expect( $exception->getMessage() ) ->to()
                        ->equal( "The nested attribute \"address.number\" was not found." );

                    $this->expect( $exception->get_object() ) ->to() ->be( "===" )
                        ->than( $object );

                    $this->expect( $exception->get_full_attribute_path() ) ->to()
                        ->equal( $attribute_path );

                    $this->expect( $exception->get_missing_attribute_path()->to_string() ) ->to()
                        ->equal( 'address.number' );

            });

        });

        $this->it( "writes an attribute value to an associative array", function() {

            $object = [
                'name' => 'Lisa',
                'last_name' => 'Simpson',
                'address' => [
                    'street' => 'Evergreen 742'
                ]
            ];

            $attribute_path = new AttributePath( 'address.street' );

            $attribute_path->set_value_to( $object, 123 );

            $this->expect( $object['address']['street'] ) ->to() ->equal( 123 );

        });

        $this->it( "raises an error when writting an inexistent attribute to an associative array", function() {

            $object = [
                'name' => 'Lisa',
                'last_name' => 'Simpson',
                'address' => [
                    'street' => 'Evergreen 742'
                ]
            ];

            $attribute_path = new AttributePath( 'address.number' );

            $this->expect( function() use ($object, $attribute_path) {

                $attribute_path->set_value_to( $object, 123 );

            }) ->to() ->raise(
                'Haijin\Tools\MissingAttributeError',
                function($exception) use ($object, $attribute_path) {

                    $this->expect( $exception->getMessage() ) ->to()
                        ->equal( "The nested attribute \"address.number\" was not found." );

                    $this->expect( $exception->get_object() ) ->to() ->be( "===" )
                        ->than( $object );

                    $this->expect( $exception->get_full_attribute_path() ) ->to()
                        ->equal( $attribute_path );

                    $this->expect( $exception->get_missing_attribute_path()->to_string() ) ->to()
                        ->equal( 'address.number' );

            });

        });

    });

    $this->describe( "on indexed arrays", function() {

        $this->it( "reads an attribute value from an indexed array", function() {

            $object = [
                [ 'Lisa', 'Simpson' ],
                [ 'Evergreen', '742' ]
            ];

            $attribute_path = new AttributePath( '[1].[0]' );

            $this->expect( $attribute_path->get_value_from( $object ) ) ->to()
                ->equal( 'Evergreen' );

        });

        $this->it( "raises an error when reading an inexistent attribute value from an indexed array", function() {

            $object = [
                [ 'Lisa', 'Simpson' ],
                [ 'Evergreen', '742' ]
            ];

            $attribute_path = new AttributePath( '[1].[2]' );

            $this->expect( function() use ($object, $attribute_path) {

                $attribute_path->get_value_from( $object );

            }) ->to() ->raise(
                'Haijin\Tools\MissingAttributeError',
                function($exception) use ($object, $attribute_path) {
                    $this->expect( $exception->getMessage() ) ->to()
                        ->equal( "The nested attribute \"[1].[2]\" was not found." );

                    $this->expect( $exception->get_object() ) ->to() ->be( "===" )
                        ->than( $object );

                    $this->expect( $exception->get_full_attribute_path() ) ->to()
                        ->equal( $attribute_path );

                    $this->expect( $exception->get_missing_attribute_path()->to_string() ) ->to()
                        ->equal( '[1].[2]' );

            });

        });

        $this->it( "writes an attribute value to an indexed array", function() {

            $object = [
                [ 'Lisa', 'Simpson' ],
                [ 'Evergreen', '742' ]
            ];

            $attribute_path = new AttributePath( '[1].[0]' );

            $attribute_path->set_value_to( $object, 123 );

            $this->expect( $object[1][0] ) ->to() ->equal( 123 );

        });

        $this->it( "raises an error when writting an inexistent attribute value to an indexed array", function() {

            $object = [
                [ 'Lisa', 'Simpson' ],
                [ 'Evergreen', '742' ]
            ];

            $attribute_path = new AttributePath( '[1].[2]' );

            $this->expect( function() use ($object, $attribute_path) {

                $attribute_path->set_value_to( $object, 123 );

            }) ->to() ->raise(
                'Haijin\Tools\MissingAttributeError',
                function($exception) use ($object, $attribute_path) {

                    $this->expect( $exception->getMessage() ) ->to()
                        ->equal( "The nested attribute \"[1].[2]\" was not found." );

                    $this->expect( $exception->get_object() ) ->to() ->be( "===" )
                        ->than( $object );

                    $this->expect( $exception->get_full_attribute_path() ) ->to()
                        ->equal( $attribute_path );

                    $this->expect( $exception->get_missing_attribute_path()->to_string() ) ->to()
                        ->equal( '[1].[2]' );

            });

        });

    });

    $this->describe( "on objects", function() {

        $this->it( "reads an attribute value from an object", function() {

            $object = new stdclass();
            $object->field = new stdclass();
            $object->field->field = 123;

            $attribute_path = new AttributePath( 'field.field' );

            $this->expect( $attribute_path->get_value_from( $object ) ) ->to()
                ->equal( 123 );

        });

        $this->it( "raises an error when reading an inexistent property from an object", function() {

            $object = new stdclass();
            $object->field = new stdclass();
            $object->field->field = 123;


            $attribute_path = new AttributePath( 'field.field_2' );

            $this->expect( function() use ($object, $attribute_path) {

                $attribute_path->get_value_from( $object );

            }) ->to() ->raise(
                'Haijin\Tools\MissingAttributeError',
                function($exception) use ($object, $attribute_path) {

                    $this->expect( $exception->getMessage() ) ->to()
                        ->equal( "The nested attribute \"field.field_2\" was not found." );

                    $this->expect( $exception->get_object() ) ->to() ->be( "===" )
                        ->than( $object );

                    $this->expect( $exception->get_full_attribute_path() ) ->to()
                        ->equal( $attribute_path );

                    $this->expect( $exception->get_missing_attribute_path()->to_string() ) ->to()
                        ->equal( 'field.field_2' );

            });

        });

        $this->it( "testWritesAnAttributeValueToAnObject", function() {

            $object = new stdclass();
            $object->field = new stdclass();
            $object->field->field = 123;

            $attribute_path = new AttributePath( 'field.field' );
            $attribute_path->set_value_to( $object, 111 );

            $this->expect( $object->field->field ) ->to() ->equal( 111 );

        });

        $this->it( "testRaisesAnErrorWhenWrittingAnInexistentPropertyToAnObject", function() {

            $object = new stdclass();
            $object->field = new stdclass();
            $object->field->field = 123;


            $attribute_path = new AttributePath( 'field.field_2' );

            $this->expect( function() use ($object, $attribute_path) {

                $attribute_path->set_value_to( $object, 111 );

            }) ->to() ->raise(
                'Haijin\Tools\MissingAttributeError',
                function($exception) use ($object, $attribute_path) {

                    $this->expect( $exception->getMessage() ) ->to()
                        ->equal( "The nested attribute \"field.field_2\" was not found." );

                    $this->expect( $exception->get_object() ) ->to() ->be( "===" )
                        ->than( $object );

                    $this->expect( $exception->get_full_attribute_path() ) ->to()
                        ->equal( $attribute_path );

                    $this->expect( $exception->get_missing_attribute_path()->to_string() ) ->to()
                        ->equal( 'field.field_2' );

            });

        });

    });

});
