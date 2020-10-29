<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 29/10/2020
 * Time: 12:53
 */

namespace FormLibrary\App\Abstracts;


abstract class AbstractForm
{
    public $html = '';

    public $errors = [];

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
