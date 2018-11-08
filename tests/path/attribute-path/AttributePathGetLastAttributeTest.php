<?php

use PHPUnit\Framework\TestCase;
use Haijin\Tools\AttributePath;

class AttributePathGetLastAttributeTest extends TestCase
{
    use \Haijin\Testing\AllExpectationsTrait;

    public function testGetLastAttributeFromEmptyPath()
    {
        $attribute_path = new AttributePath();

        $this->assertEquals( '', $attribute_path->get_last_attribute() );
    }

    public function testGetLastAttributeFromNonEmptyPath()
    {
        $attribute_path = new AttributePath('address.street');

        $this->assertEquals( 'street', $attribute_path->get_last_attribute() );
    }
}