<?php

use Haijin\Tools\Dictionary;


$spec->describe( "A Dictionary", function() {

    $this->describe( "when creating instances", function() {

        $this->it( "creates an instance with an association", function() {

            $dictionary = Dictionary::with( 'a', 1 );

            $this->expect( $dictionary->to_array() ) ->to() ->equal( [ 'a' => 1 ] );

        });

        $this->it( "creates an instance with many associations", function() {

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2 ] );

            $this->expect( $dictionary->to_array() ) ->to() ->equal( [ 'a' => 1, 'b' => 2 ] );

        });

        $this->it( "creates an instance from another instance", function() {

            $dictionary = Dictionary::with_all( Dictionary::with_all( [ 'a' => 1, 'b' => 2 ] ) );

            $this->expect( $dictionary->to_array() ) ->to() ->equal( [ 'a' => 1, 'b' => 2 ] );

        });

    });

    $this->describe( "when querying for emptyness", function() {

        $this->it( "returns true if empty, false otherwise", function() {

            $dictionary = new Dictionary();
            $this->expect( $dictionary->is_empty() ) ->to() ->be() ->true();

            $dictionary = Dictionary::with( 'a', 1 );
            $this->expect( $dictionary->is_empty() ) ->to() ->be() ->false();

        });

        $this->it( "returns true if not empty, false otherwise", function() {

            $dictionary = Dictionary::with( 'a', 1 );
            $this->expect( $dictionary->not_empty() ) ->to() ->be() ->true();

            $dictionary = new Dictionary();
            $this->expect( $dictionary->not_empty() ) ->to() ->be() ->false();

        });

    });

    $this->describe( "when accessing items", function() {

        $this->it( "returns all the keys", function() {

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

            $this->expect( $dictionary->get_keys() ) ->to() ->equal( [ 'a', 'b', 'c' ] );

        });

        $this->it( "returns all the values", function() {

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

            $this->expect( $dictionary->get_values() ) ->to() ->equal( [ 1, 2, 3 ] );

        });

        $this->it( "return the value at a key", function() {

            $dictionary = Dictionary::with( 'a', 1 );

            $this->expect( $dictionary->at( 'a' ) ) ->to() ->equal( 1 );
            $this->expect( $dictionary[ 'a' ] ) ->to() ->equal( 1 );

        });

        $this->it( "raises a MissingKeyError if the key is missing", function() {

            $dictionary = Dictionary::with( 'a', 1 );

            $this->expect( function() use($dictionary) {

                $dictionary->at( 'b' );

            }) ->to() ->raise(
                "Haijin\Tools\MissingKeyError",
                function($error) use($dictionary) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The key 'b' is not defined." );

                    $this->expect( $error->get_dictionary() ) ->to() ->be( "===" )
                        ->than( $dictionary );

                    $this->expect( $error->get_key() ) ->to() ->equal( 'b' );
                }
            );

            $this->expect( function() use($dictionary) {

                $dictionary[ 'b' ];

            }) ->to() ->raise(
                "Haijin\Tools\MissingKeyError",
                function($error) use($dictionary) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The key 'b' is not defined." );

                    $this->expect( $error->get_dictionary() ) ->to() ->be( "===" )
                        ->than($dictionary);

                    $this->expect( $error->get_key() ) ->to() ->equal( 'b' );
                }
            );

        });

        $this->it( "evaluates a closure if the key is missing, otherwise returns the value at the key", function() {

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

            $value = $dictionary->at_if_absent( 'c', function() { return 'not found'; });
            $this->expect( $value ) ->to() ->equal( 3 );

            $value = $dictionary->at_if_absent( 'd', function() { return 'not found'; });
            $this->expect( $value ) ->to() ->equal( 'not found' );

            $value = $dictionary->at_if_absent( 'd', 'not found' );
            $this->expect( $value ) ->to() ->equal( 'not found' );

        });

    });

    $this->describe( "when putting items", function() {

        $this->it( "puts a value at a key", function() {

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );
            $dictionary->at_put( 'b', 123 );

            $this->expect( $dictionary->to_array() ) ->to()
                ->equal( [ 'a' => 1, 'b' => 123, 'c' => 3 ] );

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );
            $dictionary[ 'b' ] = 123;

            $this->expect( $dictionary->to_array() ) ->to()
                ->equal( [ 'a' => 1, 'b' => 123, 'c' => 3 ] );

        });

    });

    $this->describe( "when removing associations", function() {

        $this->it( "removes the value at a key", function() {

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

            $value = $dictionary->remove_at( 'b' );

            $this->expect( $value ) ->to() ->equal( 2 );
            $this->expect( $dictionary->to_array() ) ->to() ->equal( [ 'a' => 1, 'c' => 3 ] );


            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );
            unset( $dictionary[ 'b' ] );

            $this->expect( $dictionary->to_array() ) ->to() ->equal( [ 'a' => 1, 'c' => 3 ] );

        });

        $this->it( "raises a MissingKeyError if the key to remove is missing", function() {

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

            $this->expect( function() use($dictionary) {

                $dictionary->remove_at( 'd' );

            }) ->to() ->raise(
                'Haijin\Tools\MissingKeyError',
                function($error) use($dictionary) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The key 'd' is not defined." );

                    $this->expect( $error->get_dictionary() ) ->to() ->be( "===" )
                        ->than( $dictionary );

                    $this->expect( $error->get_key() ) ->to()
                        ->equal( 'd' );
            });

        });

        $this->it( "removes the value at the key", function() {

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

            $value = $dictionary->remove_at_if_absent( 'a', function() {
                return "default value";
            });

            $this->expect( $value ) ->to() ->equal( 1 );
            $this->expect( $dictionary->to_array() ) ->to() ->equal( [ 'b' => 2, 'c' => 3 ] );

        });

        $this->it( "evaluates a closure if the key to remove is missing", function() {

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

            $value = $dictionary->remove_at_if_absent( 'd', function() {
                return "default value";
            });

            $this->expect( $value ) ->to() ->equal( "default value" );
            $this->expect( $dictionary->to_array() ) ->to()
                ->equal( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

            $value = $dictionary->remove_at_if_absent( 'd', "default value" );

            $this->expect( $value ) ->to() ->equal( "default value" );
            $this->expect( $dictionary->to_array() ) ->to()
                ->equal( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

        });

    });

    $this->describe( "when iterating", function() {

        $this->it( "iterates over all its associations of keys and values", function() {

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

            $this->keys = [];
            $this->values = [];

            $dictionary->keys_and_values_do( function($key, $value) {
                $this->keys[] = $key;
                $this->values[] = $value;
            }, $this);

            $this->expect( $this->keys ) ->to() ->equal( [ 'a', 'b', 'c' ] );
            $this->expect( $this->values ) ->to() ->equal( [ 1, 2, 3 ] );

        });

        $this->it( "iterates over all its keys", function() {

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

            $this->keys = [];

            $dictionary->keys_do( function($key) {
                $this->keys[] = $key;
            }, $this);

            $this->expect( $this->keys ) ->to() ->equal( [ 'a', 'b', 'c' ] );

        });

        $this->it( "iterates over all its values", function() {

            $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

            $this->values = [];

            $dictionary->values_do( function($value) {
                $this->values[] = $value;
            }, $this);

            $this->expect( $this->values ) ->to() ->equal( [ 1, 2, 3 ] );

        });

    });

    $this->describe( "when cloning the instance", function() {

        $this->it( "creates a copy of the dictionary", function() {

            $dictionary_1 = Dictionary::with( "a", 1 );

            $dictionary_2 = clone $dictionary_1;

            $dictionary_1[ "b" ] = 2;

            $this->expect( $dictionary_1->size() ) ->to() ->equal( 2 );
            $this->expect( $dictionary_2->size() ) ->to() ->equal( 1 );

        });

    });

});