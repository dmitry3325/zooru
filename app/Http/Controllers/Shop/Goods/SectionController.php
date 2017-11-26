<?php

namespace App\Http\Controllers\Shop\Goods;

use App\Http\Controllers\Controller;
use App\Models\Shop\Filters;
use App\Models\Shop\Goods;
use App\Models\Shop\Sections;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

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
        $filters = $Section->getAllFiltersValues();
        if ($Filter) {
            $goods = $Filter->getGoods();
            $contentData = $this->getBaseData($Filter);
            $activeFilters = $Filter->getFiltersAttribute();
            foreach($activeFilters as $f){
                if(isset($filters[$f->num]['list'][$f->code])){
                    $filters[$f->num]['list'][$f->code]['active'] = true;
                }
            }
        } else {
            $goods = $Section->getGoods();
            $contentData = $this->getBaseData($Section);
        }

        dump($filters);
        dump($goods);
        dump($contentData);

        // return View::make('section.index', ['good' => null]);
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