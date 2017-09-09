<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Vendors extends Model
{
    protected $table = 'shop.vendors';

    protected $fillable = ['title', 'orderby', 'picture_id', 'photos'];

    const FIELD_TYPE_INT      = 'int';
    const FIELD_TYPE_STRING   = 'input';
    const FIELD_TYPE_DOUBLE   = 'double';
    const FIELD_TYPE_TEXT     = 'textarea';
    const FIELD_TYPE_CHECKBOX = 'checkbox';
    const FIELD_TYPE_RADIO    = 'radio';
    const FIELD_TYPE_SELECT   = 'select';
    const FIELD_TYPE_DATE     = 'date';
    const FIELD_TYPE_OBJECT   = 'obejct';

    protected static $commonFields = [
        'id'                => [
            'title'    => 'ID',
            'type'     => self::FIELD_TYPE_INT,
            'disabled' => true,
            'baseField' => true
        ],
        'title'             => [
            'title' => 'Короткое наименование',
            'type'  => self::FIELD_TYPE_STRING,
            'baseField' => true
        ],
        'orderby'           => [
            'title' => 'Приоритет',
            'type'  => self::FIELD_TYPE_INT,
            'baseField' => true
        ],
        'picture_id'        => [
            'title' => '№ картинки',
            'type'  => self::FIELD_TYPE_INT,
            'baseField' => true
        ],
        'photos'            => [
            'title'  => 'Изображения',
            'type'   => self::FIELD_TYPE_OBJECT,
            'entity' => 'photos',
            'baseField' => true
        ]
    ];

    public static function getAllFields()
    {
        return self::$commonFields;
    }

}
