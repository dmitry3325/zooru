<?php

namespace App\Services\Shop;

use App\Models\Shop\EntityFilters;
use App\Models\Shop\Filters;
use App\Models\Shop\Sections;
use App\Models\Shop\Goods;
use App\Models\Shop\ShopBaseModel;
use Illuminate\Support\Facades\Redis;

class FiltersService
{
    /**
     * Структура фильтров для раздела
     */

    const KEY_FILTERS_SCHEMA = 'filters_schema';


    /**
     * Товары по фильтрам
     *
     * sectionId -> filterKey -> goodsList
     */
    const KEY_GOODS_FILTER = 'goods_filter';

    /**
     * Фильтры разделов
     *
     * sectionId => filterKey -> filterData
     *
     */
    const KEY_SECTION_FILTERS = 'section_filters';

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

        /*$this->sectionFiltersStorage = Redis::connection(self::SECTION_FILTERS);
        $this->filterGoodsStorage = Redis::connection(self::FILTERED_GOODS);*/
    }

    /**
     * @return array
     */
    public function getData()
    {
        $schema = $this->getFiltersSchema();
        $filtersUrls = $this->getExistingFilters();
        $filteredGoods = $this->getGoods();


        $efList = [];
        $filterFE = [];
        if($this->filter) {
            $filterFE = clone $this->filter->filters;
            foreach($this->filter->filters as $f){
                $efList[$f->num][$f->code] = $f;
            }
        }

        $goodsInCurrent = [];
        if($this->filter){
            $lists = [];
            $makeIntersect = true;
            foreach($filterFE as $f){
                if(!isset($filteredGoods[$f->num][$f->code])){
                    $makeIntersect = false;
                    break;
                }else{
                    $lists[] = $filteredGoods[$f->num][$f->code];
                }
            }
            if($makeIntersect) {
                if (count($lists) > 1) {
                    $goodsInCurrent = call_user_func_array('array_intersect_key', $lists);
                } else {
                    $goodsInCurrent = $lists[0];
                }
            }
        }

        $sectionClassName = Sections::getClassName();
        foreach($schema as $num => &$byCode){
            foreach($byCode as $code => &$data){
                $localEfList = clone $filterFE;
                $thisFilter = (object)['num'=>$num, 'code' => $code];

                $disabled = false;
                if($this->filter){
                    if(!isset($efList[$num][$code])){
                        $localEfList[] = $thisFilter;
                    }else{
                        foreach($localEfList as $k=>$f){
                            if($f->num === $num && $f->code === $code){
                                unset($localEfList[$k]); break;
                            }
                        }
                    }
                }else{
                    $localEfList[] = $thisFilter;
                }

                $data['goods_count'] = 0;
                if(isset($filteredGoods[$num][$code])) {
                    $goodsInThisFilter = array_intersect_key($goodsInCurrent, $filteredGoods[$num][$code]);
                    $data['goods_count'] = count($goodsInThisFilter);
                }

                if(!$data['goods_count']){
                    $disabled = true;
                }
                $data['disabled'] = $disabled;
                if(!$disabled) {
                    $key = Filters::getFilterKey($localEfList);
                    if (isset($filtersUrls[$key])) {
                        $data['url'] = $filtersUrls[$key]['url'];
                    } else {
                        $data['url'] = ShopBaseModel::getUrl($sectionClassName, $this->section->id, $this->section->url);
                    }
                }
            }
        }

        $q = Goods::with('url');
        if(count($goodsInCurrent)){
            $q->whereIn('id', $goodsInCurrent);
        }
        $goods = $q->get();

        return [
            'filters_schema' => $schema,
            'goods' => $goods
        ];
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
        $schema = $this->getFiltersSchema();
        $existingFilters = $this->getExistingFilters();
        $goodsByFilter = $this->getGoods();

        dump($existingFilters, $goodsByFilter);
        die;


        return [

        ];
    }

    private function getFiltersSchema()
    {
        $key = self::KEY_FILTERS_SCHEMA . ':' . $this->section->id;

        \Cache::forget($key);
        return \Cache::remember($key, 24 * 60, function () {
            $filters = EntityFilters::select('shop.entity_filters.num', 'shop.entity_filters.code', 'shop.entity_filters.value')
                ->join('shop.goods', function ($join) {
                    $join->on('shop.goods.id', '=', 'shop.entity_filters.entity_id');
                })
                ->where('shop.goods.section_id', '=', $this->section->id)
                ->where('shop.goods.hidden', '=', 0)
                ->where('shop.entity_filters.entity', '=', Goods::getClassName())
                ->groupBy('shop.entity_filters.num', 'shop.entity_filters.code')
                ->orderBy(\DB::raw('null'))
                ->get();

            $data = [];
            foreach ($filters as $fil) {
                $data[$fil->num][$fil->code] = [
                    'value' => $fil->value,
                    'code'  => $fil->code,
                ];
            }

            return $data;
        });
    }

    /**
     * Загружает существующие фильтры
     *
     * @param $section_id
     */
    private function getExistingFilters()
    {
        $key = self::KEY_SECTION_FILTERS . ':' . $this->section->id;
        \Cache::forget($key);
        return \Cache::remember($key, 24 * 60, function () {
            $existingFilters = EntityFilters::select(['shop.entity_filters.*', 'shop.urls.url'])
                ->join('shop.filters', function ($join) {
                    $join->on('shop.filters.id', '=', 'shop.entity_filters.entity_id');
                })
                ->join('shop.urls', function ($join) {
                    $join->on('shop.filters.id', '=', 'shop.urls.entity_id');
                    $join->where('shop.urls.entity', '=', Filters::getClassName());
                })
                ->where('shop.filters.section_id', '=', $this->section->id)
                ->where('shop.filters.hidden', '=', 0)
                ->where('shop.entity_filters.entity', '=', Filters::getClassName())
                ->get();

            $byFilter = [];
            foreach ($existingFilters as $filter) {
                $byFilter[$filter->entity_id][] = $filter;
            }

            $Data = [];
            foreach ($byFilter as $id => $filters) {

                $key = Filters::getFilterKey($filters);

                if (!isset($Data[$key])) {
                    $Data[$key] = [
                        'key'            => $key,
                        'id'     => $id,
                        'url'    => array_first($filters)->url,
                    ];
                }
            }

            return $Data;
        });

    }

    private function getGoods()
    {
        $key = self::KEY_GOODS_FILTER . ':' . $this->section->id;

        \Cache::forget($key);
        return \Cache::remember($key, 60, function () {
            $goodsFLS = EntityFilters::select('shop.entity_filters.*')
                ->join('shop.goods', function ($join) {
                    $join->on('shop.goods.id', '=', 'shop.entity_filters.entity_id');
                })
                ->where('shop.goods.section_id', '=', $this->section->id)
                ->where('shop.goods.hidden', '=', 0)
                ->where('shop.entity_filters.entity', '=', Goods::getClassName())
                ->get();

            $byFilter = [];
            foreach ($goodsFLS as $fl) {
                $byFilter[$fl->num][$fl->code][$fl->entity_id] = $fl->entity_id;
            }

            return $byFilter;
        });
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