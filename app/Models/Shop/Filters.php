<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Collection;

class Filters extends ShopBaseModel
{
    const COUNT = 8;

    protected $table = 'shop.filters';

    public function getGoods()
    {
        $filters = [];
        foreach ($this->getFiltersAttribute() as $filter) {
            $filters[$filter->num][$filter->code] = $filter;
        }

        $Q = EntityFilters::select('shop.entity_filters.entity_id')
            ->join('shop.goods as g', 'g.id', '=', 'shop.entity_filters.entity_id')
            ->where('shop.entity_filters.entity', 'Goods')
            ->where('g.section_id', $this->section_id);

        $byFilters = [];
        foreach ($filters as $num => $byCode) {
            $orValues = [];
            foreach ($byCode as $code => $filter) {
                $q = clone $Q;
                $list = $q->where('shop.entity_filters.code', $code)
                    ->where('shop.entity_filters.num', $num)->pluck('entity_id')->toArray();

                $orValues = $orValues + $list;
            }
            $byFilters[] = $orValues;
        }
        $ids = call_user_func_array('array_intersect', $byFilters);

        return (count($ids))? Goods::whereIn('id', $ids)->get() : new Collection();
    }
}
