<?php

namespace App\Models\Shop;

class Sections extends ShopBaseModel
{
    protected $table = 'shop.sections';

    /**
     * Уникальные значения фильтров
     *
     * @var array
     */
    public $filterValues = [];

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

    public function getAllFiltersValues()
    {
        if(count($this->filterValues)) return $this->filterValues;

        $filters = $this->sectionFilters()->with('filters')->get();
        
        foreach($filters as $filter){
           foreach($filter->filters as $eF){
               $this->filterValues[$eF->num]['list'][$eF->code] = [
                   'value' => $eF->value
               ];
           }
        }

        foreach($this->filters()->get() as $f){
            if(isset($this->filterValues[$f->num])){
                $this->filterValues[$f->num]['title'] = $f->value;
            }
        }

        return $this->filterValues;
    }

    public function parentSection()
    {
        return $this->belongsTo(Sections::class, 'parent_id', 'id');
    }

}
