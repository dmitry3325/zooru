<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Shop\Sections;
use App\Services\Shop\SectionService;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

/**
 * Created by PhpStorm.
 * User: dmi
 * Date: 09.09.17
 * Time: 15:11
 */
class SectionController extends Controller
{

    public function loadData(){

        $entity = Sections::find(Input::get('sectionId'));

        $SectionService = new SectionService($entity, null);

        $params = [
            'filter' => Input::get('filter') ?? [],
            'currentPage' => Input::get('page') ?? [],
            'perPage' => Input::get('perPage', 24),
        ];
        $SectionService->setParams($params);

        $res = $SectionService->getData();

        $result = [
            'goods' => View::make('section.goods', ['goods' => array_get($res, 'goods')])->render(),
            'filters_schema' => array_get($res, 'filters_schema'),
        ];

        return $result;
    }
}