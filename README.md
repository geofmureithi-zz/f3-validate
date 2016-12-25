# Fat Free Framework Validator

A Validation plugin for Fat Free Framework (Forked by GUMP).
GUMP is a standalone PHP data validation and filtering class.

## Installation

Copy the `lib\validate.php` file to the lib folder or any root of an auto included folder.

## Getting Started

```php
$data = $f3->get('POST')
$valid = Validate::is_valid($data, array(
	'username' => 'required|alpha_numeric',
	'password' => 'required|max_len,100|min_len,6'
));

if($valid === true) {
	// continue
} else {
	print_r($valid);
}
```
Available Validators
--------------------
* required `Ensures the specified key value exists and is not empty`
* valid_email `Checks for a valid email address`
* max_len,n `Checks key value length, makes sure it's not longer than the specified length. n = length parameter.`
* min_len,n `Checks key value length, makes sure it's not shorter than the specified length. n = length parameter.`
* exact_len,n `Ensures that the key value length precisely matches the specified length. n = length parameter.`
* alpha `Ensure only alpha characters are present in the key value (a-z, A-Z)`
* alpha_numeric `Ensure only alpha-numeric characters are present in the key value (a-z, A-Z, 0-9)`
* alpha_dash `Ensure only alpha-numeric characters + dashes and underscores are present in the key value (a-z, A-Z, 0-9, _-)`
* alpha_space `Ensure only alpha-numeric characters + spaces are present in the key value (a-z, A-Z, 0-9, \s)`
* numeric `Ensure only numeric key values`
* integer `Ensure only integer key values`
* boolean `Checks for PHP accepted boolean values, returns TRUE for "1", "true", "on" and "yes"`
* float `Checks for float values`
* valid_url `Check for valid URL or subdomain`
* url_exists `Check to see if the url exists and is accessible`
* valid_ip `Check for valid generic IP address`
* valid_ipv4 `Check for valid IPv4 address`
* valid_ipv6 `Check for valid IPv6 address`
* valid_cc `Check for a valid credit card number (Uses the MOD10 Checksum Algorithm)`
* valid_name `Check for a valid format human name`
* contains,n `Verify that a value is contained within the pre-defined value set`
* containsList,n `Verify that a value is contained within the pre-defined value set. The list of valid values must be provided in semicolon-separated list format (like so: value1;value2;value3;..;valuen). If a validation error occurs, the list of valid values is not revelead (this means, the error will just say the input is invalid, but it won't reveal the valid set to the user.`
* doesNotcontainList,n `Verify that a value is not contained within the pre-defined value set. Semicolon (;) separated, list not outputted. See the rule above for more info.`
* street_address `Checks that the provided string is a likely street address. 1 number, 1 or more space, 1 or more letters`
* iban `Check for a valid IBAN`
* min_numeric `Determine if the provided numeric value is higher or equal to a specific value`
* max_numeric `Determine if the provided numeric value is lower or equal to a specific value`
* date `Determine if the provided input is a valid date (ISO 8601)`
* starts `Ensures the value starts with a certain character / set of character`
* phone_number `Validate phone numbers that match the following examples: 555-555-5555 , 5555425555, 555 555 5555, 1(519) 555-4444, 1 (519) 555-4422, 1-555-555-5555`
* regex `You can pass a custom regex using the following format: 'regex,/your-regex/'`
* valid_json_string `validate string to check if it's a valid json format`
* equalsfield `Check if two fields are equals`

Available Filters
-----------------
Filters can be any PHP function that returns a string. You don't need to create your own if a PHP function exists that does what you want the filter to do.

* sanitize_string `Remove script tags and encode HTML entities;`
* urlencode `Encode url entities`
* htmlencode `Encode HTML entities`
* sanitize_email `Remove illegal characters from email addresses`
* sanitize_numbers `Remove any non-numeric characters`
* trim `Remove spaces from the beginning and end of strings`
* base64_encode `Base64 encode the input`
* base64_decode `Base64 decode the input`
* sha1 `Encrypt the input with the secure sha1 algorithm`
* md5 `MD5 encode the input`
* noise_words `Remove noise words from string`
* json_encode `Create a json representation of the input`
* json_decode `Decode a json string`
* rmpunctuation `Remove all known punctuation characters from a string`
* basic_tags `Remove all layout orientated HTML tags from text. Leaving only basic tags`
* whole_number `Ensure that the provided numeric value is represented as a whole number`

Adding custom validators and filters is made easy by using callback functions.

```php

/* 
   Create a custom validation rule named "is_code". Imagine we want the user to create a 6 letter code with 3 letters and 3 numbers eg: FAT300
   The callback receives 3 arguments:
   The field to validate, the values being validated, and any parameters used in the validation rule.
   It should return a boolean value indicating whether the value is valid.
*/
Validate::add_validator("is_code", function($field, $input, $param = NULL) {
    return preg_match("^[A-Z]{3}\d{3}$", $input[$field])
});

/* 
   Create a custom filter named "upper".
   The callback function receives two arguments:
   The value to filter, and any parameters used in the filter rule. It should returned the filtered value.
*/
Validate::add_filter("upper", function($value, $params = NULL) {
    return strtoupper($value);
});
// This code must be included before activating the is_valid or run functions
```
Adding using with Cortex (by Ikkez):
```php
class Category extends \DB\Cortex {
    protected $table ="categories";
    protected $fieldConf = [
        'code' => array(
            'type' => \DB\SQL\Schema::DT_VARCHAR128,
            'validate'=> 'required|max_len,3', //Validations
            'filter'=> 'trim|sanitize_string', //Filters
        ),
        'title' => array(
            'type' => \DB\SQL\Schema::DT_VARCHAR128,
            'validate'=> 'required|alpha_numeric', //Validations
            'filter'=> 'trim|sanitize_string', //Filters
        ),
		'slug' => array(
            'type' => \DB\SQL\Schema::DT_VARCHAR128,
        ),
        'description' => array(
            'type' => 'TEXT'
        ),
        'icon' => array(
            'type' => 'TEXT'
        ),
	]
}

/* Now in your controller or something */
    function add($f3) {

        if($f3->exists('POST')) {
            $this->model = new Category()
            $validator= \Validate::instance();
            $validator->add_cortex($this->model);
            $data= $f3->get('POST');
            if($validator->run($data)){
                $this->model->reset();
                $this->model->copyfrom('POST');
                $this->model->save();
                \Flash::instance()->addMessage('New Record Successfully added to Database','success');
                $f3->reroute('/admin/' .$this->prural);
            }
            foreach ($validator->get_errors_array() as $field=>$error) {
                #Handle Your errors
                //\Flash::instance()->addMessage($error,'warning');  # Uncomment if you are using Flash
            }

        }
        $f3->set('page.inner','/add.html');
        $f3->set('page.title','Add New'));

    }
 ```
 More documentation and examples can be found here:
 https://github.com/Wixel/GUMP
