# Fat Free Framework Validator

An easy to use and strait to the point validation trait for Fat Free Framework 
[![Build Status](https://travis-ci.org/geofmureithi/f3-validate.svg?branch=master)](https://travis-ci.org/geofmureithi/f3-validate)
## Installation

##### Using Composer
```bash
composer require geofmureithi/f3-validate
```
##### Manually
Copy the `lib\validate.php` file to the lib folder or any root of an auto included folder.

**Remember it requires at least php 5.4 to use traits**
## Getting Started

```php
class Profile
{
    use Validate; //<--- Trait

    public function getRules()
    {
        return [
            "email" => "reqired|email"
        ];
    }
    
    public function save()
    {
        //.....
    }
}

$data = $f3->get('POST')
$profile = new Profile();
$result = $profile->check($data);
if( $result != true) return $result; //errors
$profile->save();
```
Available Validators
--------------------
* required `Ensures the specified key value exists and is not empty`
* email `Checks for a valid email address`
* max_length,n `Checks key value length, makes sure it's not longer than the specified length. n = length parameter.`
* min_length,n `Checks key value length, makes sure it's not shorter than the specified length. n = length parameter.`
* exact_length,n `Ensures that the key value length precisely matches the specified length. n = length parameter.`
* alpha `Ensure only alpha characters are present in the key value (a-z, A-Z)`
* alpha_numeric `Ensure only alpha-numeric characters are present in the key value (a-z, A-Z, 0-9)`
* numeric `Ensure only numeric key values`
* boolean `Checks for PHP accepted boolean values, returns TRUE for "1", "true", "on" and "yes"`
* url `Check for valid URL or subdomain`
* ipv4 `Check for valid IPv4 address`
* ipv6 `Check for valid IPv6 address`
* card `Check for a valid credit card number (Uses the MOD10 Checksum Algorithm)`
* phone `Validate phone numbers that match the following examples: 555-555-5555 , 5555425555, 555 555 5555, 1(519) 555-4444, 1 (519) 555-4422, 1-555-555-5555`
* regex `You can pass a custom regex using the following format: 'regex,/your-regex/'`


Adding custom validators and filters is made easy by using callback functions.

```php
$message = "The value of {0} must include each of these items : {1}";
Validate::addValidator("contains", function ($value, $ruleConfigs) {
  $required = explode("&", substr($ruleConfigs[0], 1, -1));
  $diff = array_diff($required, explode(",", $value));
  return empty($diff);
 }, $message);
 
 //Or
Validate::addValidator("custom", "SampleClass::testCustom", "Custom Error");
```
## RoadMap

- [x] Add Tests and Travis
- [x] Convert to Trait
- [x] Use Audit and make Lib more lightweight
- [x] Allow Translations
- [x] Add Composer
- [ ] Add detailed Examples

## Development
Tests are run using PHPUnit
```bash
./vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests
```

## Examples

See the tests folder for now

## Contributing

Feel free to Create a PR


