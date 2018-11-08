<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\FilePath;

/**
 * Tests the $path->back() behaviour.
 */
class FilePathBackTest extends TestCase
{
    use \Haijin\Testing\AllExpectationsTrait;

    public function testBackWithNoParameters()
    {
        $file_path = ( new FilePath( 'home/dev/src' ) )->back();

        $this->assertEquals( 'home/dev', $file_path->to_string() );
    }

    public function testBackWithParameters()
    {
        $file_path = ( new FilePath( 'home/dev/src' ) )->back( 0 );
        $this->assertEquals( 'home/dev/src', $file_path->to_string() );

        $file_path = ( new FilePath( 'home/dev/src' ) )->back( 2 );
        $this->assertEquals( 'home', $file_path->to_string() );

        $file_path = ( new FilePath( 'home/dev/src' ) )->back( 3 );
        $this->assertEquals( '', $file_path->to_string() );

        $file_path = ( new FilePath( 'home/dev/src' ) )->back( 4 );
        $this->assertEquals( '', $file_path->to_string() );
    }

    public function testRaisesAnErrorIfNIsNegative()
    {
        $this->expectExactExceptionRaised(
            'Haijin\Tools\PathError',
            function() {
                ( new FilePath( 'home/dev/src' ) )->back( -1 );
            },
            function($error) {
                $this->assertEquals(
                    "Haijin\Tools\FilePath->back( -1 ): invalid parameter -1.",
                    $error->getMessage()
                );
            }
        );
    }

    public function testDoesNotModifyTheReceiverInstance()
    {
        $file_path = new FilePath( 'home/dev/src' );
        $backed_path = $file_path->back();

        $this->assertEquals( 'home/dev/src', $file_path->to_string() );
        $this->assertEquals( 'home/dev', $backed_path->to_string() );
    }
}