<?php
require_once('src/Validate.php');

class MockUrlTest{
    use Validate;
    public function getRules()
    {
        return [
            "website" => "url"
        ];
    }
}

class UrlTest extends \PHPUnit\Framework\TestCase {

    public function testAcceptOnValidUrl(){
        $input = [
            "website" => "http://fatfreeframework.com"
        ];
        $mockEmail = new MockUrlTest();
        $this->assertEquals(true, $mockEmail->check($input));
    }

    public function testRejectWhenUrlInvalid(){
        $input = [
            "website" => "test"
        ];
        $mockEmail = new MockUrlTest();
        $this->assertContains("The value of website must be a valid url", $mockEmail->check($input));
    }
}