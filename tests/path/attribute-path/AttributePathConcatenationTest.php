<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\AttributePath;

/**
 * Tests the $path->concat( $path ) behaviour.
 */
class AttributePathConcatenationTest extends TestCase
{
    public function testConcatenatesAString()
    {
        $attribute_path = ( new AttributePath( 'user' ) )->concat( 'address.street' );

        $this->assertEquals( 'user.address.street', $attribute_path->to_string() );
    }

    public function testConcatenatesAnEmptyString()
    {
        $attribute_path = ( new AttributePath( 'user' ) )->concat( '' );

        $this->assertEquals( 'user', $attribute_path->to_string() );
    }

    public function testConcatenatesAnArray()
    {
        $attribute_path = ( new AttributePath( 'user' ) )->concat( [ 'address', 'street' ] );

        $this->assertEquals( 'user.address.street', $attribute_path->to_string() );
    }


    public function testConcatenatesAnEmptyArray()
    {
        $attribute_path = ( new AttributePath( 'user' ) )->concat( [] );

        $this->assertEquals( 'user', $attribute_path->to_string() );
    }

    public function testConcatenatesAnAttributePath()
    {
        $attribute_path = ( new AttributePath( 'user' ) )->concat( new AttributePath( 'address.street' ) );

        $this->assertEquals( 'user.address.street', $attribute_path->to_string() );
    }

    public function testConcatenatesAnEmptyPath()
    {
        $attribute_path = ( new AttributePath( 'user' ) )->concat( new AttributePath() );

        $this->assertEquals( 'user', $attribute_path->to_string() );
    }

    public function testDoesNotModifyTheReceiverInstance()
    {
        $attribute_path = new AttributePath( 'user' );
        $concatenated_path = $attribute_path->concat( 'address.street' );

        $this->assertEquals( 'user', $attribute_path->to_string() );
        $this->assertEquals( 'user.address.street', $concatenated_path->to_string() );
    }
}