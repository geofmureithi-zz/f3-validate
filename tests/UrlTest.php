<?php


class MockUrlTest
{
    use Validate;

    public function getRules()
    {
        return [
            "website" => "url"
        ];
    }
}

class UrlTest extends \PHPUnit\Framework\TestCase
{

    public function testAcceptOnValidUrl()
    {
        $input = [
            "website" => "http://fatfreeframework.com"
        ];
        $mock = new MockUrlTest();
        $this->assertEquals(true, $mock->check($input));
    }

    public function testRejectWhenUrlInvalid()
    {
        $input = [
            "website" => "test"
        ];
        $mock = new MockUrlTest();
        $this->assertContains("The value of website must be a valid url", $mock->check($input));
    }
}