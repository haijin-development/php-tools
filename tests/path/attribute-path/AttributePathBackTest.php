<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\AttributePath;

/**
 * Tests the $path->back() behaviour.
 */
class AttributePathBackTest extends TestCase
{
    use \Haijin\Testing\AllExpectationsTrait;

    public function testBackWithNoParameters()
    {
        $attribute_path = ( new AttributePath( 'user.address.street' ) )->back();

        $this->assertEquals( 'user.address', $attribute_path->to_string() );
    }

    public function testBackWithParameters()
    {
        $attribute_path = ( new AttributePath( 'user.address.street' ) )->back( 0 );
        $this->assertEquals( 'user.address.street', $attribute_path->to_string() );

        $attribute_path = ( new AttributePath( 'user.address.street' ) )->back( 2 );
        $this->assertEquals( 'user', $attribute_path->to_string() );

        $attribute_path = ( new AttributePath( 'user.address.street' ) )->back( 3 );
        $this->assertEquals( '', $attribute_path->to_string() );

        $attribute_path = ( new AttributePath( 'user.address.street' ) )->back( 4 );
        $this->assertEquals( '', $attribute_path->to_string() );
    }

    public function testRaisesAnErrorIfNIsNegative()
    {
        $this->expectExactExceptionRaised(
            'Haijin\Tools\PathError',
            function() {
                ( new AttributePath( 'user.address.street' ) )->back( -1 );
            },
            function($error) {
                $this->assertEquals(
                    "Haijin\Tools\AttributePath->back( -1 ): invalid parameter -1.",
                    $error->getMessage()
                );
            }
        );
    }

    public function testDoesNotModifyTheReceiverInstance()
    {
        $attribute_path = new AttributePath( 'user.address.street' );
        $backed_path = $attribute_path->back();

        $this->assertEquals( 'user.address.street', $attribute_path->to_string() );
        $this->assertEquals( 'user.address', $backed_path->to_string() );
    }
}