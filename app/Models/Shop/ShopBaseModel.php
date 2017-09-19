<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 24.07.17
 * Time: 18:40
 */

namespace App\Models\Shop;

use App\Classes\Traits\Shop\QueryFilterTrait;
use App\Models\Photos\Photos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class ShopBaseModel extends Model
{
    const DB = 'shop';
    protected static $class_name;
    protected static $class_full_name;
    protected static $fields;
    protected $fillable = [];

    //запилить хлебные крошки
    protected static $_breadcrumbs = [];

    public static function checkEntity($entity)
    {
        return in_array($entity, ['Sections', 'Filters', 'Goods', 'HtmlPages']);
    }

    public static function getTableName($onlyTable = false)
    {
        $name = with(new static)->getTable();
        if ($onlyTable) {
            $name = str_replace(self::DB . '.', '', $name);
        }
        return $name;
    }

    public static function getClassName($full = false)
    {
        if (self::$class_name && !$full) {
            return self::$class_name;
        }
        if (self::$class_full_name && $full) {
            return self::$class_full_name;
        }

        self::$class_full_name = get_called_class();
        $arr                   = explode('\\', self::$class_full_name);
        self::$class_name      = end($arr);
        return ($full) ? self::$class_full_name : self::$class_name;
    }

    /**
     * Url functions
     */
    public function url()
    {
        return $this->hasOne(Urls::class, 'entity_id')->where('entity', '=', self::getClassName());
    }

    public function getUrlAttribute()
    {
        if (isset($this->attributes['url'])) {
            return $this->attributes['url'];
        }
        else {
            if (isset($this->relations['url']->id)) {
                $this->attributes['url'] = $this->relations['url']->url;
            }
            else {
                $url                     = $this->url()->first();
                $this->attributes['url'] = ($url) ? $url->url : '';
            }
            return $this->attributes['url'];
        }
    }

    /**
     * Metadata functions
     */
    public function metadata()
    {
        return $this->hasMany(ShopMetadata::class, 'entity_id')->where('entity', '=', self::getClassName());
    }

    public function getMetaData($fields = [])
    {
        $q = $this->metadata();
        if (count($fields)) {
            $q->whereIn('key', $fields);
        }
        $meta = $q->get();
        foreach ($meta as $m) {
            $this->{$m->key} = $m->value;
        }
        return $this;
    }

    public function getPhotos($ext = 'jpeg')
    {
        $ph     = ($this->attributes['photos']) ? json_decode($this->attributes['photos'], true) : [];
        $photos = [];
        foreach (Photos::$sizes as $size => $photo) {
            foreach ($ph as $num) {
                $this->getPhotoUrl($size, $num, $ext);
            }
        }
        return $photos;
    }

    public function getPhotoUrl($size, $num = 1, $ext = 'jpg')
    {
        if ($this->url) {
            return Photos::PIC_PATH . '/' . $size . '/' . $this->attributes['url'] . (($num !== 1) ? '_' . $num : '') . '.' . $ext;
        }
        else {
            return Photos::PIC_PATH . '/' . $size . '/' . self::getTableName(true) . '/' . $this->id . (($num !== 1) ? '_' . $num : '') . '.' . $ext;
        }
    }

    public function getBreadcrumb(){
        //раздел и не раздел

    }
}