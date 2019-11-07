<?php
/**
 * Created by PhpStorm.
 * User: Nikita
 * Date: 07.11.2019
 * Time: 10:58
 */

namespace App;


class Model
{
    function __construct()
    {
        spl_autoload_register(function ($class_name) {
            include "app/Models/" . $class_name . '.php';
        });
    }

    public static function search()
    {
        $query = new QueryBuilder();
        $result = $query->find(get_called_class());
        return $result;
    }

    public function save()
    {
        $query = new QueryBuilder();
        $result = $query->save($this);
        return $result;
    }
}