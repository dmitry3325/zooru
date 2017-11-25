<?php

namespace App\Http\Controllers\Shop\Goods;

use App\Http\Controllers\Controller;
use App\Models\Shop\Goods;
use Illuminate\Support\Facades\View;

/**
 * Created by PhpStorm.
 * User: dmi
 * Date: 09.09.17
 * Time: 15:11
 */
class SectionController extends Controller
{
    public function index($Section, $Filter = null)
    {
        dd($Filter->getGoods());exit();
        //берем мету и h1 из фильтра если есть. если нет из section
        if ($Filter) {

        }
        //        return View::make('section.index', ['good' => $good]);
    }

    private function getBaseData($entity)
    {

    }

}