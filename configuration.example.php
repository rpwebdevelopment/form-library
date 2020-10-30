<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 30/10/2020
 * Time: 09:41
 */

  $env = [
      'DB_HOST' => '',
      'DB_USERNAME' => '',
      'DB_PASSWORD' => '',
      'DB_NAME' => '',
      'DB_PORT' => '',
  ];

  foreach ($env as $key => $value) {
      putenv("$key=$value");
  }