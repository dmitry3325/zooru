<?php

namespace App\Http\Controllers\Shop\Goods;

use App\Http\Controllers\Controller;
use App\Models\Shop\Filters;
use App\Models\Shop\Sections;
use App\Services\Shop\SectionService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;

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
        $params = [
            'filter' => Input::get('filter') ?? [],
            'currentPage' => Input::get('page') ?? [],
        ];
        $SectionService->setParams($params);
        $res = $SectionService->getData();
        //        dd($res);

        return View::make('section.index', [
            'goods'          => array_get($res, 'goods'),
            'filters_schema' => array_get($res, 'filters_schema'),
            'section_id'     => $Section->id,
        ]);
    }

    private function getBaseData($entity)
    {
        return [
            'html_title'       => 'bla',
            'title'            => 'bla',
            'shot_description' => 'bla',
        ];
    }

}