<?php

namespace App\Http\Controllers\Shop\Goods;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

/**
 * Created by PhpStorm.
 * User: dmi
 * Date: 09.09.17
 * Time: 15:11
 */

class PageController extends Controller
{
    public function index($good){
        return View::make('layouts.main', ['good' => $good]);
    }
}