<?php
/**
 * Created by PhpStorm.
 * User: codenrock
 * Date: 07.08.19
 * Time: 10:19
 */
include "Core/global.php";
require_once 'Core/QueryBuilder.php';
require_once 'Core/Model.php';
require_once 'Core/View.php';
require_once 'Core/Controller.php';

require_once 'WebRoutes.php';
require_once 'Core/Router.php';

\App\Router::start(); // запускаем маршрутизатор
