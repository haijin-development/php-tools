<?php

use PHPUnit\Framework\TestCase;

use Haijin\Tools\Dictionary;

use Haijin\Tools\OrderedCollection;

class DictionaryTest extends TestCase
{
    use \Haijin\Testing\AllExpectationsTrait;

    /// Test creating dictionaries

    public function testWith()
    {
        $dictionary = Dictionary::with( 'a', 1 );

        $this->assertEquals( [ 'a' => 1 ], $dictionary->to_array() );
    }

    public function testWithAllFromArray()
    {
        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2 ] );
        $this->assertEquals( [ 'a' => 1, 'b' => 2 ], $dictionary->to_array() );
    }

    public function testWithAllFromDictionary()
    {
        $dictionary = Dictionary::with_all( Dictionary::with_all( [ 'a' => 1, 'b' => 2 ] ) );
        $this->assertEquals( [ 'a' => 1, 'b' => 2 ], $dictionary->to_array() );
    }

    /// Test querying the dictionary

    public function testEmpty()
    {
        $dictionary = new Dictionary();
        $this->assertEquals( true, $dictionary->is_empty() );

        $dictionary = Dictionary::with( 'a', 1 );
        $this->assertEquals( false, $dictionary->is_empty() );
    }

    public function testNotEmpty()
    {
        $dictionary = Dictionary::with( 'a', 1 );
        $this->assertEquals( true, $dictionary->not_empty() );

        $dictionary = new Dictionary();
        $this->assertEquals( false, $dictionary->not_empty() );
    }

    /// Test accessing items in the collection

    public function testGetKeys()
    {
        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

        $this->assertEquals( [ 'a', 'b', 'c' ], $dictionary->get_keys() );
    }

    public function testGetValues()
    {
        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

        $this->assertEquals( [ 1, 2, 3 ], $dictionary->get_values() );
    }

    public function testAt()
    {
        $dictionary = Dictionary::with( 'a', 1 );

        $this->assertEquals( 1, $dictionary->at( 'a' ) );
        $this->assertEquals( 1, $dictionary[ 'a' ] );
    }

    public function testAtWithMissingKey()
    {
        $dictionary = Dictionary::with( 'a', 1 );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\MissingKeyError',
            function() use($dictionary){
                $dictionary->at( 'b' );
            },
            function($error) use($dictionary) {
                $this->assertEquals( "The key 'b' is not defined.", $error->getMessage() );
                $this->assertSame( $dictionary, $error->get_dictionary() );
                $this->assertEquals( 'b', $error->get_key() );
            }
        );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\MissingKeyError',
            function() use($dictionary){
                $dictionary[ 'b' ];
            },
            function($error) use($dictionary) {
                $this->assertEquals( "The key 'b' is not defined.", $error->getMessage() );
                $this->assertSame( $dictionary, $error->get_dictionary() );
                $this->assertEquals( 'b', $error->get_key() );
            }
        );
    }

    public function testAtIfAbsent()
    {
        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

        $value = $dictionary->at_if_absent( 'c', function() { return 'not found'; });
        $this->assertEquals( 3, $value );

        $value = $dictionary->at_if_absent( 'd', function() { return 'not found'; });
        $this->assertEquals( 'not found', $value );

        $value = $dictionary->at_if_absent( 'd', 'not found' );
        $this->assertEquals( 'not found', $value );
    }

    public function testAtPut()
    {
        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );
        $dictionary->at_put( 'b', 123 );
        $this->assertEquals( [ 'a' => 1, 'b' => 123, 'c' => 3 ], $dictionary->to_array() );

        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );
        $dictionary[ 'b' ] = 123;
        $this->assertEquals( [ 'a' => 1, 'b' => 123, 'c' => 3 ], $dictionary->to_array() );
    }


    /// Test removing associations

    public function testRemoveAt()
    {
        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

        $value = $dictionary->remove_at( 'b' );

        $this->assertEquals( 2, $value );
        $this->assertEquals( [ 'a' => 1, 'c' => 3 ], $dictionary->to_array() );


        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );
        unset( $dictionary[ 'b' ] );
        $this->assertEquals( [ 'a' => 1, 'c' => 3 ], $dictionary->to_array() );
    }

    public function testRemoveAtMissingKey()
    {
        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\MissingKeyError',
            function() use($dictionary){
                $dictionary->remove_at( 'd' );
            },
            function($error) use($dictionary) {
                $this->assertEquals( "The key 'd' is not defined.", $error->getMessage() );
                $this->assertSame( $dictionary, $error->get_dictionary() );
                $this->assertEquals( 'd', $error->get_key() );
            }
        );
    }

    public function testRemoveAtIfAbsent()
    {
        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

        $value = $dictionary->remove_at_if_absent( 'a', function() {
            return "default value";
        });

        $this->assertEquals( 1, $value );
        $this->assertEquals( [ 'b' => 2, 'c' => 3 ], $dictionary->to_array() );
    }

    public function testRemoveAtIfAbsentWithMissingKey()
    {
        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

        $value = $dictionary->remove_at_if_absent( 'd', function() {
            return "default value";
        });

        $this->assertEquals( "default value", $value );
        $this->assertEquals( [ 'a' => 1, 'b' => 2, 'c' => 3 ], $dictionary->to_array() );
    }

    /// Test iterating

    public function testKeysAndValuesDo()
    {
        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

        $this->keys = [];
        $this->values = [];

        $dictionary->keys_and_values_do( function($key, $value) {
            $this->keys[] = $key;
            $this->values[] = $value;
        }, $this);

        $this->assertEquals( [ 'a', 'b', 'c' ], $this->keys );
        $this->assertEquals( [ 1, 2, 3 ], $this->values );
    }

    public function testKeysDo()
    {
        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

        $this->keys = [];

        $dictionary->keys_do( function($key) {
            $this->keys[] = $key;
        }, $this);

        $this->assertEquals( [ 'a', 'b', 'c' ], $this->keys );
    }

    public function testValuesDo()
    {
        $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );

        $this->values = [];

        $dictionary->values_do( function($value) {
            $this->values[] = $value;
        }, $this);

        $this->assertEquals( [ 1, 2, 3 ], $this->values );
    }
}