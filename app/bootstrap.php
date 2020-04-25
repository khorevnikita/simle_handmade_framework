<?php
/**
 * Created by PhpStorm.
 * User: codenrock
 * Date: 07.08.19
 * Time: 10:19
 */
include "core/global.php";
require_once 'Core/querybuilder.php';
require_once 'Core/model.php';
require_once 'Core/view.php';
require_once 'Core/controller.php';

require_once 'WebRoutes.php';
require_once 'Core/router.php';

\App\Router::start(); // запускаем маршрутизатор
