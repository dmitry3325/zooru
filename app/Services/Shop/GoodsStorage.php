<?php

namespace App\Services\Shop;

use Illuminate\Support\Facades\Redis;

class GoodsStorage
{
    /**
     * Товары по фильтру
     */
    const FILTERED_GOODS = 'goods_by_filter';

    /**
     * Фильтры разделов
     */
    const SECTION_FILTERS = 'section_filters';

    /**
     * Схема фильтров
     */
    const SECTION_FILTER_SCHEMA = 'section_filter_schema';

    /**
     * Данные для фильтрации вес, цена
     */
    const GOODS_IMPORTANT_DATA = 'goods_important_data';

    /**
     * @var \Illuminate\Redis\Connections\Connection
     */
    protected $sectionFiltersStorage;

    /**
     * @var \Illuminate\Redis\Connections\Connection
     */
    protected $filterGoodsStorage;

    /**
     * @var \Illuminate\Redis\Connections\Connection
     */
    protected $goodsDataStorage;

    /**
     * @var \Illuminate\Redis\Connections\Connection
     */
    protected $sectionSchemaStorage;

    public function __construct()
    {
        $this->sectionFiltersStorage = Redis::connection(self::SECTION_FILTERS);
        $this->filterGoodsStorage = Redis::connection(self::FILTERED_GOODS);
        $this->goodsDataStorage = Redis::connection(self::GOODS_IMPORTANT_DATA);
        $this->sectionSchemaStorage = Redis::connection(self::SECTION_FILTER_SCHEMA);
    }

    /**
     * @param int   $section_id
     * @param array $goods
     */
    public function setGoodsByFilter(int $section_id, array $goods)
    {
        $this->filterGoodsStorage->set($section_id, json_encode($goods));
    }

    /**
     * @param int $section_id
     *
     * @return array
     */
    public function getGoodsByFilter(int $section_id)
    {
        $list = $this->filterGoodsStorage->get($section_id);

        return ($list) ? json_decode($list, true) : null;
    }

    /**
     * @param int   $section_id
     * @param array $data
     */
    public function setSectionFilters(int $section_id, array $data)
    {
        $this->sectionFiltersStorage->set($section_id, json_encode($data));
    }

    /**
     * @param int $section_id
     *
     * @return array
     */
    public function getSectionFilters(int $section_id)
    {
        $list = $this->sectionFiltersStorage->get($section_id);

        return ($list) ? json_decode($list, true) : null;
    }

    /**
     * @param int   $section_id
     * @param array $schema
     */
    public function setFilterSchema(int $section_id, array $schema)
    {
        $this->sectionSchemaStorage->set($section_id, json_encode($schema));
    }

    /**
     * @param int $section_id
     *
     * @return array
     */
    public function getFilterSchema(int $section_id)
    {
        $list = $this->sectionSchemaStorage->get($section_id);

        return ($list) ? json_decode($list, true) : null;
    }

    /**
     * @param int   $section_id
     * @param array $goods
     */
    public function setGoodsData(int $section_id, array $goods)
    {
        $this->goodsDataStorage->set($section_id, json_encode($goods));
    }

    /**
     * @param int $section_id
     *
     * @return array
     */
    public function getGoodsData(int $section_id)
    {
        $list = $this->goodsDataStorage->get($section_id);

        return ($list) ? json_decode($list, true) : null;
    }
}