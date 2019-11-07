<?php
/**
 * Created by PhpStorm.
 * User: Nikita
 * Date: 07.11.2019
 * Time: 11:00
 */

namespace App;

class WebRoutes
{
    static $web = [
        '/login' => [
            'path' => 'AuthController@login',
            'method' => 'get'
        ],
        '/register' => [
            'path' => 'AuthController@register',
            'method' => 'get'
        ],
        /*'/' => [
            'path' => 'HomeController@index',
            'method' => 'get'
        ],*/

        '/user/{username}' => [
            'path' => 'UserController@show',
            'method' => 'get'
        ],

        'auth/{id}/edit/{name}' => [
            'path' => "AuthController@byId",
            'method' => "get"
        ],
    ];
}