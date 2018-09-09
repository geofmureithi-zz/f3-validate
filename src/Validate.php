<?php

/**
 * Created by IntelliJ IDEA.
 * User: mureithi
 * Date: 1/25/18
 * Time: 8:24 AM
 */
trait Validate
{
    public $errors = [];


    private static $extendedValidators = [
    ];
    private static $extendedErrorMessages = [
    ];
    private static $defaultErrorMessage = "The value of {0} is invalid";

    public abstract function getRules();

    private $phoneRegex = '/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i';

    protected function getDefaultErrorMessages(){
        return [
            "required" => "The value of {0} is required",
            "email" => "The value of {0} must be a valid email",
            "url" => "The value of {0} must be a valid url",
            "card" => "The value of {0} must be a valid credit card",
            "ipv4" => "The value of {0} must be a valid ipv4 address",
            "ipv6" => "The value of {0} must be a valid ipv6 address",
            "max_length" => "The length of {0} can not be greater than {1}",
            "min_length" => "The length of {0} can not be less than {1}",
            "exact_length" => "The length of {0} must be exactly {1}",
            "alphanumeric" => "The value of {0} can only contain alphanumerics",
            "alpha" => "The value of {0} can only contain alphabet letters",
            "numeric" => "The value of {0} can only contain numbers",
            "matches" => "The value of {0} should match that of {1}",
            "boolean" => "The value of {0} can only contain boolean values",
            "phone" => "The value of {0} must be a valid phone number",
            "regex" => "The value of {0} must match the regex {1}"
        ];
    }
    protected function getDefaultValidators()
    {
        return [
            "required" => function($str){
                return !!strlen($str);
            },
            "email" => function ($str, $configs = []) {
                return Audit::instance()->email($str, $configs[0]);
            },
            "url" => function ($str) {
                return Audit::instance()->url($str);
            },
            "card" => function ($str) {
                return Audit::instance()->card($str);
            },
            "ipv4" => function ($str) {
                return Audit::instance()->ipv4($str);
            },
            "ipv6" => function ($str) {
                return Audit::instance()->ipv6($str);
            },
            "max_length" => function ($str, $rule = []) {
                return strlen($str) < (int) $rule[0];
            },
            "min_length" => function ($str, $rule = []) {
                return strlen($str) > (int) $rule[0];
            },
            "exact_length" => function ($str, $rule = []) {
                return strlen($str) === (int) $rule[0];
            },
            "alphanumeric" => function ($str) {
                return ctype_alnum($str);
            },
            "alpha" => function ($str) {
                return ctype_alpha($str);
            },
            "matches" => function ($str, $rule, $input) {
                return $str === $input[$rule[0]];
            },
            "numeric" => function ($str) {
                return is_numeric($str);
            },
            "boolean" => function ($str) {
                return in_array($str, [true, "true", 1, "on", "yes", false, "false", "off", "no"], true);
            },
            "phone" => function ($str) {
                return preg_match($this->phoneRegex, $str);
            },
            "regex" => function ($str, $rule = []) {
                return preg_match($rule[0], $str);
            }

        ];
    }

    public static function addValidator($rule, $validator)
    {
        self::$extendedValidators[$rule] = $validator;
    }


    public function check($input = null)
    {
        $this->errors = [];
        $f3 = Base::instance();
        $configValidators = $f3->exists("VALIDATE.validators") ? $f3->get('VALIDATE.validators'): [];
        $configErrorMessages =$f3->exists("VALIDATE.errors") ? $f3->get('VALIDATE.errors') : [];
        $rules = $this->getRules();
        $validators = array_merge(static::getDefaultValidators(), $configValidators, static::$extendedValidators);
        $errorMessages = array_merge(static::getDefaultErrorMessages(), $configErrorMessages, static::$extendedValidators);
        $validateMapper = function ($field, $value, $rule, $input) use ($f3, $validators, $errorMessages){
            $innerRules = explode('|', $rule);
            if(!$innerRules) return true;
            foreach ($innerRules as $currentRule){
                $ruleConfigs = $f3->split($currentRule, false);
                $mainRule = $ruleConfigs[0];
                $callable = $validators[$mainRule];
                array_shift($ruleConfigs);
                if($f3->call($callable, [$value, $ruleConfigs, $input])) continue;
                $error = $errorMessages[$mainRule]?$errorMessages[$mainRule]:static::$defaultErrorMessage;
                return $f3->format($error, $field, implode(' , ', $ruleConfigs));
            }
            return true;
        };
        foreach ($rules as $field=>$rule){
            /*
             * The line below ensures that if no input and the field is not required then no need validation
             * For example, A user doesn't need to enter their website during profile creation, but if they enter, it has to be valid
             * Hence remember to add required rule.
             * [Possible Bug] Additionally, avoid rules with the string "required" unless the field is required.
             * TODO: find a better fix for the above scenario
             */
            if(!$input[$field] && !preg_match('/required/',$rule)) continue;
            $result = $validateMapper($field,$input[$field], $rule,$input);
            if($result !== true)
                $this->errors[$field] = $result;
        }
        return $this->errors? $this->errors: true;
    }
}