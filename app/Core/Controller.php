<?php
/**
 * Created by PhpStorm.
 * User: Nikita
 * Date: 07.11.2019
 * Time: 10:58
 */

namespace App\Controllers;


use App\View;

class Controller
{
    public function render($template, $params = [])
    {
        $view = new View();
        return $view->render($template, $params);
    }
}