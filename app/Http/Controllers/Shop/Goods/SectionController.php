<?php

namespace App\Http\Controllers\Shop\Goods;

use App\Http\Controllers\Controller;
use App\Models\Shop\Filters;
use App\Models\Shop\Goods;
use App\Models\Shop\Sections;
use App\Services\Shop\SectionService;

/**
 * Created by PhpStorm.
 * User: dmi
 * Date: 09.09.17
 * Time: 15:11
 */
class SectionController extends Controller
{
    public function index(Sections $Section, Filters $Filter = null)
    {
        $SectionService = new SectionService($Section, $Filter);

        $res = $SectionService->getData();
        dump($res);

    }

    private function getBaseData($entity)
    {
        return [
            'html_title' => 'bla',
            'title' => 'bla',
            'shot_description' => 'bla',
        ];
    }

}