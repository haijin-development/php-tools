<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\FilePath;

/**
 * Tests the behaviour of an absolute $path.
 */
class AbsoluteFilePathTest extends TestCase
{
    public function testPathsAreRelativeByDefault()
    {
        $file_path = new FilePath();

        $this->assertEquals( "", $file_path->to_string() );

        $this->assertSame( true, $file_path->is_relative() );
        $this->assertSame( false, $file_path->is_absolute() );
    }

    public function testCreatesAnAbsolutePathFromAString()
    {
        $file_path = new FilePath( '/home' );

        $this->assertEquals( "/home", $file_path->to_string() );

        $this->assertSame( false, $file_path->is_relative() );
        $this->assertSame( true, $file_path->is_absolute() );
    }

    public function testCreatesAnAbsolutePathFromAnArray()
    {
        $file_path = new FilePath( [ '/home' ] );

        $this->assertEquals( "/home", $file_path->to_string() );

        $this->assertSame( false, $file_path->is_relative() );
        $this->assertSame( true, $file_path->is_absolute() );
    }

    public function testCreatesAnAbsolutePathFromAnAnotherAbsolutePath()
    {
        $file_path = new FilePath( new FilePath( '/home' ) );

        $this->assertEquals( "/home", $file_path->to_string() );

        $this->assertSame( false, $file_path->is_relative() );
        $this->assertSame( true, $file_path->is_absolute() );
    }

    public function testPreservesTheAbsolutenessWhenConcatenantingAPath()
    {
        $file_path = new FilePath( '/home' );

        $concatenated_file_path = $file_path->concat( 'dev/src' );

        $this->assertEquals( "/home/dev/src", $concatenated_file_path->to_string() );

        $this->assertSame( false, $concatenated_file_path->is_relative() );
        $this->assertSame( true, $concatenated_file_path->is_absolute() );
    }

    public function testPreservesTheAbsolutenessWhenAppendingAPath()
    {
        $file_path = new FilePath( '/home' );

        $concatenated_file_path = $file_path->append( 'dev/src' );

        $this->assertEquals( "/home/dev/src", $file_path->to_string() );

        $this->assertSame( false, $concatenated_file_path->is_relative() );
        $this->assertSame( true, $concatenated_file_path->is_absolute() );
    }

    public function testPreservesTheAbsolutenessWhenGoingBackAPath()
    {
        $file_path = new FilePath( '/home/dev/src' );

        $backed_file_path = $file_path->back();

        $this->assertEquals( "/home/dev", $backed_file_path->to_string() );

        $this->assertSame( false, $backed_file_path->is_relative() );
        $this->assertSame( true, $backed_file_path->is_absolute() );
    }

    public function testPreservesTheAbsolutenessWhenDroppingAPath()
    {
        $file_path = new FilePath( '/home/dev/src' );

        $file_path->drop();

        $this->assertEquals( "/home/dev", $file_path->to_string() );

        $this->assertSame( false, $file_path->is_relative() );
        $this->assertSame( true, $file_path->is_absolute() );
    }
}