<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 24.07.17
 * Time: 18:40
 */

namespace App\Models\Shop;

use App\Models\Photos\Photos;
use Illuminate\Database\Eloquent\Model;

class ShopBaseModel extends Model
{
    //TODO ALL FIELDS GUARDED
    const DB = 'shop';
    protected static $class_name;
    protected static $class_full_name;
    protected static $fields;
    protected $fillable = [];

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
        return $this->hasOne(Urls::class, 'entity_id')->where('entity', self::getClassName());
    }

    public function sectionUrl(){
        return $this->hasManyThrough(Urls::class, Sections::class, 'entity_id', 'id','entity_id', 'id');
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
        return $this->hasMany(ShopMetadata::class, 'entity_id')->where('entity', self::getClassName());
    }

    public function getMetaData(array $fields = [])
    {
        $q = $this->metadata();
        if (count($fields)) {
            $q->whereIn('key', $fields);
        }

        $meta = $q->get();

        $result = [];

        foreach ($meta as $m) {
            $result[$m->key] = $m->value;
        }

        if(count($result) === 1){
            return array_shift($result);
        }

        return $result;
    }

    /**
     * Photos functions
     */
    public function photos()
    {
        return $this->hasMany(Photos::class, 'entity_id')->where('entity', '=', self::getClassName());
    }

    public function getPhotosAttribute()
    {
        return $this->getPhotos('jpeg');
    }

    public function getPhotos($ext = 'jpeg')
    {
        $ph     = ($this->attributes['photos']) ? json_decode($this->attributes['photos'], true) : [];
        $photos = [];
        foreach (Photos::$sizes as $size => $photo) {
            foreach ($ph as $num) {
                $photos[$size][] = env('PHOTO_SERVER') . $this->getPhotoUrl($size, $num, $ext);
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

    public function getSizedPhotos($size){
        $photos = array_get($this->photos, $size);
        unset($photos[0]);
        return $photos;
    }

    //TODO - это не правильно сделано, сделай так чтобы крошки были не статическим, а обычным свойством
    public function getBreadcrumbs(){
        if(!empty(self::$_breadcrumbs)){
            return self::$_breadcrumbs;
        }

        $breadcrumbs = [];

        $breadcrumbs['Главная'] = '/';

        if($this->parentSection && $this->parentSection->url){
            if($this->parentSection->parentSection && $this->parentSection->parentSection->url){
                $name = $this->parentSection->parentSection->getH1Title();
                $breadcrumbs[$name] = '/' . $this->parentSection->parentSection->url;
            }

            $name = $this->parentSection->getH1Title();
            $breadcrumbs[$name] = '/' . $this->parentSection->url;
        }

        $name = $this->getH1Title();
        $breadcrumbs[$name] = null;



        self::$_breadcrumbs = $breadcrumbs;

        //костыль для класснейма
        self::$class_name = 'Goods';

        return self::$_breadcrumbs;
    }

    public function parentSection()
    {
        return $this->hasOne(Sections::class, 'id', 'section_id');
    }

    public function getH1Title(){
        return $this->h1_title ? $this->h1_title : null;
    }


    public function getFirstPhoto($size){
        return array_get($this->photos, $size.'.0');
    }

    public function section(){
        return $this->belongsTo(Sections::class, 'section_id');
    }

    public function filters(){
        return $this->hasMany(EntityFilters::class, 'entity_id')->where('entity', '=', self::getClassName());
    }
}