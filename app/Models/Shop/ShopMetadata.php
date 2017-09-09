<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;

class ShopMetadata extends Model
{
    protected $table = 'shop.shop_metadata';

    protected $guarded = ['id'];

    protected static $fields = [
        'html_title'       => [
            'title' => 'HTML title',
            'type'  => ShopBaseModel::FIELD_TYPE_STRING,
        ],
        'html_keywords'    => [
            'title' => 'KEYWORDS',
            'type'  => ShopBaseModel::FIELD_TYPE_TEXT,
        ],
        'html_description' => [
            'title' => 'DESCRIPTION',
            'type'  => ShopBaseModel::FIELD_TYPE_TEXT,
        ],
        'big_description' => [
            'title' => 'Полное описание',
            'type'  => ShopBaseModel::FIELD_TYPE_TEXT,
        ],
        'links_title' => [
            'title' => 'Заголовок к связанным товарам',
            'type'  => ShopBaseModel::FIELD_TYPE_STRING,
        ],
        'links_list' => [
            'title' => 'Список связаных товаров',
            'type'  => ShopBaseModel::FIELD_TYPE_STRING,
            'description'=>''
        ],
        'components' => [
            'title' => 'Компоненты товара',
            'type'  => ShopBaseModel::FIELD_TYPE_STRING,
        ],
    ];

    public static function getAllFields()
    {
        for($i = 1; $i<=Filters::COUNT; $i++){
            self::$fields['filter_'.$i.'_use_in_autocreate'] = [
                'title' => 'Создавать автоматически',
                'type'  => ShopBaseModel::FIELD_TYPE_CHECKBOX,
            ];
        }
        return self::$fields;
    }

    public static function saveMetadata($entity, $id, array $data)
    {
        if (!$entity || !$id || !count($data)) {
            return false;
        }

        foreach ($data as $key => $val) {
            $data = [
                'entity'    => $entity,
                'entity_id' => $id,
                'key'       => $key,
            ];
            self::updateOrCreate($data, array_merge($data, ['value' => $val]));
        }

        return [
            'result' => true,
        ];
    }
}
