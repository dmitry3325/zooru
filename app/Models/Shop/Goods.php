<?php

namespace App\Models\Shop;

class Goods extends ShopBaseModel
{
    protected $table = 'shop.goods';

    const GOOD_TYPE_LINE     = 'line';
    const GOOD_TYPE_SUB_LINE = 'sub_line';
    const GOOD_TYPE_COMPLEX  = 'complex';

    const PRICE_ROUND_METHOD_DEFAULT = 0;
    const PRICE_ROUND_METHOD_MINUS_1 = 'minus_1';
    const PRICE_ROUND_METHOD_INTEGER = 'integer';

}
