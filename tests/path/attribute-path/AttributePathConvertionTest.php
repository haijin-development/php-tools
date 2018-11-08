<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\AttributePath;

/**
 * Tests the AttributePath convertion to strings and arrays.
 */
class AttributePathConvertionTest extends TestCase
{
    public function testConvertingToArray()
    {
        $attribute_path = new AttributePath();
        $this->assertEquals( [], $attribute_path->to_array() );

        $attribute_path = new AttributePath('user.address.street');
        $this->assertEquals( ['user', 'address', 'street' ], $attribute_path->to_array() );
    }

    public function testConvertingToDefaulString()
    {
        $attribute_path = new AttributePath();
        $this->assertEquals( '', $attribute_path->to_string() );

        $attribute_path = new AttributePath( 'user.address.street' );
        $this->assertEquals( 'user.address.street', $attribute_path->to_string() );
    }

    public function testConvertingToStringWithSeparator()
    {
        $attribute_path = new AttributePath();
        $this->assertEquals( '', $attribute_path->to_string( '/' ) );

        $attribute_path = new AttributePath( 'user.address.street' );
        $this->assertEquals( 'user/address/street', $attribute_path->to_string( '/' ) );
    }
}