<?php

use Haijin\Ordered_Collection;

$spec->describe( "An Ordered_Collection", function() {

    $this->describe( "when creating instances", function() {

        $this->it( "creates an instance with an item", function() {

            $collection = Ordered_Collection::with( 1 );

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1 ] );

        });

        $this->it( "creates an instance with many items", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2 ] );

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2 ] );

        });

        $this->it( "creates an instance from another instance", function() {

            $collection = Ordered_Collection::with_all( Ordered_Collection::with_all( [ 1, 2 ] ) );

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2 ] );

        });

    });


    $this->describe( "when asking if it is empty", function() {

        $this->it( "returns if empty or not", function() {

            $collection = new Ordered_Collection();

            $this->expect( $collection->is_empty() ) ->to() ->be() ->true();

            $collection = Ordered_Collection::with( 1 );

            $this->expect( $collection->is_empty() ) ->to() ->be() ->false();

        });

        $this->it( "returns if not empty or empty", function() {

            $collection = Ordered_Collection::with( 1 );

            $this->expect( $collection->not_empty() )  ->to() ->be() ->true();

            $collection = new Ordered_Collection();

            $this->expect( $collection->not_empty() ) ->to() ->be() ->false();

        });

    });

    $this->describe( "when adding items", function() {

        $this->it( "adds an item at the end of the collection", function() {

            $collection = new Ordered_Collection();
            $collection->add( 1 );

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1 ] );

            $collection = new Ordered_Collection();
            $collection[] = 1;

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1 ] );

        });

        $this->it( "adds many items at the end of the collection", function() {

            $collection = new Ordered_Collection();
            $collection->add_all( [ 1, 2 ] );

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2 ] );

            $another_collection = new Ordered_Collection();
            $another_collection->add_all( [ 1, 2] );

            $collection = new Ordered_Collection();
            $collection->add_all( $another_collection );

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2 ] );

        });

        $this->it( "adds an item at an index", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $collection->add_at( 'a', 1 );

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 'a', 2, 3 ] );

        });

    });

    $this->describe( "when accessing items", function() {

        $this->it( "returns the first item", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $this->expect( $collection->first() ) ->to() ->equal( 1 );

        });

        $this->it( "returns the last item", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $this->expect( $collection->last() ) ->to() ->equal( 3 );

        });

        $this->it( "returns the item at an index", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $this->expect( $collection->at( 0 ) ) ->to() ->equal( 1 );
            $this->expect( $collection->at( 1 ) ) ->to() ->equal( 2 );
            $this->expect( $collection->at( 2 ) ) ->to() ->equal( 3 );

            $this->expect( $collection[ 0 ] ) ->to() ->equal( 1 );
            $this->expect( $collection[ 1 ] ) ->to() ->equal( 2 );
            $this->expect( $collection[ 2 ] ) ->to() ->equal( 3 );

        });

        $this->it( "returns the item at a negative index", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $this->expect( $collection->at( -1 ) ) ->to() ->equal( 3 );
            $this->expect( $collection->at( -2 ) ) ->to() ->equal( 2 );
            $this->expect( $collection->at( -3 ) ) ->to() ->equal( 1 );


            $this->expect( $collection[ -1 ] ) ->to() ->equal( 3 );
            $this->expect( $collection[ -2 ] ) ->to() ->equal( 2 );
            $this->expect( $collection[ -3 ] ) ->to() ->equal( 1 );

        });

        $this->it( "raises an Out_Of_Range_Error if the index is invalid", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $this->expect( function() use($collection) {

                $collection->at( 3 );

            }) ->to() ->raise(
                'Haijin\Out_Of_Range_Error',
                function($error) use($collection) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The index 3 is out of range." );

                    $this->expect( $error->get_collection() ) ->to() ->be( "===" )
                        ->than( $collection );

                    $this->expect( $error->get_index() ) ->to()
                        ->equal( 3 );
            });

            $this->expect( function() use($collection){

                $collection[ 3 ];

            }) ->to() ->raise(
                'Haijin\Out_Of_Range_Error',
                function($error) use($collection) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The index 3 is out of range." );

                    $this->expect( $error->get_collection() ) ->to() ->be( "===")
                        ->than( $collection );

                    $this->expect( $error->get_index() ) ->to()
                        ->equal( 3 );

            });

            $this->expect( function() use($collection){

                $collection->at( -4 );

            }) ->to() ->raise(
                'Haijin\Out_Of_Range_Error',
                function($error) use($collection) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The index -4 is out of range." );

                    $this->expect( $error->get_collection() ) ->to() ->be( "===" )
                        ->than( $collection );

                    $this->expect( $error->get_index() ) ->to()
                        ->equal( -4 );

            });

            $this->expect( function() use($collection){

                    $collection[ -4 ];

            }) ->to() ->raise(
                'Haijin\Out_Of_Range_Error',
                function($error) use($collection) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The index -4 is out of range." );

                    $this->expect( $error->get_collection() ) ->to()
                        ->be( "===" ) ->than( $collection );

                    $this->expect( $error->get_index() ) ->to()
                        ->equal( -4 );
            });

        });

        $this->it( "returns the item at an index or evaluates a closure if the index is invalid", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $item = $collection->at_if_absent( 2, function() { return 'not found'; });
            $this->expect( $item ) ->to() ->equal( 3 );

            $item = $collection->at_if_absent( 3, function() { return 'not found'; });
            $this->expect( $item ) ->to() ->equal( 'not found' );

            $item = $collection->at_if_absent( 3, 'not found' );
            $this->expect( $item ) ->to() ->equal( 'not found' );

        });

        $this->it( "returns the item at an index or evaluates a closure if the negative index is invalid", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $item = $collection->at_if_absent( -1, function() { return 'not found'; });
            $this->expect( $item ) ->to() ->equal( 3 );

            $item = $collection->at_if_absent( -4, function() { return 'not found'; });
            $this->expect( $item ) ->to() ->equal( 'not found' );

        });

    });

    $this->describe( "when putting items", function() {

        $this->it( "puts an item at an index", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $collection->at_put( 1, 'a' );
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 'a', 3 ] );


            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $collection[ 1 ] = 'a';
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 'a', 3 ] );

        });

        $this->it( "puts an item at a negative index", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $collection->at_put( -1, 'a' );
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2, 'a' ] );


            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $collection[ -1 ] = 'a';
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2, 'a' ] );

        });

        $this->it( "appends an item if the put_at index is the size of the collection", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $collection->at_put( 3, 'a' );
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2, 3, 'a' ] );


            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $collection[ 3 ] = 'a';
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2, 3, 'a' ] );

        });

        $this->it( "raises an Out_Of_Range_Error if the index is invalid", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $this->expect( function() use($collection) {

                    $collection->at_put( 4, 'a' );

            }) ->to() ->raise(
                'Haijin\Out_Of_Range_Error',
                function($error) use($collection) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The index 4 is out of range." );

                    $this->expect( $error->get_collection() ) ->to() ->be( "===" )
                        ->than( $collection );

                    $this->expect( $error->get_index() ) ->to()
                        ->equal( 4 );

            });

            $this->expect( function() use($collection) {

                    $collection[ 4 ] = 'a';

            }) ->to() ->raise(
                'Haijin\Out_Of_Range_Error',
                function($error) use($collection) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The index 4 is out of range." );

                    $this->expect( $error->get_collection() ) ->to() ->be( "===" )
                        ->than( $collection );

                    $this->expect( $error->get_index() ) ->to()
                        ->equal( 4 );

            });

            $this->expect( function() use($collection) {

                    $collection->at_put( -4, 'a' );

            }) ->to() ->raise(
                'Haijin\Out_Of_Range_Error',
                function($error) use($collection) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The index -4 is out of range." );

                    $this->expect( $error->get_collection() ) ->to() ->be( "===" )
                        ->than( $collection );

                    $this->expect( $error->get_index() ) ->to()
                        ->equal( -4 );

            });

            $this->expect( function() use($collection){

                    $collection[ -4 ] = 'a';

            }) ->to() ->raise(
                'Haijin\Out_Of_Range_Error',
                function($error) use($collection) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The index -4 is out of range." );

                    $this->expect( $error->get_collection() ) ->to() ->be( "===" )
                        ->than( $collection );

                    $this->expect( $error->get_index() ) ->to()
                        ->equal( -4 );

            });

        });

    });

    $this->describe( "when removing items", function() {

        $this->it( "removes the first item in the collection", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $collection->remove_first();

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 2, 3 ] );

        });

        $this->it( "removes the last item in the collection", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $collection->remove_last();

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2 ] );

        });

        $this->it( "removes the item at a valid index", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $item = $collection->remove_at( 0 );

            $this->expect( $item ) ->to() ->equal( 1 );
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 2, 3 ] );


            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $item = $collection->remove_at( 1 );

            $this->expect( $item ) ->to() ->equal( 2 );
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 3 ] );

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $item = $collection->remove_at( 2 );

            $this->expect( $item ) ->to() ->equal( 3 );
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2 ] );


            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            unset( $collection[ 0 ] );
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 2, 3 ] );

        });

        $this->it( "removes the item at a valid negative index", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $collection->remove_at( -1 );

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2 ] );

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $collection->remove_at( -2 );

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 3 ] );

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
            $collection->remove_at( -3 );

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 2, 3 ] );

        });

        $this->it( "raises an Out_Of_Range_Error if the index to remove is invalid", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $this->expect( function() use($collection){

                    $collection->remove_at( 3 );

            }) ->to() ->raise(
                'Haijin\Out_Of_Range_Error',
                function($error) use($collection) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The index 3 is out of range." );

                    $this->expect( $error->get_collection() ) ->to() ->be( "===" )
                        ->than( $collection );

                    $this->expect( $error->get_index() ) ->to()
                        ->equal( 3 );

            });

            $this->expect( function() use($collection){

                    $collection->remove_at( -4 );

            }) ->to() ->raise(
                'Haijin\Out_Of_Range_Error',
                function($error) use($collection) {

                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "The index -4 is out of range." );

                    $this->expect( $error->get_collection() ) ->to() ->be( "===" )
                        ->than( $collection );

                    $this->expect( $error->get_index() ) ->to()
                        ->equal( -4 );
            });

        });

        $this->it( "removes the item at a valid index or evaluates the closure at an invalid index", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $item = $collection->remove_at_if_absent( 0, function() {
                return "absent index";
            });

            $this->expect( $item ) ->to() ->equal( 1 );
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 2, 3 ] );

        });

        $this->it( "removes the item at a valid index or evaluates the closure at an invalid index", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $item = $collection->remove_at_if_absent( 3, function() {
                return "absent index";
            });

            $this->expect( $item ) ->to() ->equal( "absent index" );
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2, 3 ] );

            $item = $collection->remove_at_if_absent( 3, "absent index" );

            $this->expect( $item ) ->to() ->equal( "absent index" );
            $this->expect( $collection->to_array() ) ->to() ->equal( [ 1, 2, 3 ] );

        });

        $this->it( "removes all the ocurrences of an item in the collection", function() {

            $collection = Ordered_Collection::with_all( [ 'a', 'b', 'a', 'c', 'a' ] );
            $collection->remove( 'a' );

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 'b', 'c' ] );

        });

        $this->it( "does not fail when removing an absent item from the collection", function() {

            $collection = Ordered_Collection::with_all( [ 'a', 'b', 'c' ] );
            $collection->remove( 'd' );

            $this->expect( $collection->to_array() ) ->to() ->equal( [ 'a', 'b', 'c' ] );

        });

    });

    $this->describe( "when searching for items", function() {

        $this->it( "finds the first matching item", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $item = $collection->find_first( function($each) {
                return $each % 2 == 1;
            });

            $this->expect( $item ) ->to() ->equal( 1 );

        });

        $this->it( "finds the index of the first matching item", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $item = $collection->find_first_index( function($each) {
                return $each % 2 == 1;
            });

            $this->expect( $item ) ->to() ->equal( 0 ) ;

        });

        $this->it( "finds the last matching item", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $item = $collection->find_last( function($each) {
                return $each % 2 == 1;
            });

            $this->expect( $item ) ->to() ->equal( 3 );

        });

        $this->it( "finds the index of the last matching item", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $item = $collection->find_last_index( function($each) {
                return $each % 2 == 1;
            });

            $this->expect( $item ) ->to() ->equal( 2 );

        });

    });

    $this->describe( "when iterating items", function() {

        $this->it( "iterates over all the items in the collection", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $this->items = [];

            $collection->each_do( function($each) {
                $this->items[] = $each;
            }, $this);

            $this->expect( $this->items ) ->to() ->equal( [ 1, 2, 3 ] );

        });

        $this->it( "iterates over all the indices and items in the collection", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $this->items = [];
            $this->indices = [];

            $collection->each_with_index_do( function($each, $i) {
                $this->items[] = $each;
                $this->indices[] = $i;
            }, $this);

            $this->expect( $this->items ) ->to() ->equal( [ 1, 2, 3 ] );
            $this->expect( $this->indices ) ->to() ->equal( [ 0, 1, 2 ] );

        });

        $this->it( "iterates all the items in the collection backwards", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $this->items = [];

            $collection->reverse_do( function($each) {
                $this->items[] = $each;
            }, $this);

            $this->expect( $this->items ) ->to() ->equal( [ 3, 2, 1 ] );

        });

        $this->it( "returns a new Ordered_Collection with only the items that matches a filter", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $selected_values = $collection->select( function($each) {
                    return $each % 2 == 1;
                });

            $this->expect( $selected_values->to_array() ) ->to() ->equal( [ 1, 3 ] );

        });

        $this->it( "returns a new Ordered_Collection applying a closure to each item in the collection", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $collected_values = $collection->collect( function($each) {
                    return $each * 2;
                });

            $this->expect( $collected_values->to_array() ) ->to() ->equal( [ 2, 4, 6 ] );

        });

        $this->it( "accumulates a value into a variable", function() {

            $collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );

            $sum = $collection->acummulate( 10, function($sum, $each) {
                    return $sum = $sum + $each;
                });

            $this->expect( $sum ) ->to() ->equal( 16 );

        });

    });

    $this->describe( "when querying for items", function() {

        $this->it( "returns true if includes an item using == as comparison", function() {

            $collection = Ordered_Collection::with_all( [ "a", "b", "c" ] );

            $this->expect( $collection->includes( "a" ) ) ->to() ->be() ->true();
            $this->expect( $collection->includes( "d" ) ) ->to() ->be() ->false();

        });

        $this->it( "returns true if does not include an item using == as comparison", function() {

            $collection = Ordered_Collection::with_all( [ "a", "b", "c" ] );

            $this->expect( $collection->includes_not( "d" ) ) ->be() ->true();
            $this->expect( $collection->includes_not( "a" ) ) ->be() ->false();

        });

    });

    $this->describe( "when joining string items", function() {

        $this->it( "joins the string items of a collection with a given separator string", function() {

            $collection = Ordered_Collection::with_all( [ "a", "b", "c" ] );

            $this->expect( $collection->join_with( '.' ) ) ->to() ->equal( "a.b.c" );

        });

    });

    $this->describe( "when cloning the instance", function() {

        $this->it( "creates a copy of the collection", function() {

            $collection_1 = Ordered_Collection::with( 1 );

            $collection_2 = clone $collection_1;

            $collection_1->add( 2 );

            $this->expect( $collection_1->size() ) ->to() ->equal( 2 );
            $this->expect( $collection_2->size() ) ->to() ->equal( 1 );

        });

    });

});