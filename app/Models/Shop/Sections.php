<?php

namespace App\Models\Shop;

class Sections extends ShopBaseModel
{
    protected $table = 'shop.sections';

    public function parentSection(){
        return $this->belongsTo(Sections::class, 'parent_id', 'id');
    }

}
