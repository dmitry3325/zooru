<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;

/**
 * Created by PhpStorm.
 * User: dmi
 * Date: 09.09.17
 * Time: 15:11
 */
class SectionController extends Controller
{

    public function loadData($a){
        dump('aaa', $a);
    }
}