<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\FilePath;

/**
 * Tests the FilePath convertion to strings and arrays.
 */
class FilePathConvertionTest extends TestCase
{
    public function testConvertingToArray()
    {
        $file_path = new FilePath();
        $this->assertEquals( [], $file_path->to_array() );

        $file_path = new FilePath('home/dev/src');
        $this->assertEquals( ['home', 'dev', 'src' ], $file_path->to_array() );
    }

    public function testConvertingToDefaulString()
    {
        $file_path = new FilePath();
        $this->assertEquals( '', $file_path->to_string() );

        $file_path = new FilePath( 'home/dev/src' );
        $this->assertEquals( 'home/dev/src', $file_path->to_string() );
    }

    public function testConvertingToStringWithSeparator()
    {
        $file_path = new FilePath();
        $this->assertEquals( '', $file_path->to_string( '/' ) );

        $file_path = new FilePath( 'home/dev/src' );
        $this->assertEquals( 'home/dev/src', $file_path->to_string( '/' ) );
    }
}