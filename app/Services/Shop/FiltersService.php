<?php

namespace App\Services\Shop;

use App\Models\Shop\Filters;
use App\Models\Shop\Sections;
use App\Models\Shop\ShopBaseModel;
use Illuminate\Support\Facades\Redis;

class FiltersService
{
    /**
     * Товары по фильтру
     *
     * sectionId -> filterKey -> goodsList
     */
    const FILTERED_GOODS = 'filtered_goods';

    /**
     * Фильтры разделов
     *
     * sectionId => filterKey -> filterData
     *
     */
    const SECTION_FILTERS = 'section_filters';

    /**
     * @var \Illuminate\Redis\Connections\Connection
     */
    protected $sectionFiltersStorage;

    /**
     * @var \Illuminate\Redis\Connections\Connection
     */
    protected $filterGoodsStorage;

    /**
     * @var Sections
     */
    protected $section;

    /**
     * @var Filters|null
     */
    protected $filter;

    /**
     * FiltersService constructor.
     *
     * @param Sections     $section
     * @param Filters|null $filter
     */
    public function __construct(Sections $section, Filters $filter = null)
    {
        $this->section = $section;
        $this->filter = $filter;

        $this->sectionFiltersStorage = Redis::connection(self::SECTION_FILTERS);
        $this->filterGoodsStorage = Redis::connection(self::FILTERED_GOODS);
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = $this->getFromRedis();
        dump($data);
        if ($data) {
            $data = $this->getFromDB();
        }

        return $data;
    }

    /**
     * @return array|null
     */
    private function getFromRedis()
    {
        $allFilters = $this->getSectionFiltersFromRedis('all_data');
        if (!$allFilters) return null;

        $goods = [];
        if ($this->filter) {
            $key = $this->filter->getKey();
            $filterData = $this->getSectionFiltersFromRedis($key);
            $goods = $this->getFilterGoodsFromRedis($key);

            foreach ($allFilters as $num => &$data) {
                foreach ($data['list'] as $code => &$filter) {
                    if (!isset($filterData[$num][$code])) {
                        $filter['disabled'] = 1;
                    } else {
                        $filter['url'] = $filterData[$num][$code]['url'];
                    }
                }
            }
        }

        return [
            'filters' => $allFilters,
            'goods'   => $goods,
        ];
    }

    private function getFromDB()
    {

        $allFilters = [];
        $filters = $this->section->sectionFilters()->with('filters')->with('url')->get();
        foreach ($filters as $filter) {
            $eFils = $filter->filters;

            foreach ($eFils as $eF) {
                if(!isset($allFilters[$eF->num]['list'][$eF->code])) {
                    $allFilters[$eF->num]['list'][$eF->code] = [
                        'value' => $eF->value,
                        'code'  => $eF->code,
                    ];
                }
            }
            if(count($eFils) === 1){
                $allFilters[$eF->num]['list'][$eF->code]['url'] = ShopBaseModel::generateUrl('Filters',$filter->id, $filter->url);
            }
        }

        foreach ($this->section->filters as $f) {
            if (isset($allFilters[$f->num])) {
                $allFilters[$f->num]['title'] = $f->value;
            }
        }

        dump($allFilters);
        return [

        ];
    }

    /**
     * @param string $filterKey
     *
     * @return array|null
     */
    private function getFilterGoodsFromRedis(string $filterKey = null)
    {
        if ($filterKey) {
            $list = $this->filterGoodsStorage->hget($this->section->id, $filterKey);

            return ($list) ? json_decode($list, true) : null;
        } else {
            $list = $this->filterGoodsStorage->hgetall($this->section->id);

            foreach ($list as &$data) {
                $data = json_decode($data, true);
            }

            return $list;
        }
    }


    /**
     * @param string $filterKey
     *
     * @return array|null
     */
    private function getSectionFiltersFromRedis(string $filterKey = null)
    {
        if ($filterKey) {
            $list = $this->sectionFiltersStorage->hget($this->section->id, $filterKey);

            return ($list) ? json_decode($list, true) : null;
        } else {
            $list = $this->sectionFiltersStorage->hgetall($this->section->id);

            foreach ($list as &$data) {
                $data = json_decode($data, true);
            }

            return $list;
        }
    }


}