<?php

namespace App\Http\Controllers;

use App\Models\Shop\Sections;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct() {
        $menu = self::getMenu();
        \View::share ( 'menu', $menu );
    }


    public static function getMenu(){
        $result = [];
        $sections = Sections::all()->sortBy('parent_id')->toArray();
        foreach ($sections as $section) {
            if($section['parent_id'] === 0){
                $result[$section['id']] = [];
                $result[$section['id']]['main'] = $section;
            } else {
                $result[$section['parent_id']][] = $section;
            }
        }
        return $result;
    }
}
