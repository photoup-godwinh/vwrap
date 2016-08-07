<?php
namespace SimplePhp;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Exceptions\NestedValidationException;
class Validator {

    protected $validators;

    protected $errors;

    protected $input;

    protected $rules_arr;

    public function __construct($input, $rules) {
        if(is_array($input) && is_array($rules) && empty(array_diff_key($input, $rules))) {
            // This is a multi input - multi validator case

            $this->input = $input;
            $this->rules_arr = $rules;
        } else if(!is_array($input)) {
            if(!is_array($rules) && !($rules instanceof \Respect\Validation\Validator)) {
                throw new \InvalidArgumentException("Rule is not an instance of Respect Validator class");
            }

            // This is a 1 input- multi validator case
            // or a 1 input - 1 validator case
            $this->input = $input;
            $this->rules_arr[] = $rules;
        } else {
            throw new \InvalidArgumentException("Incorrect input and/or rules format");
        }
    }

    public function validate() {
        $this->_purgeErrors();

        if(!is_array($this->input)) {
            foreach($this->rules_arr as $key => $validator) {
                $this->_check($validator, $this->input);
            }
        } else {
            foreach($this->input as $key => $input) {
                $this->_check($this->rules_arr[$key], $input, $key);
            }
        }

        return $this->pass();
    }

    public function check(\Respect\Validation\Validator $validator, $input, $key = null) {
        $rules_count = count($validator->getRules());

        if($rules_count > 1) {
            try {
                $validator->assert($input);
            } catch(NestedValidationException $e) {
                $this->_appendErrors($e->getMessages(), $key);
            }
        } else {
            try {
                $validator->check($input);
            } catch(ValidationException $e) {
                $this->_appendError($e->getMainMessage(), $key);
            }
        }
    }

    public static function create($input, $rules) {
        $validator = new self($input, $rules);

        return $validator;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function pass() {
        return empty($this->errors);
    }

    private function _appendError($message, $key = null) {
        if($key) {
            if(!isset($this->errors[$key])) {
                $this->errors[$key] = array();
            }

            $this->errors[$key][] = $message;
        } else {
            $this->errors[] = $message;
        }
    }

    private function _appendErrors($messages, $key = null) {
        foreach($messages as $value) {
            $this->_appendError($value, $key);
        }
    }

    private function _check($validator, $input, $key = null) {
        if(is_array($validator)) {
            foreach($validator as $index => $v) {
                $this->check($v, $input, $key);
            }
        } else {
            $this->check($validator, $input, $key);
        }
    }

    private function _purgeErrors() {
        $this->errors = [];
    }
}
