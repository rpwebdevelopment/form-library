<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 29/10/2020
 * Time: 08:06
 */
session_start();

require_once ('configuration.php');

require ('vendor/autoload.php');

new \FormLibrary\Src\Bootstrap();
