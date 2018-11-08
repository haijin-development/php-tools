<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\ObjectAttributeAccessor;

class ObjectAttributeAccessorsTest extends TestCase
{
    use \Haijin\Testing\AllExpectationsTrait;

    public function testGetsAnExistingAttribute()
    {
        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
            "address" => [
                "street" => "Evergreen 742"
            ]
        ];

        $accessor = new ObjectAttributeAccessor( $object );

        $this->assertEquals( "Lisa", $accessor->get_value_at( "name" ) );
        $this->assertEquals( "Evergreen 742", $accessor->get_value_at( "address.street" ) );
    }

    public function testAtAbsentAttribute()
    {
        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
            "address" => [
                "street" => "Evergreen 742"
            ]
        ];

        $accessor = new ObjectAttributeAccessor( $object );

        $this->assertEquals(
            "Absent value",
            $accessor->get_value_at_if_absent( "address.number",  function() { return "Absent value"; })
        );

        $this->assertEquals(
            "Absent value",
            $accessor->get_value_at_if_absent( "address.number",  "Absent value" )
        );
    }

    public function testRaisesAnErrorWhenTryingToGetAnInexistingAttribute()
    {
        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
            "address" => [
                "street" => "Evergreen 742"
            ]
        ];

        $accessor = new ObjectAttributeAccessor( $object );

        $this->expectExactExceptionRaised(
            "Haijin\Tools\MissingAttributeError",
            function() use($accessor) {
                $accessor->get_value_at( "address.number" );
            },
            function($error) use($object) {
                $this->assertEquals( 'The nested attribute "address.number" was not found.', $error->getMessage() );
                $this->assertEquals( "address.number", $error->get_full_attribute_path() );
                $this->assertSame( $object, $error->get_object() );
            }
        );
    }

    public function testSetsAnExistingAttribute()
    {
        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
            "address" => [
                "street" => "Evergreen 742"
            ]
        ];

        $accessor = new ObjectAttributeAccessor( $object );
        $accessor->set_value_at( "address.street", "Evergreen" );

        $this->assertEquals( "Evergreen", $object["address"]["street"] );
    }

    public function testRaisesAnErrorWhenTryingToSetAnInexistingAttribute()
    {
        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
            "address" => [
                "street" => "Evergreen 742"
            ]
        ];

        $accessor = new ObjectAttributeAccessor( $object );

        $this->expectExactExceptionRaised(
            "Haijin\Tools\MissingAttributeError",
            function() use($accessor) {
                $accessor->set_value_at( "address.number", 742 );
            },
            function($error) use($object) {
                $this->assertEquals( 'The nested attribute "address.number" was not found.', $error->getMessage() );
                $this->assertEquals( "address.number", $error->get_full_attribute_path() );
                $this->assertSame( $object, $error->get_object() );
            }
        );
    }

    public function testCreatesAndSetsAnInexistingAttribute()
    {
        $object = [
            "name" => "Lisa",
            "last_name" => "Simpson",
        ];

        $accessor = new ObjectAttributeAccessor( $object );
        $accessor->create_value_at( "addresses.[0].address.number", 742 );

        $this->assertEquals( 742, $object["addresses"][0]["address"]["number"] );
    }

    public function testDoesNotOverrideExistingAttributeWhenCreatingInexistingAttribute()
    {
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

        $this->assertEquals( "Evergreen", $object["addresses"][0]["address"]["street"] );
        $this->assertEquals( 742, $object["addresses"][0]["address"]["number"] );
    }
}