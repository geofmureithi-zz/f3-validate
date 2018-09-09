<?php
require_once('src/Validate.php');

class MockLengthTest{
    use Validate;
    public function getRules()
    {
        return [
            "max_length" => "max_length,10",
            "min_length" => "min_length,10",
            "exact_length" => "exact_length,10"
        ];
    }
}

class LengthTest extends \PHPUnit\Framework\TestCase {

    public function testAcceptWhenValid(){
        $input = [
            "max_length" => "below 10",
            "min_length" => "greater than ten",
            "exact_length" => "exactlyten"
        ];
        $mock = new MockLengthTest();
        var_dump($mock->check($input));
        $this->assertEquals(true, $mock->check($input));
    }

    public function testRejectWhenInvalid(){
        $input = [
            "max_length" => "greater than ten",
            "min_length" => "below 10",
            "exact_length" => "no exactly ten"
        ];
        $mock = new MockLengthTest();
        $this->assertContains("The length of max_length can not be greater than 10", $mock->check($input));
        $this->assertContains("The length of min_length can not be less than 10", $mock->check($input));
        $this->assertContains("The length of exact_length must be 10", $mock->check($input));
    }
}