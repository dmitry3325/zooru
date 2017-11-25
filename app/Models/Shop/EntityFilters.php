<?php

namespace App\Models\Shop;

use App\Models\BaseModel;

/**
 * Class EntityFilters
 * @package App\Models\Shop
 */
class EntityFilters extends BaseModel
{
    protected $table   = 'shop.entity_filters';
    protected $guarded = ['id'];

    /**
     * @param $value
     *
     * @return int
     */
    public static function getCode($value)
    {
        return crc32(mb_strtolower(trim($value)));
    }
}
