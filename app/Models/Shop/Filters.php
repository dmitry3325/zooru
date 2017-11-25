<?php

namespace App\Models\Shop;

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

        $q = EntityFilters::join('shop.goods as g', 'g.id', '=', 'shop.entity_filters.entity_id')
            ->where('shop.entity_filters.entity', 'Goods')
            ->where('g.section_id', $this->section_id)
            ->groupBy('g.id')
            ->select('g.*');

        foreach ($filters as $num => $byCode) {
            $q->where(function ($qw) use ($byCode) {
                foreach ($byCode as $code => $filter) {
                    $qw->orWhere(function ($qwe) use($filter){
                        $qwe->where('code', $filter->code)->where('num', $filter->num);
                    });
                }
            });
        }

        dd($q->toSql());

        return $q->get();
    }
}
