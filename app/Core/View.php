<?php
/**
 * Created by PhpStorm.
 * User: Nikita
 * Date: 07.11.2019
 * Time: 10:58
 */

namespace App;


class View
{
    protected $content;

    public function render($template, $params)
    {
        foreach ($params as $k => $v) {
            $$k = $v;
        }

        include_once "app/Views/$template.php";

        return true;
    }

    function extend($path)
    {
        $file = 'app/Views/' . $path . '.php';
        include_once $file;
    }

    function start_section()
    {
        return ob_start();
    }

    function end_section()
    {
        $this->content = ob_get_clean();
    }

    function content()
    {
        echo $this->content;
    }
}