<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 29/10/2020
 * Time: 08:47
 */

namespace FormLibrary\Src;

use FormLibrary\Src\RenderPage;


class Bootstrap
{
    /**
     * Bootstrap constructor - check the request method and render call the page renderer
     */
    public function __construct()
    {
        $routes = new Routes();
        $route_list = ($_SERVER['REQUEST_METHOD'] == 'GET') ?
            $routes->get : $routes->post;

        $request = ($_SERVER['REQUEST_METHOD'] == 'GET') ? $_GET : $_POST;

        $page = $route_list[$_SERVER['REQUEST_URI']] ??
            $route_list[rtrim($_SERVER['REQUEST_URI'], '/')] ??
            false;

        if (!$page) {
            http_response_code(404);
            die;
        }

        RenderPage::render($page, $request);
    }
}
