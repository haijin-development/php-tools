<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\FilePath;

/**
 * Tests the FilePath constructor behaviour.
 */
class FilePathConstructorTest extends TestCase
{
    public function testCreatesAnEmptyPath()
    {
        $file_path = new FilePath();
        $this->assertEquals( '', $file_path->to_string() );
    }

    public function testCreatesAPathFromAnFilesString()
    {
        $file_path = new FilePath( 'home/dev/src' );
        $this->assertEquals( 'home/dev/src', $file_path->to_string() );
    }

    public function testCreatesAPathFromAnFilesArray()
    {
        $file_path = new FilePath( ['home', 'dev', 'src'] );
        $this->assertEquals( 'home/dev/src', $file_path->to_string() );
    }

    public function testCreatesAPathFromAnotherPath()
    {
        $file_path = new FilePath( new FilePath( 'home/dev/src' ) );
        $this->assertEquals( 'home/dev/src', $file_path->to_string() );
    }
}