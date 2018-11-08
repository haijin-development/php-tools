<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\FilePath;

/**
 * Tests the $path->drop() behaviour.
 */
class FilePathDropTest extends TestCase
{
    use \Haijin\Testing\AllExpectationsTrait;

    public function testDropWithNoParameters()
    {
        $file_path = ( new FilePath( 'home/dev/src' ) )->drop();

        $this->assertEquals( 'home/dev', $file_path->to_string() );
    }

    public function testDropWithParameters()
    {
        $file_path = ( new FilePath( 'home/dev/src' ) )->drop( 0 );
        $this->assertEquals( 'home/dev/src', $file_path->to_string() );

        $file_path = ( new FilePath( 'home/dev/src' ) )->drop( 2 );
        $this->assertEquals( 'home', $file_path->to_string() );

        $file_path = ( new FilePath( 'home/dev/src' ) )->drop( 3 );
        $this->assertEquals( '', $file_path->to_string() );

        $file_path = ( new FilePath( 'home/dev/src' ) )->drop( 4 );
        $this->assertEquals( '', $file_path->to_string() );
    }

    public function testRaisesAnErrorIfNIsNegative()
    {
        $this->expectExactExceptionRaised(
            'Haijin\Tools\PathError',
            function() {
                ( new FilePath( 'home/dev/src' ) )->drop( -1 );
            },
            function($error) {
                $this->assertEquals(
                    "Haijin\Tools\FilePath->drop( -1 ): invalid parameter -1.",
                    $error->getMessage()
                );
            }
        );
    }

    public function testModifiesTheReceiverInstance()
    {
        $file_path = new FilePath( 'home/dev/src' );
        $file_path->drop();

        $this->assertEquals( 'home/dev', $file_path->to_string() );
    }

    public function testReturnsThisInstance()
    {
        $file_path = new FilePath( 'home/dev/src' );
        $dropped_path = $file_path->drop();

        $this->assertSame( $file_path, $dropped_path );
    }
}