<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\FilePath;

class FilePathGetLastFileTest extends TestCase
{
    use \Haijin\Testing\AllExpectationsTrait;

    public function testGetLastFileFromEmptyPath()
    {
        $file_path = new FilePath();

        $this->assertEquals( '', $file_path->get_last_attribute() );
    }

    public function testGetLastFileFromNonEmptyPath()
    {
        $file_path = new FilePath('dev/src');

        $this->assertEquals( 'src', $file_path->get_last_attribute() );
    }
}