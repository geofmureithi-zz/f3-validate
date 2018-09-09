<?php

class MockAphaNumericTest
{
    use Validate;

    public function getRules()
    {
        return [
            "alphanumeric" => "alphanumeric",
            "alpha" => "alpha",
            "numeric" => "numeric",
        ];
    }
}

class AphaNumericTest extends \PHPUnit\Framework\TestCase
{

    public function testAcceptWhenValid()
    {
        $input = [
            "alphanumeric" => "alphanumeric1235",
            "alpha" => "alphakzzz",
            "numeric" => "13.05",
        ];
        $mock = new MockAphaNumericTest();
        $this->assertEquals(true, $mock->check($input));
    }

    public function testRejectWhenInvalid()
    {
        $input = [
            "alphanumeric" => "! @ # & ( ) – [ { } ] : ; ', ? / *",
            "alpha" => "12345",
            "numeric" => "numeric",
        ];
        $mock = new MockAphaNumericTest();
        $result = $mock->check($input);
        $this->assertContains("The value of alphanumeric can only contain alphanumerics", $result);
        $this->assertContains("The value of alpha can only contain alphabet letters", $result);
        $this->assertContains("The value of numeric can only contain numbers", $result);
    }
}