<?php
/**
 * Created by PhpStorm.
 * User: codenrock
 * Date: 07.08.19
 * Time: 10:19
 */
include "core/global.php";
require_once 'core/querybuilder.php';
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';

require_once 'webroutes.php';
require_once 'core/router.php';

\App\Router::start(); // запускаем маршрутизатор