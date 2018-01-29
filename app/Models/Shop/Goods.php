<?php

namespace App\Models\Shop;


/**
 * Class Goods
 * @package App\Models\Shop
 * @property int $parent_id
 * @property int $section_id
 * @property string $type
 * @property string $title
 * @property string $h1_title
 * @property string $order_title
 * @property string $path_title
 * @property int $orderby
 * @property int $orderby_manual
 * @property int $orderby_auto
 * @property int $ignore_orderby_auto
 * @property int $hidden
 * @property int $stop_sale
 * @property int $cancelled
 * @property int $manid
 * @property string $articul
 * @property int $cost
 * @property int $price
 * @property int $final_price
 * @property int $price_opt1
 * @property int $price_opt2
 * @property int $discount
 * @property string $filter_1
 * @property int $filter_1_id
 * @property string $filter_2
 * @property int $filter_2_id
 * @property string $filter_3
 * @property int $filter_3_id
 * @property string $filter_4
 * @property int $filter_4_id
 * @property string $filter_5
 * @property int $filter_5_id
 * @property string $filter_6
 * @property int $filter_6_id
 * @property string $filter_7
 * @property int $filter_7_id
 * @property string $filter_8
 * @property int $filter_8_id
 
 * @property int $tarif
 * @property int $tarif_discount
 * @property int $min_qty
 * @property int $ignore_min_qty
 * @property int $weight
 * @property int $final_price_round_method
 * @property int $nds
 * @property int $show_qty
 * @property int $show_buy
 * @property int $img_new
 * @property int $img_promo
 * @property int $comments_avg
 * @property int $comments_num
 * @property int $picture_id
 * @property int $photos
 * @property int $first_inventory
 * @property int $not_for_ya_market
 * @property int $not_for_site_map
 *
 */
class Goods extends ShopBaseModel
{
    protected $table = 'shop.goods';

    const GOOD_TYPE_LINE     = 'line';
    const GOOD_TYPE_SUB_LINE = 'sub_line';
    const GOOD_TYPE_COMPLEX  = 'complex';

    const PRICE_ROUND_METHOD_DEFAULT = 0;
    const PRICE_ROUND_METHOD_MINUS_1 = 'minus_1';
    const PRICE_ROUND_METHOD_INTEGER = 'integer';

    public function getPrice()
    {
        return $this->price;
    }

    public function getFormatedPrice()
    {
        return number_format($this->getPrice(), 0, ',', ' ') . '&#8381;';
    }

    //TODO поправить на sectiion_id
    public function sections(){
        return $this->belongsTo(Sections::class, 'parent_id');
    }

    public function notForSale(){
        return $this->stop_sale;
    }

    public function getPriceBlock(){
        if(!$this->parent_id) {
            return Goods::where('parent_id', $this->id)
                ->orWhere('id', $this->id)
                ->get();
        }

        return Goods::where('parent_id', $this->parent_id)
            ->orWhere('id', $this->parent_id)
            ->get();

        //TODO выбрать ток нужные столбцы селектом
    }

    public function getCartArray(){
        return json_encode([
            'id' => $this->id,
            'title' => $this->getH1Title(),
            'price' => $this->getPrice(),
            'maxItems' => 5,
            'photo' =>  $this->getFirstPhoto('thumb'),
        ]);
    }

    public function getAssociatedList(){
        return Goods::all()->take(4);
    }

}
