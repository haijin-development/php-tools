<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\FilePath;

/**
 * Tests the $path->append( $path ) behaviour.
 */
class FilePathAppendTest extends TestCase
{
    public function testConcatenatesAString()
    {
        $file_path = ( new FilePath( 'home' ) )->append( 'dev/src' );

        $this->assertEquals( 'home/dev/src', $file_path->to_string() );
    }

    public function testConcatenatesAnEmptyString()
    {
        $file_path = ( new FilePath( 'home' ) )->append( '' );

        $this->assertEquals( 'home', $file_path->to_string() );
    }

    public function testConcatenatesAnArray()
    {
        $file_path = ( new FilePath( 'home' ) )->append( [ 'dev', 'src' ] );

        $this->assertEquals( 'home/dev/src', $file_path->to_string() );
    }


    public function testConcatenatesAnEmptyArray()
    {
        $file_path = ( new FilePath( 'home' ) )->append( [] );

        $this->assertEquals( 'home', $file_path->to_string() );
    }

    public function testConcatenatesAnFilePath()
    {
        $file_path = ( new FilePath( 'home' ) )->append( new FilePath( 'dev/src' ) );

        $this->assertEquals( 'home/dev/src', $file_path->to_string() );
    }

    public function testConcatenatesAnEmptyPath()
    {
        $file_path = ( new FilePath( 'home' ) )->append( new FilePath() );

        $this->assertEquals( 'home', $file_path->to_string() );
    }

    public function testModifiesTheReceiverInstance()
    {
        $file_path = new FilePath( 'home' );
        $file_path->append( 'dev/src' );

        $this->assertEquals( 'home/dev/src', $file_path->to_string() );
    }

    public function testReturnsThisInstance()
    {
        $file_path = new FilePath( 'home' );
        $concatenated_path = $file_path->append( 'dev/src' );

        $this->assertSame( $file_path, $concatenated_path );
    }
}