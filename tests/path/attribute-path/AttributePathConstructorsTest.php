<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\AttributePath;

/**
 * Tests the AttributePath constructor behaviour.
 */
class AttributePathConstructorTest extends TestCase
{
    public function testCreatesAnEmptyPath()
    {
        $attribute_path = new AttributePath();
        $this->assertEquals( '', $attribute_path->to_string() );
    }

    public function testCreatesAPathFromAnAttributesString()
    {
        $attribute_path = new AttributePath( 'user.name.address' );
        $this->assertEquals( 'user.name.address', $attribute_path->to_string() );
    }

    public function testCreatesAPathFromAnAttributesArray()
    {
        $attribute_path = new AttributePath( ['user', 'name', 'address'] );
        $this->assertEquals( 'user.name.address', $attribute_path->to_string() );
    }

    public function testCreatesAPathFromAnotherPath()
    {
        $attribute_path = new AttributePath( new AttributePath( 'user.name.address' ) );
        $this->assertEquals( 'user.name.address', $attribute_path->to_string() );
    }
}