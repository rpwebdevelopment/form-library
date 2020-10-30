<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 29/10/2020
 * Time: 12:53
 */

namespace FormLibrary\Src\Abstracts;


abstract class AbstractForm
{
    public $html = '';

    public $rules = [];

    public $request = [];

    public $errors = [];

    public function __construct()
    {
        $this->errors['form'] = (!isset($this->errors['form'])) ?
            false : $this->errors['form'];
        foreach ($this->rules as $field => $rule) {
            $this->errors[$field] = (!isset($this->errors[$field])) ?
                false : $this->errors[$field];
        }

        foreach ($this->rules as $field => $rule) {
            $this->request[$field] = (!isset($this->request[$field])) ?
                null : $this->request[$field];
        }
    }

    /**
     * build - build method required to construct form HTML
     * @return mixed
     */
    abstract public function build();

    /**
     * handle - processes submitted form request
     * @param array $request
     * @return mixed
     */
    abstract public function handle(array $request = []);
}
