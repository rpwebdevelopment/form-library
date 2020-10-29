<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 29/10/2020
 * Time: 14:51
 */

namespace FileLibrary\Src;


use mysql_xdevapi\Exception;
use Volnix\CSRF\CSRF;

class FormValidator
{
    public $rules = [];

    public $errors = [];

    public $request = [];

    public $successful = false;

    /**
     * FormValidator constructor - build ruleset
     * @param array $rules
     */
    public function __construct($rules = [])
    {
        foreach ($rules as $field => $ruleset) {
            foreach ($ruleset as $rule) {
                $this->rules[$field] = [];
                $parts = explode('|', $rule);
                foreach ($parts as $part) {
                    if (strpos($part, ':') === false) {
                        $this->rules[$field][] = $part;
                    } else {
                        $subparts = explode(':', $part);
                        $this->rules[$field][] = [$subparts[0] => $subparts[1]];
                    }
                }
            }
        }

    }

    /**
     * validate - parse rules and run all validation methods on request data
     *
     * @param array $request
     */
    public function validate(array $request)
    {
        // validate our CSRF token
        if (!CSRF::validate($request)) {
            $this->errors['form'] = 'Token mismatch, could not submit form';
        } else {
            foreach ($this->rules as $field => $rulset) {
                $ruleset = $this->rules[$field];
                // check to ensure field is completed if mandatory
                if (in_array('required', $ruleset) && empty($request[$field])) {
                    $this->errors[$field] = 'This field is mandatory, please complete before re-submitting.';
                    continue;
                }

                // check our rules are being fulfilled
                $value = (isset($request[$field])) ? $request[$field] : false;
                if ($value) {
                    foreach ($ruleset as $rule) {
                        if (!is_array($rule) && $rule !== 'required' &&
                            empty($this->errors[$field])) {
                            $this->$rule($field, $value);
                        } elseif (is_array($rule) && empty($this->errors[$field])) {
                            $func = key($rule);
                            $this->$func($field, $rule[$func], $value);
                        }
                    }
                }
            }
        }
        $this->successful = (!count($this->errors));
    }

    /**
     * string - sanitize strings
     *
     * @param $field
     * @param $string
     */
    public function string($field, $string)
    {
        if (!is_string($string)) {
            $this->errors[$field] = 'Please enter a valid value before re-submitting';
            unset($this->request[$field]);
            return;
        }

        $string = strip_tags($string);
        $string = filter_var($string, FILTER_SANITIZE_STRING);
        $this->request[$field] = $string;
    }

    /**
     * email - ensure valid email address given
     *
     * @param $field
     * @param $string
     */
    public function email($field, $string)
    {
        if (!filter_var($string, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'Please enter a valid email address.';
            unset($this->request[$field]);
            return;
        }

        $parts = explode('@', $string);
        $domain = $parts[1];
        if (!checkdnsrr($domain, 'MX')) {
            $this->errors[$field] = 'Please enter a valid email address.';
            unset($this->request[$field]);
            return;
        }

        $string = filter_var($string, FILTER_SANITIZE_EMAIL);
        $this->request[$field] = $string;
    }

    /**
     * int - ensure value is an integer
     *
     * @param $field
     * @param $int
     */
    public function int($field, $int)
    {
        if (!filter_var($int, FILTER_VALIDATE_INT)) {
            $this->errors[$field] = 'Please enter a valid value before re-submitting.';
            unset($this->request[$field]);
            return;
        }

        $int = filter_var($int, FILTER_SANITIZE_NUMBER_INT);
        $this->request[$field] = $int;
    }

    /**
     * float - ensure value is a float
     *
     * @param $field
     * @param $float
     */
    public function float($field, $float)
    {
        if (!filter_var($float, FILTER_VALIDATE_FLOAT)) {
            $this->errors[$field] = 'Please enter a valid value before re-submitting';
            unset($this->request[$field]);
            return;
        }

        $float = filter_var($float, FILTER_SANITIZE_NUMBER_FLOAT);
        $this->request[$field] = $float;
    }

    /**
     * bool - ensure value is boolean
     *
     * @param $field
     * @param $bool
     */
    public function bool($field, $bool)
    {
        if (!filter_var($bool, FILTER_VALIDATE_BOOLEAN)) {
            $this->errors[$field] = 'Please enter a valid value before re-submitting';
            unset($this->request[$field]);
            return;
        }

        $this->request[$field] = $bool;
        return;
    }

    /**
     * min - ensure string is at least as long as minimum length
     *
     * @param $field
     * @param $spec
     * @param $value
     */
    public function min($field, $spec, $value)
    {
        if (strlen($value) < $spec) {
            $this->errors[$field] = 'Value must be at least ' . $spec . ' characters long';
            unset($this->request[$field]);
            return;
        }

        $this->request[$field] = $value;
    }

    /**
     * max - ensure string does not exceed expected length
     *
     * @param $field
     * @param $spec
     * @param $value
     */
    public function max($field, $spec, $value)
    {
        if (strlen($value) > $spec) {
            $this->errors[$field] = 'Value must be less than ' . $spec . ' characters long';
            unset($this->request[$field]);
            return;
        }

        $this->request[$field] = $value;
    }

    /**
     * above - ensure given value is greater than specified
     *
     * @param $field
     * @param $spec
     * @param $value
     */
    public function above($field, $spec, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_INT) &&
            !filter_var($value, FILTER_VALIDATE_FLOAT)) {
            $this->errors[$field] = 'Please enter a valid value.';
            unset($this->request[$field]);
            return;
        }

        if ($value <= $spec) {
            $this->errors[$field] = 'Value must be above ' . $spec;
            unset($this->request[$field]);
            return;
        }

        $this->request[$field] = $value;
    }

    /**
     * below - ensure given value is less than specified
     *
     * @param $field
     * @param $spec
     * @param $value
     */
    public function below($field, $spec, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_INT) &&
            !filter_var($value, FILTER_VALIDATE_FLOAT)) {
            $this->errors[$field] = 'Please enter a valid value.';
        }

        if ($value >= $spec) {
            $this->errors[$field] = 'Value must be below ' . $spec;
            unset($this->request[$field]);
            return;
        }

        $this->request[$field] = $value;
    }

    /**
     * arr - ensure given value matches an expected value
     *
     * @param $field
     * @param $spec
     * @param $value
     */
    public function arr($field, $spec, $value)
    {
        $array = explode(',', $spec);
        if (!in_array($value, $array)) {
            $this->errors[$field] = 'Unexpected value detected.';
            unset($this->request[$field]);
            return;
        }

        $this->request[$field] = $value;
    }

    /**
     * checkbox - ensure value saves in predetermined format
     *
     * @param $field
     * @param $spec
     * @param $value
     * @throws \Exception
     */
    public function checkbox($field, $spec, $value)
    {
        if ($spec == 'int') {
            $value = ($value == 'on') ? 1 : 0;
        } elseif ($spec == 'bool') {
            $value = ($value == 'on') ? true : false;
        } else {
            try {
                $e = 'Invalid form configuration';
                throw new \Exception($e);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        $this->request[$field] = $value;
    }
}