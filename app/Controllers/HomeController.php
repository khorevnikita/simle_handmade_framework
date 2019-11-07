<?php
/**
 * Created by PhpStorm.
 * User: Nikita
 * Date: 07.11.2019
 * Time: 15:14
 */

namespace App\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return $this->render('home', []);
    }
}