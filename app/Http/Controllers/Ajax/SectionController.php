<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Shop\Urls;
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

        $U = Urls::getEntityByUrl(Input::get('url'));

        $entity = $U->entity::find($U->entity_id);

        if ($U->entity === 'Filters') {
            $SectionService = new SectionService($entity->section, $entity);
        } elseif ($U->entity === 'Sections') {
            $SectionService = new SectionService($entity);
        }

        $res = $SectionService->getData();

        $result = [
            'goods' => View::make('section.goods', ['goods' => array_get($res, 'goods')])->render(),
            'filters_schema' => array_get($res, 'filters_schema')
        ];

        return $result;
    }
}