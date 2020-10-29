<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 29/10/2020
 * Time: 08:40
 */

namespace FormLibrary\App;


class RenderPage
{

    /**
     * $page - array with routing information
     * @var array
     */
    public static $page;

    /**
     * $template_dir - relative directory path for base templates
     * @var string
     */
    public static $template_dir = __DIR__ . '/../templates/';

    /**
     * $page_dir - relative directory path for page templates
     * @var string
     */
    public static $page_dir = __DIR__ . '/../templates/pages/';

    /**
     * $content - string for storing page html to be loaded in template
     * @var string
     */
    public static $content = '';

    /**
     * render - parse the $page array and call any required controllers
     * 
     * @param null $page
     * @param array $request
     */
    public static function render($page = null, $request = [])
    {
        self::$page = $page;
        self::verifyRoute();

        if (isset($page['class'])) {
            $full_class = (isset($page['namespace'])) ?
                $page['namespace'] . $page['class'] : $page['class'];
            try {
                $obj = new $full_class();
                $method = $page['method'];
                $obj->$method($request);
                self::$content .= $obj->html;
            } catch (\Exception $e) {
                print_r($e->getMessage());
            }
        }

        self::build();
    }

    /**
     * build - include template files
     */
    public static function build()
    {
        include_once (self::$template_dir . "head.php");
        include_once  (self::$page_dir . self::$page['template'] . '.php');
        include_once (self::$template_dir. 'footer.php');
    }

    /**
     * verify route - check $page array meets expectations or return 404 response
     */
    public static function verifyRoute()
    {
        if (is_null(self::$page) || !isset(self::$page['template']) ||
            !file_exists(self::$page_dir . self::$page['template'] . '.php')) {
            http_response_code(404);
            die;
        }
    }
}
