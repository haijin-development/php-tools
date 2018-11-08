<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\AttributePath;

/**
 * Tests the $path->drop() behaviour.
 */
class AttributePathDropTest extends TestCase
{
    use \Haijin\Testing\AllExpectationsTrait;

    public function testDropWithNoParameters()
    {
        $attribute_path = ( new AttributePath( 'user.address.street' ) )->drop();

        $this->assertEquals( 'user.address', $attribute_path->to_string() );
    }

    public function testDropWithParameters()
    {
        $attribute_path = ( new AttributePath( 'user.address.street' ) )->drop( 0 );
        $this->assertEquals( 'user.address.street', $attribute_path->to_string() );

        $attribute_path = ( new AttributePath( 'user.address.street' ) )->drop( 2 );
        $this->assertEquals( 'user', $attribute_path->to_string() );

        $attribute_path = ( new AttributePath( 'user.address.street' ) )->drop( 3 );
        $this->assertEquals( '', $attribute_path->to_string() );

        $attribute_path = ( new AttributePath( 'user.address.street' ) )->drop( 4 );
        $this->assertEquals( '', $attribute_path->to_string() );
    }

    public function testRaisesAnErrorIfNIsNegative()
    {
        $this->expectExactExceptionRaised(
            'Haijin\Tools\PathError',
            function() {
                ( new AttributePath( 'user.address.street' ) )->drop( -1 );
            },
            function($error) {
                $this->assertEquals(
                    "Haijin\Tools\AttributePath->drop( -1 ): invalid parameter -1.",
                    $error->getMessage()
                );
            }
        );
    }

    public function testModifiesTheReceiverInstance()
    {
        $attribute_path = new AttributePath( 'user.address.street' );
        $attribute_path->drop();

        $this->assertEquals( 'user.address', $attribute_path->to_string() );
    }

    public function testReturnsThisInstance()
    {
        $attribute_path = new AttributePath( 'user.address.street' );
        $dropped_path = $attribute_path->drop();

        $this->assertSame( $attribute_path, $dropped_path );
    }
}