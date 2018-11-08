<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\AttributePath;

class AttributePathAccessorsTest extends TestCase
{
    use \Haijin\Testing\AllExpectationsTrait;

    /// Accessing associative arrays

    public function testReadsAnAttributeValueFromAnAssociativeArray()
    {
        $object = [
            'name' => 'Lisa',
            'last_name' => 'Simpson',
            'address' => [
                'street' => 'Evergreen 742'
            ]
        ];

        $attribute_path = new AttributePath('address.street');

        $this->assertEquals( 'Evergreen 742', $attribute_path->get_value_from( $object ) );
    }

    public function testRaisesAnErrorWhenReadingAnInexistentAttributeValueFromAnAssociativeArray()
    {
        $object = [
            'name' => 'Lisa',
            'last_name' => 'Simpson',
            'address' => [
                'street' => 'Evergreen 742'
            ]
        ];

        $attribute_path = new AttributePath( 'address.number' );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\MissingAttributeError',
            function() use ($object, $attribute_path) {
                $attribute_path->get_value_from( $object );
            },
            function($exception) use ($object, $attribute_path) {
                $this->assertEquals(
                    "The nested attribute \"address.number\" was not found.",
                    $exception->getMessage()
                );

                $this->assertSame( $object, $exception->get_object() );
                $this->assertEquals( $attribute_path, $exception->get_full_attribute_path() );
                $this->assertEquals( 'address.number', $exception->get_missing_attribute_path()->to_string() );
            }
        );
    }

    public function testWritesAnAttributeValueToAnAssociativeArray()
    {
        $object = [
            'name' => 'Lisa',
            'last_name' => 'Simpson',
            'address' => [
                'street' => 'Evergreen 742'
            ]
        ];

        $attribute_path = new AttributePath( 'address.street' );

        $attribute_path->set_value_to( $object, 123 );

        $this->assertEquals( 123, $object['address']['street'] );
    }

    public function testRaisesAnErrorWhenWrittingAnInexistentAttributeToAnAssociativeArray()
    {
        $object = [
            'name' => 'Lisa',
            'last_name' => 'Simpson',
            'address' => [
                'street' => 'Evergreen 742'
            ]
        ];

        $attribute_path = new AttributePath( 'address.number' );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\MissingAttributeError',
            function() use ($object, $attribute_path) {
                $attribute_path->set_value_to( $object, 123 );
            },
            function($exception) use ($object, $attribute_path) {
                $this->assertEquals(
                    "The nested attribute \"address.number\" was not found.",
                    $exception->getMessage()
                );

                $this->assertSame( $object, $exception->get_object() );
                $this->assertEquals( $attribute_path, $exception->get_full_attribute_path() );
                $this->assertEquals( 'address.number', $exception->get_missing_attribute_path()->to_string() );
            }
        );
    }

    /// Accessing indexed arrays

    public function testReadsAnAttributeValueFromAnIndexedArray()
    {
        $object = [
            [ 'Lisa', 'Simpson' ],
            [ 'Evergreen', '742' ]
        ];

        $attribute_path = new AttributePath( '[1].[0]' );

        $this->assertEquals( 'Evergreen', $attribute_path->get_value_from( $object ) );
    }

    public function testRaisesAnErrorWhenReadingAnInexistentAttributeValueFromAnIndexedArray()
    {
        $object = [
            [ 'Lisa', 'Simpson' ],
            [ 'Evergreen', '742' ]
        ];

        $attribute_path = new AttributePath( '[1].[2]' );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\MissingAttributeError',
            function() use ($object, $attribute_path) {
                $attribute_path->get_value_from( $object );
            },
            function($exception) use ($object, $attribute_path) {
                $this->assertEquals(
                    "The nested attribute \"[1].[2]\" was not found.",
                    $exception->getMessage()
                );

                $this->assertSame( $object, $exception->get_object() );
                $this->assertEquals( $attribute_path, $exception->get_full_attribute_path() );
                $this->assertEquals( '[1].[2]', $exception->get_missing_attribute_path()->to_string() );
            }
        );
    }

    public function testWritesAnAttributeValueToAnIndexedArray()
    {
        $object = [
            [ 'Lisa', 'Simpson' ],
            [ 'Evergreen', '742' ]
        ];

        $attribute_path = new AttributePath( '[1].[0]' );

        $attribute_path->set_value_to( $object, 123 );

        $this->assertEquals( 123, $object[1][0] );
    }

    public function testRaisesAnErrorWhenWrittingAnInexistentAttributeValueToAnIndexedArray()
    {
        $object = [
            [ 'Lisa', 'Simpson' ],
            [ 'Evergreen', '742' ]
        ];

        $attribute_path = new AttributePath( '[1].[2]' );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\MissingAttributeError',
            function() use ($object, $attribute_path) {
                $attribute_path->set_value_to( $object, 123 );
            },
            function($exception) use ($object, $attribute_path) {
                $this->assertEquals(
                    "The nested attribute \"[1].[2]\" was not found.",
                    $exception->getMessage()
                );

                $this->assertSame( $object, $exception->get_object() );
                $this->assertEquals( $attribute_path, $exception->get_full_attribute_path() );
                $this->assertEquals( '[1].[2]', $exception->get_missing_attribute_path()->to_string() );
            }
        );
    }

    /// Accessing objects

    public function testReadsAnAttributeValueFromAnObject()
    {
        $object = new stdclass();
        $object->field = new stdclass();
        $object->field->field = 123;

        $attribute_path = new AttributePath( 'field.field' );

        $this->assertEquals( 123, $attribute_path->get_value_from( $object ) );
    }

    public function testRaisesAnErrorWhenReadingAnInexistentPropertyFromAnObject()
    {
        $object = new stdclass();
        $object->field = new stdclass();
        $object->field->field = 123;


        $attribute_path = new AttributePath( 'field.field_2' );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\MissingAttributeError',
            function() use ($object, $attribute_path) {
                $attribute_path->get_value_from( $object );
            },
            function($exception) use ($object, $attribute_path) {
                $this->assertEquals(
                    "The nested attribute \"field.field_2\" was not found.",
                    $exception->getMessage()
                );

                $this->assertSame( $object, $exception->get_object() );
                $this->assertEquals( $attribute_path, $exception->get_full_attribute_path() );
                $this->assertEquals( 'field.field_2', $exception->get_missing_attribute_path()->to_string() );
            }
        );
    }

    public function testWritesAnAttributeValueToAnObject()
    {
        $object = new stdclass();
        $object->field = new stdclass();
        $object->field->field = 123;

        $attribute_path = new AttributePath( 'field.field' );
        $attribute_path->set_value_to( $object, 111 );

        $this->assertEquals( 111,  $object->field->field );
    }

    public function testRaisesAnErrorWhenWrittingAnInexistentPropertyToAnObject()
    {
        $object = new stdclass();
        $object->field = new stdclass();
        $object->field->field = 123;


        $attribute_path = new AttributePath( 'field.field_2' );

        $this->expectExactExceptionRaised(
            'Haijin\Tools\MissingAttributeError',
            function() use ($object, $attribute_path) {
                $attribute_path->set_value_to( $object, 111 );
            },
            function($exception) use ($object, $attribute_path) {
                $this->assertEquals(
                    "The nested attribute \"field.field_2\" was not found.",
                    $exception->getMessage()
                );

                $this->assertSame( $object, $exception->get_object() );
                $this->assertEquals( $attribute_path, $exception->get_full_attribute_path() );
                $this->assertEquals( 'field.field_2', $exception->get_missing_attribute_path()->to_string() );
            }
        );
    }
}