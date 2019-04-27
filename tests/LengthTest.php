<?php

class MockLengthTest
{
    use Validate;

    public function getRules()
    {
        return [
            "max_length" => "max_length,10",
            "min_length" => "min_length,10",
            "exact_length" => "exact_length,10",
            "mb_max_length" => "mb_max_length,10",
            "mb_min_length" => "mb_min_length,10",
            "mb_exact_length" => "mb_exact_length,10"
        ];
    }
}

class LengthTest extends \PHPUnit\Framework\TestCase
{

    public function testAcceptWhenValid()
    {
        $input = [
            "max_length" => "below 10",
            "min_length" => "greater than ten",
            "exact_length" => "exactlyten",
            "mb_max_length" => str_repeat("©", 9),
            "mb_min_length" => str_repeat("©", 11),
            "mb_exact_length" => str_repeat("©", 10)
        ];
        $mock = new MockLengthTest();
        $this->assertEquals(true, $mock->check($input));
    }

    public function testRejectWhenInvalid()
    {
        $input = [
            "max_length" => "greater than ten",
            "min_length" => "below 10",
            "exact_length" => "no exactly ten",
            "mb_max_length" => str_repeat("©", 11),
            "mb_min_length" => str_repeat("©", 9),
            "mb_exact_length" => str_repeat("©", 11)
        ];
        $mock = new MockLengthTest();
        $result = $mock->check($input);
        $this->assertContains("The length of max_length can not be greater than 10", $result);
        $this->assertContains("The length of min_length can not be less than 10", $result);
        $this->assertContains("The length of exact_length must be exactly 10", $result);
    }
}
