<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Urls extends Model
{
    protected $table = 'shop.urls';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @param $entity
     * @param $entity_id
     * @param $url
     *
     * @return array
     */
    public static function createNew($entity, $entity_id, $url)
    {
        $result = [
            'result' => false,
        ];

        if ($url && self::where('url', '=', $url)->first()) {
            $result['errors'][] = 'Такой URL уже существует на вашем сайте! Адрес должен быть уникален!';

            return $result;
        }

        $result['result'] = self::updateOrCreate([
            'entity'    => $entity,
            'entity_id' => $entity_id,
        ], [
            'entity'    => $entity,
            'entity_id' => $entity_id,
            'url'       => $url,
        ]);

        return $result;
    }

    /**
     * @param $url
     *
     * @return Model|null|static
     */
    public static function getEntityByUrl($url)
    {
        $U = null;
        if (preg_match('#([a-z]+)\/([0-9]+)#', $url, $matches)) {
            $entity = ucfirst($matches[1]);
            $entity_id = intval($matches[2]);

            if (ShopBaseModel::checkEntity($entity)) {
                $U = self::where('entity', $entity)
                    ->where('entity_id', $entity_id)
                    ->first();
            }

        } elseif (preg_match('#([a-z0-9\-_]+)#', $url, $matches)) {
            $url = $matches[1];
            $U = self::where('url', $url)->first();
        }

        return $U;
    }

    /**
     * @param $xxx
     *
     * @return mixed|null|string|string[]
     */
    public static function generateUrlFromText($xxx)
    {
        $xxx = mb_strtolower($xxx);
        $sf = [
            '/ё/',
            '/а/',
            '/б/',
            '/в/',
            '/г/',
            '/д/',
            '/е/',
            '/ж/',
            '/з/',
            '/и/',
            '/й/',
            '/к/',
            '/л/',
            '/м/',
            '/н/',
            '/о/',
            '/п/',
            '/р/',
            '/с/',
            '/т/',
            '/у/',
            '/ф/',
            '/х/',
            '/ц/',
            '/ч/',
            '/ш/',
            '/щ/',
            '/ъ/',
            '/ь/',
            '/ы/',
            '/э/',
            '/ю/',
            '/я/',
        ];
        $st = [
            'e',
            'a',
            'b',
            'v',
            'g',
            'd',
            'e',
            'j',
            'z',
            'i',
            'y',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'r',
            's',
            't',
            'u',
            'f',
            'h',
            'c',
            'ch',
            'sh',
            'sch',
            '',
            '',
            'y',
            'e',
            'yu',
            'ya',
        ];
        $xxx = preg_replace($sf, $st, $xxx);
        $xxx = preg_replace($sf, $st, $xxx);
        $xxx = preg_replace('/[^a-z0-9]+/', '_', $xxx);
        $xxx = preg_replace('/[_]+/', '_', $xxx);
        $xxx = preg_replace('/_\z/', '', $xxx);

        return $xxx;
    }
}
