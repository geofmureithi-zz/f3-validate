<?php

class MockLengthTest2
{
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

class LengthTest2 extends \PHPUnit\Framework\TestCase
{

    public function testAcceptWhenValid()
    {
        $input = [
            "max_length" => "exactlyten",
            "min_length" => "exactlyten",
            "exact_length" => "exactlyten"
        ];
        $mock = new MockLengthTest2();
        $this->assertEquals(true, $mock->check($input));
    }

    public function testRejectWhenInvalid()
    {
        $input = [
            "max_length" => "exactly ten",
            "min_length" => "exactly ten",
            "exact_length" => "exactly ten"
        ];
        $mock = new MockLengthTest2();
        $result = $mock->check($input);
        $this->assertContains("The length of max_length can not be greater than 10", $result);
        $this->assertContains("The length of min_length can not be less than 10", $result);
        $this->assertContains("The length of exact_length must be exactly 10", $result);
    }
}
