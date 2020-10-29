<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 29/10/2020
 * Time: 08:41
 */

namespace FormLibrary\Src;


class Routes
{
    /**
     * $fet - array for storage of all GET routes
     * @var array
     */
    public $get = [];

    /**
     * $post - array for storage of all POST routes
     * @var array
     */
    public $post = [];

    /**
     * $namespace - default controller namespace
     * @var string
     */
    public $namespace = 'FormLibrary\\Src\\Controllers\\';

    /**
     * Routes constructor.
     */
    public function __construct()
    {
        $this->setRoutes();
    }

    /**
     * setRoutes - declaration of any routes within the application
     */
    protected function setRoutes()
    {
        $this->get = [
            '/' => [
                'template' => 'home',
                'namespace' => $this->namespace,
                'class' => 'UserForm',
                'method' => 'build'
            ],
            '/inline-one' => [
                'template' => 'inline-one'
            ],
            '/inline-two' => [
                'template' => 'inline-two'
            ]
        ];

        $this->post = [
            '/' => [
                'template' => 'home',
                'namespace' => $this->namespace,
                'class' => 'UserForm',
                'method' => 'handle'
            ]
        ];
    }
}
