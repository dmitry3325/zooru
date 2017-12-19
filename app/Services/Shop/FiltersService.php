<?php

namespace App\Services\Shop;

use App\Models\Shop\Filters;
use App\Models\Shop\Sections;
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
    public function getFiltersStructure()
    {
        $data = $this->getFromRedis();
        if (!$data) {
            // $data = $this->getFromDB();
        }

        return $data;
    }

    private function getFromRedis()
    {
        $allFilters = $this->getFilterGoodsFromRedis('all_data');
        if (!$allFilters) return null;

        if ($this->filter) {
            $key = $this->filter->getKey();
            $filterData = $this->getFilterGoodsFromRedis($key);

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
            'goods'   => [],
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