<?php

namespace App\Services\Shop;

use App\Models\Shop\EntityFilters;
use App\Models\Shop\Filters;
use App\Models\Shop\Sections;
use App\Models\Shop\Goods;
use App\Models\Shop\ShopBaseModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Redis;

class SectionService
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
     * @var Sections
     */
    protected $section;

    protected $goodsDataStorage;

    /**
     * @var Filters|null
     */
    protected $filter;

    public $paginate = true;

    public $pageParamName = 'page';
    public $filterData = [];

    public $perPage = 20;

    public $currentPage = 1;

    public $orderByColumn = 'orderby';

    public $orderByWay = 'DESC';

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

        try {
            $this->goodsDataStorage = new GoodsStorage();
        } catch (\Exception $e) {

        }
    }

    public function setParams($params){
        if(isset($params['filter'])){
            $this->filterData = $params['filter'];
        }
        if(isset($params['perPage'])){
            $this->perPage = $params['perPage'];
        }
        if(isset($params['currentPage'])){
            $this->currentPage = $params['currentPage'];
        }
        if(isset($params['orderBy'])){
            $this->orderByColumn = $params['orderBy']['column'] ?? $this->orderByColumn;
            $this->orderByWay = $params['orderByWay']['way'] ??  $this->orderByWay;
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        list($schema, $filteredGoods, $goodsDataById) = $this->getFiltersSchema();

        $efList = [];
        $filterFE = new Collection();
        if ($this->filter) {
            $filterFE = clone $this->filter->filters;
        }

        $addParams = $this->fillQueryParams($schema, $filterFE);
        if (count($filterFE)) {
            foreach ($filterFE as $f) {
                $efList[$f->num][$f->code] = $f;
            }
        }
        $goodsInCurrent = $this->getGoodsForCurrent($filterFE, $filteredGoods, $addParams, $goodsDataById);
        $schema = $this->fillFiltersSchema($schema, $filteredGoods, $goodsDataById, $goodsInCurrent, $filterFE, $efList, $addParams);

        $q = Goods::with('url')->where('hidden', 0);

        if (count($goodsInCurrent)) {
            $q->whereIn('id', $goodsInCurrent);
        } else {
            $q->where(\DB::raw('0 = 1'));
        }


        $q->orderBy($this->orderByColumn, $this->orderByWay);
        $goods = $q->paginate($this->perPage, ['*'], $this->pageParamName, $this->currentPage);

        return [
            'filters_schema' => $schema,
            'goods'          => $goods,
        ];
    }

    /**
     * @param            $schema
     * @param Collection $filterFE
     *
     * @return array
     */
    private function fillQueryParams(&$schema, Collection &$filterFE)
    {
        $filterData = $this->filterData;
        if (!$filterData) return [];

        $addParams = [];
        foreach ($schema as $num => &$filter) {
            if (isset($filterData[$filter['code']])) {
                $viewType = $filter['view_type'] ?? 'items_list';
                $data = $filterData[$filter['code']];
                if ($viewType === 'items_list') {
                    if (!is_array($data)) $data = [$data];
                    foreach ($data as $code) {
                        if (isset($filter['list'][$code])) {
                            $ef = new EntityFilters();
                            $ef->num = $num;
                            $ef->code = $filter['list'][$code]['code'];
                            $ef->value = $filter['list'][$code]['value'];
                            $filterFE->add($ef);
                        }
                    }
                } else if ($viewType === 'data_range') {
                    switch ($filter['code']) {
                        case EntityFilters::FILTER_PRICE:
                        case EntityFilters::FILTER_WEIGHT:
                            $addParams[$filter['code']] = $schema[$num]['value'] = [
                                'min' => floatval(array_get($data, 'min', $filter['range']['min'])),
                                'max' => floatval(array_get($data, 'max', $filter['range']['max'])),
                            ];
                        default :
                            break;
                    }
                }
            }
        }

        return $addParams;
    }

    /**
     * @param $schema
     * @param $filteredGoods
     * @param $goodsDataById
     * @param $goodsInCurrent
     * @param $filterFE
     * @param $efList
     * @param $addParams
     *
     * @return array
     */
    private function fillFiltersSchema($schema, $filteredGoods, $goodsDataById, $goodsInCurrent, $filterFE, $efList, $addParams)
    {
        $filtersUrls = $this->getExistingFilters();
        $sectionClassName = Sections::getClassName();

        $filterByAddParams = count($addParams);
        foreach ($schema as $num => &$byCode) {
            if ($byCode['view_type'] !== 'items_list') continue;
            foreach ($byCode['list'] as $code => &$data) {
                $localEfList = [];
                if ($filterFE) {
                    $localEfList = clone $filterFE;
                }
                $thisFilter = (object)['num' => $num, 'code' => $code];

                $disabled = false;
                if (count($filterFE)) {
                    if (!isset($efList[$num][$code])) {
                        $localEfList[] = $thisFilter;
                    } else {
                        foreach ($localEfList as $k => $f) {
                            if ($f->num === $num && $f->code === $code) {
                                unset($localEfList[$k]);
                                break;
                            }
                        }
                    }
                } else {
                    $localEfList[] = $thisFilter;
                }
                $data['goods_count'] = 0;
                /** Фильтруем по фильрам */
                if (isset($filteredGoods[$num][$code])) {
                    if (count($filterFE)) {
                        $goodsInThisFilter = array_intersect_key($goodsInCurrent, $filteredGoods[$num][$code]);
                    } else {
                        $goodsInThisFilter = $filteredGoods[$num][$code];
                    }

                    /** Оставшиеся по параметрам если есть*/
                    if (count($goodsInThisFilter) && $filterByAddParams) {
                        $goodsInThisFilter = $this->filterByAddParams($goodsInThisFilter, $addParams, $goodsDataById);
                    }


                    $data['goods_count'] = count($goodsInThisFilter);
                }

                if (!$data['goods_count']) {
                    $disabled = true;
                }
                $data['disabled'] = $disabled;
                if (!$disabled) {
                    $key = Filters::getFilterKey($localEfList);
                    if (isset($filtersUrls[$key])) {
                        $data['url'] = $filtersUrls[$key]['url'];
                    } else {
                        $data['url'] = ShopBaseModel::getUrl($sectionClassName, $this->section->id, $this->section->url);
                    }
                }
                if(isset($efList[$num][$code])){
                    $data['checked'] = 1;
                }
            }
        }

        return $schema;
    }

    /**
     * @param $goods
     * @param $addParams
     * @param $goodsDataById
     *
     * @return mixed
     */
    private function filterByAddParams($goods, $addParams, $goodsDataById)
    {
        foreach ($addParams as $param => $paramData) {
            foreach ($goods as $goodId) {
                if (isset($goodsDataById[$goodId])) {
                    $key = null;
                    if ($param === EntityFilters::FILTER_PRICE) {
                        $key = 'price';
                    } else if ($param === EntityFilters::FILTER_WEIGHT) {
                        $key = 'weight';
                    }
                    if (!$key) continue;
                    $val = $goodsDataById[$goodId][$key];
                    if (isset($paramData['min']) && $val <= $paramData['min']) {
                        unset($goods[$goodId]);
                    }
                    if (isset($paramData['max']) && $val >= $paramData['max']) {
                        unset($goods[$goodId]);
                    }
                } else {
                    unset($goods[$goodId]);
                }
            }
        }

        return $goods;
    }

    /**
     * @param $filterFE
     * @param $filteredGoods
     * @param $addParams
     * @param $goodsDataById
     *
     * @return array|mixed
     */
    private function getGoodsForCurrent($filterFE, $filteredGoods, $addParams, $goodsDataById)
    {
        $goodsInCurrent = [];
        if (count($filterFE)) {
            $lists = [];
            $makeIntersect = true;
            foreach ($filterFE as $f) {
                if (!isset($filteredGoods[$f->num][$f->code])) {
                    $makeIntersect = false;
                    break;
                } else {
                    $lists[] = $filteredGoods[$f->num][$f->code];
                }
            }
            if ($makeIntersect) {
                if (count($lists) > 1) {
                    $goodsInCurrent = call_user_func_array('array_intersect_key', $lists);
                } else {
                    $goodsInCurrent = $lists[0];
                }
            }
        }
        if (!count($goodsInCurrent)) {
            foreach ($goodsDataById as $id => $data) {
                $goodsInCurrent[$id] = $id;
            }
        }
        if (count($addParams)) {
            $goodsInCurrent = $this->filterByAddParams($goodsInCurrent, $addParams, $goodsDataById);
        }

        return $goodsInCurrent;
    }

    /**
     * @return mixed
     */
    private function getFiltersSchema()
    {
        if ($this->goodsDataStorage) {
            try {
                $schema = $this->goodsDataStorage->getFilterSchema($this->section->id);
                $goodsByFilter = $this->goodsDataStorage->getGoodsByFilter($this->section->id);
                $goodsDataById = $this->goodsDataStorage->getGoodsData($this->section->id);
                if (!is_null($schema) && !is_null($goodsByFilter) && !is_null($goodsDataById)) {
                    return [$schema, $goodsByFilter, $goodsDataById];
                }
            } catch (\Exception $e) {

            }
        }

        $key = self::KEY_FILTERS_SCHEMA . ':' . $this->section->id;

        //\Cache::forget($key);

        return \Cache::remember($key, 24 * 60, function () {

            $filters = EntityFilters::select(
                'shop.entity_filters.num',
                'shop.entity_filters.code',
                'shop.entity_filters.value',
                'shop.entity_filters.entity_id',
                'shop.goods.final_price as price',
                'shop.goods.weight')
                ->join('shop.goods', function ($join) {
                    $join->on('shop.goods.id', '=', 'shop.entity_filters.entity_id');
                })
                ->where('shop.goods.section_id', '=', $this->section->id)
                ->where('shop.goods.hidden', '=', 0)
                ->where('shop.entity_filters.entity', '=', Goods::getClassName())
                ->orderBy(\DB::raw('null'))
                ->get();

            $schema = [];
            $goodsByFilter = [];
            $goodsDataById = [];
            $priceRange = $weightRange = [
                'min' => 10000000000000,
                'max' => 0,
            ];
            foreach ($this->section->filters as $filter) {
                if ($filter->hidden) continue;

                $schema[$filter->num] = [
                    'code'     => $filter->code,
                    'title'    => $filter->value,
                    'order_by' => $filter->order_by,
                ];
                if ($filter->code === EntityFilters::FILTER_PRICE) {
                    $schema[$filter->num]['view_type'] = 'data_range';
                    $schema[$filter->num]['range'] = [];
                } else if ($filter->code === EntityFilters::FILTER_WEIGHT) {
                    $schema[$filter->num]['view_type'] = 'data_range';
                    $schema[$filter->num]['range'] = [];
                } else {
                    $schema[$filter->num]['view_type'] = 'items_list';
                    $schema[$filter->num]['list'] = [];
                }
            }

            foreach ($filters as $fil) {
                if (!isset($schema[$fil->num])) continue;
                if ($schema[$fil->num]['view_type'] === 'items_list') {
                    $schema[$fil->num]['list'][$fil->code] = [
                        'value' => $fil->value,
                        'code'  => $fil->code,
                    ];
                }

                if ($priceRange['min'] > $fil->price) {
                    $priceRange['min'] = $fil->price;
                }
                if ($priceRange['max'] < $fil->price) {
                    $priceRange['max'] = $fil->price;
                }

                if ($weightRange['min'] > $fil->weigth) {
                    $weightRange['min'] = $fil->weigth;
                }
                if ($weightRange['max'] < $fil->weigth) {
                    $weightRange['max'] = $fil->weigth;
                }

                $goodsByFilter[$fil->num][$fil->code][$fil->entity_id] = $fil->entity_id;
                $goodsDataById[$fil->entity_id] = [
                    'price'  => $fil->price,
                    'weight' => $fil->weight,
                ];
            }

            foreach ($schema as $k => $sh) {
                if ($schema[$k]['code'] === EntityFilters::FILTER_PRICE) {
                    $schema[$k]['range'] = $priceRange;
                } else if ($schema[$k]['code'] === EntityFilters::FILTER_WEIGHT) {
                    $schema[$k]['range'] = $weightRange;
                } else if ($schema[$k]['view_type'] === 'items_list') {
                    if (count($schema[$k]['list']) === 0) {
                        unset($schema[$k]);
                    }
                }
            }

            uasort($schema, function ($a, $b) {
                if ($a['order_by'] === $b['order_by']) return 0;

                return (($a['order_by'] < $b['order_by']) ? 1 : -1);
            });


            return [$schema, $goodsByFilter, $goodsDataById];
        });
    }

    /**
     * Загружает существующие фильтры
     * @return mixed
     */
    private function getExistingFilters()
    {

        if ($this->goodsDataStorage) {
            try {
                $data = $this->goodsDataStorage->getSectionFilters($this->section->id);

                if (!is_null($data)) {
                    return $data;
                }
            } catch (\Exception $e) {

            }
        }

        $key = self::KEY_SECTION_FILTERS . ':' . $this->section->id;

        //\Cache::forget($key);

        return \Cache::remember($key, 24 * 60, function () {
            $filterClassName = Filters::getClassName();
            $existingFilters = EntityFilters::select(['shop.entity_filters.*', 'shop.urls.url'])
                ->join('shop.filters', function ($join) {
                    $join->on('shop.filters.id', '=', 'shop.entity_filters.entity_id');
                })
                ->join('shop.urls', function ($join) use ($filterClassName) {
                    $join->on('shop.filters.id', '=', 'shop.urls.entity_id');
                    $join->where('shop.urls.entity', '=', $filterClassName);
                })
                ->where('shop.filters.section_id', '=', $this->section->id)
                ->where('shop.filters.hidden', '=', 0)
                ->where('shop.entity_filters.entity', '=', $filterClassName)
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
                        'key' => $key,
                        'id'  => $id,
                        'url' => ShopBaseModel::getUrl($filterClassName, $id, array_first($filters)->url),
                    ];
                }
            }

            return $Data;
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