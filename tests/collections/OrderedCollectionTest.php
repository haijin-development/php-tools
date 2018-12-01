<?php

use PHPUnit\Framework\TestCase;

use Haijin\Tools\OrderedCollection;

class OrderedCollectionTest extends TestCase
{
    use \Haijin\Testing\AllExpectationsTrait;

    /// Test creating collections

    public function testWith()
    {
        $collection = OrderedCollection::with( 1 );

        $this->assertEquals( [ 1 ], $collection->to_array() );
    }

    public function testWithAllFromArray()
    {
        $collection = OrderedCollection::with_all( [ 1, 2 ] );
        $this->assertEquals( [ 1, 2 ], $collection->to_array() );
    }

    public function testWithAllFromOrderedCollection()
    {
        $collection = OrderedCollection::with_all( OrderedCollection::with_all( [ 1, 2 ] ) );
        $this->assertEquals( [ 1, 2 ], $collection->to_array() );
    }

    public function testEmpty()
    {
        $collection = new OrderedCollection();
        $this->assertEquals( true, $collection->is_empty() );

        $collection = OrderedCollection::with( 1 );
        $this->assertEquals( false, $collection->is_empty() );
    }

    public function testNotEmpty()
    {
        $collection = OrderedCollection::with( 1 );
        $this->assertEquals( true, $collection->not_empty() );

        $collection = new OrderedCollection();
        $this->assertEquals( false, $collection->not_empty() );
    }

    /// Test adding items

    public function testAdd()
    {
        $collection = new OrderedCollection();
        $collection->add( 1 );
        $this->assertEquals( [ 1 ], $collection->to_array() );

        $collection = new OrderedCollection();
        $collection[] = 1;
        $this->assertEquals( [ 1 ], $collection->to_array() );
    }

    public function testAddAll()
    {
        $collection = new OrderedCollection();
        $collection->add_all( [ 1, 2 ] );

        $this->assertEquals( [ 1, 2 ], $collection->to_array() );

        $another_collection = new OrderedCollection();
        $another_collection->add_all( [ 1, 2] );

        $collection = new OrderedCollection();
        $collection->add_all( $another_collection );

        $this->assertEquals( [ 1, 2 ], $collection->to_array() );
    }

    public function testAddAt()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $collection->add_at( 'a', 1 );

        $this->assertEquals( [ 1, 'a', 2, 3 ], $collection->to_array() );
    }

    /// Test accessing items in the collection

    public function testAt()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $this->assertEquals( 1, $collection->at( 0 ) );
        $this->assertEquals( 2, $collection->at( 1 ) );
        $this->assertEquals( 3, $collection->at( 2 ) );

        $this->assertEquals( 1, $collection[ 0 ] );
        $this->assertEquals( 2, $collection[ 1 ] );
        $this->assertEquals( 3, $collection[ 2 ] );
    }

    public function testAtWithNegativeIndex()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $this->assertEquals( 3, $collection->at( -1 ) );
        $this->assertEquals( 2, $collection->at( -2 ) );
        $this->assertEquals( 1, $collection->at( -3 ) );


        $this->assertEquals( 3, $collection[ -1 ] );
        $this->assertEquals( 2, $collection[ -2 ] );
        $this->assertEquals( 1, $collection[ -3 ] );
    }

    public function testAtInvalidIndex()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\OutOfRangeError',
            function() use($collection){
                $collection->at( 3 );
            },
            function($error) use($collection) {
                $this->assertEquals( "The index 3 is out of range.", $error->getMessage() );
                $this->assertSame( $collection, $error->get_collection() );
                $this->assertEquals( 3, $error->get_index() );
            }
        );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\OutOfRangeError',
            function() use($collection){
                $collection[ 3 ];
            },
            function($error) use($collection) {
                $this->assertEquals( "The index 3 is out of range.", $error->getMessage() );
                $this->assertSame( $collection, $error->get_collection() );
                $this->assertEquals( 3, $error->get_index() );
            }
        );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\OutOfRangeError',
            function() use($collection){
                $collection->at( -4 );
            },
            function($error) use($collection) {
                $this->assertEquals( "The index -4 is out of range.", $error->getMessage() );
                $this->assertSame( $collection, $error->get_collection() );
                $this->assertEquals( -4, $error->get_index() );
            }
        );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\OutOfRangeError',
            function() use($collection){
                $collection[ -4 ];
            },
            function($error) use($collection) {
                $this->assertEquals( "The index -4 is out of range.", $error->getMessage() );
                $this->assertSame( $collection, $error->get_collection() );
                $this->assertEquals( -4, $error->get_index() );
            }
        );
    }

    public function testAtIfAbsent()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $item = $collection->at_if_absent( 2, function() { return 'not found'; });
        $this->assertEquals( 3, $item );

        $item = $collection->at_if_absent( 3, function() { return 'not found'; });
        $this->assertEquals( 'not found', $item );

        $item = $collection->at_if_absent( 3, 'not found' );
        $this->assertEquals( 'not found', $item );
    }

    public function testAtIfAbsentWithNegativeIndex()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $item = $collection->at_if_absent( -1, function() { return 'not found'; });
        $this->assertEquals( 3, $item );

        $item = $collection->at_if_absent( -4, function() { return 'not found'; });
        $this->assertEquals( 'not found', $item );
    }

    public function testAtPut()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $collection->at_put( 1, 'a' );
        $this->assertEquals( [ 1, 'a', 3 ], $collection->to_array() );


        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $collection[ 1 ] = 'a';
        $this->assertEquals( [ 1, 'a', 3 ], $collection->to_array() );
    }

    public function testAtPutWithNegativeIndex()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $collection->at_put( -1, 'a' );
        $this->assertEquals( [ 1, 2, 'a' ], $collection->to_array() );


        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $collection[ -1 ] = 'a';
        $this->assertEquals( [ 1, 2, 'a' ], $collection->to_array() );
    }

    public function testAtPutWithEndingIndex()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $collection->at_put( 3, 'a' );
        $this->assertEquals( [ 1, 2, 3, 'a' ], $collection->to_array() );


        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $collection[ 3 ] = 'a';
        $this->assertEquals( [ 1, 2, 3, 'a' ], $collection->to_array() );
    }

    public function testAtPutInvalidIndex()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\OutOfRangeError',
            function() use($collection){
                $collection->at_put( 4, 'a' );
            },
            function($error) use($collection) {
                $this->assertEquals( "The index 4 is out of range.", $error->getMessage() );
                $this->assertSame( $collection, $error->get_collection() );
                $this->assertEquals( 4, $error->get_index() );
            }
        );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\OutOfRangeError',
            function() use($collection){
                $collection[ 4 ] = 'a';
            },
            function($error) use($collection) {
                $this->assertEquals( "The index 4 is out of range.", $error->getMessage() );
                $this->assertSame( $collection, $error->get_collection() );
                $this->assertEquals( 4, $error->get_index() );
            }
        );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\OutOfRangeError',
            function() use($collection){
                $collection->at_put( -4, 'a' );
            },
            function($error) use($collection) {
                $this->assertEquals( "The index -4 is out of range.", $error->getMessage() );
                $this->assertSame( $collection, $error->get_collection() );
                $this->assertEquals( -4, $error->get_index() );
            }
        );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\OutOfRangeError',
            function() use($collection){
                $collection[ -4 ] = 'a';
            },
            function($error) use($collection) {
                $this->assertEquals( "The index -4 is out of range.", $error->getMessage() );
                $this->assertSame( $collection, $error->get_collection() );
                $this->assertEquals( -4, $error->get_index() );
            }
        );
    }

    /// Test removing items

    public function testRemoveLast()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $collection->remove_last();

        $this->assertEquals( [ 1, 2 ], $collection->to_array() );
    }

    public function testRemoveAt()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $item = $collection->remove_at( 0 );
        $this->assertEquals( 1, $item );
        $this->assertEquals( [ 2, 3 ], $collection->to_array() );


        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $item = $collection->remove_at( 1 );
        $this->assertEquals( 2, $item );
        $this->assertEquals( [ 1, 3 ], $collection->to_array() );

        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $item = $collection->remove_at( 2 );
        $this->assertEquals( 3, $item );
        $this->assertEquals( [ 1, 2 ], $collection->to_array() );


        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        unset( $collection[ 0 ] );
        $this->assertEquals( [ 2, 3 ], $collection->to_array() );
    }

    public function testRemoveAtWithNegativeIndex()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $collection->remove_at( -1 );
        $this->assertEquals( [ 1, 2 ], $collection->to_array() );

        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $collection->remove_at( -2 );
        $this->assertEquals( [ 1, 3 ], $collection->to_array() );

        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );
        $collection->remove_at( -3 );
        $this->assertEquals( [ 2, 3 ], $collection->to_array() );
    }

    public function testRemoveAtInvalidIndex()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\OutOfRangeError',
            function() use($collection){
                $collection->remove_at( 3 );
            },
            function($error) use($collection) {
                $this->assertEquals( "The index 3 is out of range.", $error->getMessage() );
                $this->assertSame( $collection, $error->get_collection() );
                $this->assertEquals( 3, $error->get_index() );
            }
        );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\OutOfRangeError',
            function() use($collection){
                $collection->remove_at( -4 );
            },
            function($error) use($collection) {
                $this->assertEquals( "The index -4 is out of range.", $error->getMessage() );
                $this->assertSame( $collection, $error->get_collection() );
                $this->assertEquals( -4, $error->get_index() );
            }
        );
    }

    public function testRemoveAtIfAbsent()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $item = $collection->remove_at_if_absent( 0, function() {
            return "absent index";
        });

        $this->assertEquals( 1, $item );
        $this->assertEquals( [ 2, 3 ], $collection->to_array() );
    }

    public function testRemoveAtIfAbsentWithInvalidIndex()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $item = $collection->remove_at_if_absent( 3, function() {
            return "absent index";
        });

        $this->assertEquals( "absent index", $item );
        $this->assertEquals( [ 1, 2, 3 ], $collection->to_array() );

        $item = $collection->remove_at_if_absent( 3, "absent index" );

        $this->assertEquals( "absent index", $item );
        $this->assertEquals( [ 1, 2, 3 ], $collection->to_array() );
    }

    /// Test iterating

    public function testFindFirst()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $item = $collection->find_first( function($each) {
            return $each % 2 == 1;
        });

        $this->assertEquals( 1, $item );
    }

    public function testFindFirstIndex()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $item = $collection->find_first_index( function($each) {
            return $each % 2 == 1;
        });

        $this->assertEquals( 0, $item );
    }

    public function testFindLast()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $item = $collection->find_last( function($each) {
            return $each % 2 == 1;
        });

        $this->assertEquals( 3, $item );
    }

    public function testFindLastIndex()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $item = $collection->find_last_index( function($each) {
            return $each % 2 == 1;
        });

        $this->assertEquals( 2, $item );
    }

    public function testEachDo()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $this->items = [];

        $collection->each_do( function($each) {
            $this->items[] = $each;
        }, $this);

        $this->assertEquals( [ 1, 2, 3 ], $this->items );
    }

    public function testEachWithIndexDo()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $this->items = [];
        $this->indices = [];

        $collection->each_with_index_do( function($each, $i) {
            $this->items[] = $each;
            $this->indices[] = $i;
        }, $this);

        $this->assertEquals( [ 1, 2, 3 ], $this->items );
        $this->assertEquals( [ 0, 1, 2 ], $this->indices );
    }

    public function testReverseDo()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $this->items = [];

        $collection->reverse_do( function($each) {
            $this->items[] = $each;
        }, $this);

        $this->assertEquals( [ 3, 2, 1 ], $this->items );
    }

    public function testSelect()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $selected_values = $collection->select( function($each) {
                return $each % 2 == 1;
            });

        $this->assertEquals( [ 1, 3 ], $selected_values->to_array() );
    }

    public function testCollect()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $collected_values = $collection->collect( function($each) {
                return $each * 2;
            });

        $this->assertEquals( [ 2, 4, 6 ], $collected_values->to_array() );
    }

    public function testAccumulate()
    {
        $collection = OrderedCollection::with_all( [ 1, 2, 3 ] );

        $sum = $collection->acummulate( 10, function($sum, $each) {
                return $sum = $sum + $each;
            });

        $this->assertEquals( 16 , $sum );
    }

    /// Test querying the collection

    public function testIncludes()
    {
        $collection = OrderedCollection::with_all( [ "a", "b", "c" ] );

        $this->assertEquals( true , $collection->includes( "a" ) );
        $this->assertEquals( false , $collection->includes( "d" ) );
    }

    public function testIncludesNot()
    {
        $collection = OrderedCollection::with_all( [ "a", "b", "c" ] );

        $this->assertEquals( true , $collection->includes_not( "d" ) );
        $this->assertEquals( false , $collection->includes_not( "a" ) );
    }

    /// Test joining

    public function testJoinWith()
    {
        $collection = OrderedCollection::with_all( [ "a", "b", "c" ] );

        $this->assertEquals( "a.b.c" , $collection->join_with( '.' ) );
    }
}