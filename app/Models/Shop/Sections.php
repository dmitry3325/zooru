<?php

namespace App\Models\Shop;

class Sections extends ShopBaseModel
{
    protected $table = 'shop.sections';

    public function goods()
    {
        return $this->hasMany(Goods::class, 'section_id', 'id');
    }

    public function sectionFilters()
    {
        return $this->hasMany(Filters::class, 'section_id', 'id');
    }

    public function getGoods()
    {
        return $this->goods()->get();
    }


    public function parentSection()
    {
        return $this->belongsTo(Sections::class, 'parent_id', 'id');
    }

}
