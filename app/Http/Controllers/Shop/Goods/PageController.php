<?php

namespace App\Http\Controllers\Shop\Goods;

use App\Http\Controllers\Controller;
use App\Models\Shop\Goods;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

/**
 * Created by PhpStorm.
 * User: dmi
 * Date: 09.09.17
 * Time: 15:11
 */
class PageController extends Controller
{
    public function index(Goods $good)
    {
        return View::make('goods.page', ['good' => $good]);
    }
}