<?php

namespace App\Models\Shop;

class Sections extends ShopBaseModel
{
    protected $table = 'shop.sections';


    public static function deleteEntity($id){
        $up = [
            'section_id' => 0
        ];
        Goods::where('section_id','=',$id)->update($up);
        Filters::where('section_id','=',$id)->update($up);
        return parent::deleteEntity($id);
    }
}
