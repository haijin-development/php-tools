<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\FilePath;

/**
 * Tests the $path->concat( $path ) behaviour.
 */
class FilePathConcatenationTest extends TestCase
{
    public function testConcatenatesAString()
    {
        $file_path = ( new FilePath( 'home' ) )->concat( 'dev/src' );

        $this->assertEquals( 'home/dev/src', $file_path->to_string() );
    }

    public function testConcatenatesAnEmptyString()
    {
        $file_path = ( new FilePath( 'home' ) )->concat( '' );

        $this->assertEquals( 'home', $file_path->to_string() );
    }

    public function testConcatenatesAnArray()
    {
        $file_path = ( new FilePath( 'home' ) )->concat( [ 'dev', 'src' ] );

        $this->assertEquals( 'home/dev/src', $file_path->to_string() );
    }


    public function testConcatenatesAnEmptyArray()
    {
        $file_path = ( new FilePath( 'home' ) )->concat( [] );

        $this->assertEquals( 'home', $file_path->to_string() );
    }

    public function testConcatenatesAFilePath()
    {
        $file_path = ( new FilePath( 'home' ) )->concat( new FilePath( 'dev/src' ) );

        $this->assertEquals( 'home/dev/src', $file_path->to_string() );
    }

    public function testConcatenatesAnEmptyPath()
    {
        $file_path = ( new FilePath( 'home' ) )->concat( new FilePath() );

        $this->assertEquals( 'home', $file_path->to_string() );
    }

    public function testDoesNotModifyTheReceiverInstance()
    {
        $file_path = new FilePath( 'home' );
        $concatenated_path = $file_path->concat( 'dev/src' );

        $this->assertEquals( 'home', $file_path->to_string() );
        $this->assertEquals( 'home/dev/src', $concatenated_path->to_string() );
    }
}