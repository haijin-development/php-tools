<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\AttributePath;

/**
 * Tests the $path->append( $path ) behaviour.
 */
class AttributePathAppendTest extends TestCase
{
    public function testConcatenatesAString()
    {
        $attribute_path = ( new AttributePath( 'user' ) )->append( 'address.street' );

        $this->assertEquals( 'user.address.street', $attribute_path->to_string() );
    }

    public function testConcatenatesAnEmptyString()
    {
        $attribute_path = ( new AttributePath( 'user' ) )->append( '' );

        $this->assertEquals( 'user', $attribute_path->to_string() );
    }

    public function testConcatenatesAnArray()
    {
        $attribute_path = ( new AttributePath( 'user' ) )->append( [ 'address', 'street' ] );

        $this->assertEquals( 'user.address.street', $attribute_path->to_string() );
    }


    public function testConcatenatesAnEmptyArray()
    {
        $attribute_path = ( new AttributePath( 'user' ) )->append( [] );

        $this->assertEquals( 'user', $attribute_path->to_string() );
    }

    public function testConcatenatesAnAttributePath()
    {
        $attribute_path = ( new AttributePath( 'user' ) )->append( new AttributePath( 'address.street' ) );

        $this->assertEquals( 'user.address.street', $attribute_path->to_string() );
    }

    public function testConcatenatesAnEmptyPath()
    {
        $attribute_path = ( new AttributePath( 'user' ) )->append( new AttributePath() );

        $this->assertEquals( 'user', $attribute_path->to_string() );
    }

    public function testModifiesTheReceiverInstance()
    {
        $attribute_path = new AttributePath( 'user' );
        $attribute_path->append( 'address.street' );

        $this->assertEquals( 'user.address.street', $attribute_path->to_string() );
    }

    public function testReturnsThisInstance()
    {
        $attribute_path = new AttributePath( 'user' );
        $concatenated_path = $attribute_path->append( 'address.street' );

        $this->assertSame( $attribute_path, $concatenated_path );
    }
}