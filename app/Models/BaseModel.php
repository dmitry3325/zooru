<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected static $class_name;
    protected static $class_full_name;

    /**
     * @param bool $onlyTable
     *
     * @return mixed
     */
    public static function getTableName($onlyTable = false)
    {
        $table = with(new static)->getTable();
        if($onlyTable){
            $tt = explode('.', $table);
            if(count($tt) === 2){
                $table = $tt[1];
            }
        }
        return $table;
    }

    /**
     * @param bool $full
     *
     * @return string
     */
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
}
