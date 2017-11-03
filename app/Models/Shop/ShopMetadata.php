<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;

class ShopMetadata extends Model
{
    protected $table = 'shop.shop_metadata';

    protected $guarded = ['id'];

}
