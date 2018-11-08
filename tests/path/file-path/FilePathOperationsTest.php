<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\FilePath;

class FilePathOperationsTest extends TestCase
{
    public function testGettingFileName()
    {
        $file_path = new FilePath( __DIR__ . "/file-samples/file-sample.txt" );

        $this->assertEquals( "file-sample.txt", $file_path->file_name() );
    }

    public function testGettingFileExtension()
    {
        $file_path = new FilePath( __DIR__ . "/file-samples/file-sample.txt" );

        $this->assertEquals( "txt", $file_path->file_extension() );
    }

    public function testGettingTheFileContents()
    {
        $file_path = new FilePath( __DIR__ . "/file-samples/file-sample.txt" );

        $this->assertEquals( "Sample", $file_path->file_contents() );
    }
}