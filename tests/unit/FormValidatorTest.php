<?php

use PHPUnit\Framework\TestCase;
use FileLibrary\Src\FormValidator;

class FormValidatorTest extends TestCase
{

    public function testStringValidation() : void
    {
        $string = "test string";
        $validator = new FormValidator();

        $validator->string('test', $string);
        $this->assertSame($string, $validator->request['test']);

        $int = 1;
        $validator->string('test', $int);
        $this->assertSame('Please enter a valid value before re-submitting', $validator->errors['test']);
    }

    public function testEmailValidation() : void
    {
        $email = 'test@mailinator.com';
        $validator = new FormValidator();

        $validator->email('test', $email);
        $this->assertSame($email, $validator->request['test']);

        $string = 'this is a test';
        $validator->email('test', $string);
        $this->assertSame('Please enter a valid email address.', $validator->errors['test']);
    }

    public function testIntValidation() : void
    {
        $int = 2;
        $validator = new FormValidator();

        $validator->int('test', $int);
        $this->assertSame($int, (int) $validator->request['test']);

        $string = 'this is a test';
        $validator->int('test', $string);
        $this->assertSame('Please enter a valid value before re-submitting.', $validator->errors['test']);
    }

    public function testBoolValidation() : void
    {
        $bool = true;
        $validator = new FormValidator();

        $validator->bool('test', $bool);
        $this->assertSame($bool, $validator->request['test']);

        $string = 'this is a test';
        $validator->bool('test', $string);
        $this->assertSame('Please enter a valid value before re-submitting', $validator->errors['test']);
    }

    public function testMaxValidation() : void
    {
        $string = 'test';
        $validator = new FormValidator();

        $validator->max('test', 6, $string);
        $this->assertSame($string, $validator->request['test']);

        $string = 'this is a test';
        $validator->max('test', 6, $string);
        $this->assertSame('Value must be less than 6 characters long', $validator->errors['test']);
    }

    public function testMinValidation() : void
    {
        $string = 'this is a test';
        $validator = new FormValidator();

        $validator->min('test', 6, $string);
        $this->assertSame($string, $validator->request['test']);

        $string = 'test';
        $validator->min('test', 6, $string);
        $this->assertSame('Value must be at least 6 characters long', $validator->errors['test']);
    }

    public function testAboveValidation() : void
    {
        $int = 10;
        $validator = new FormValidator();

        $validator->above('test', 6, $int);
        $this->assertSame($int, (int) $validator->request['test']);

        $validator->above('test', 12, $int);
        $this->assertSame('Value must be above 12', $validator->errors['test']);
    }

    public function testBelowValidation() : void
    {
        $int = 10;
        $validator = new FormValidator();

        $validator->below('test', 12, $int);
        $this->assertSame($int, (int) $validator->request['test']);

        $validator->below('test', 6, $int);
        $this->assertSame('Value must be below 6', $validator->errors['test']);
    }

    public function testArrayValidation() : void
    {
        $string = 'test';
        $validator = new FormValidator();

        $validator->arr('test', 'test,testing,another', $string);
        $this->assertSame($string, $validator->request['test']);

        $validator->arr('test', 'testing,another', $string);
        $this->assertSame('Unexpected value detected.', $validator->errors['test']);
    }

    public function testCheckboxValidation() : void
    {
        $string = 'on';
        $validator = new FormValidator();

        $validator->checkbox('test', 'int', $string);
        $this->assertSame(1, (int) $validator->request['test']);

        $string = 'off';
        $validator->checkbox('test', 'int', $string);
        $this->assertSame(0, (int) $validator->request['test']);
    }
}