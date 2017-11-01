<?php

namespace App\Models\Photos;

use App\Models\BaseModel;

class Photos extends Basemodel
{
    protected $table = 'photos.photos';

    protected $guarded = ['id'];

    const PIC_PATH    = '/p';
    const STORAGE_DIR = '/photos';

    public static $sizes = [
        'thumb'  => [
            'width'         => 80,
            'height'        => 60,
            'no_water_mark' => true,
        ],
        'small'  => [
            'width'  => 160,
            'height' => 120,
            'no_water_mark' => true,
        ],
        'medium' => [
            'width'  => 400,
            'height' => 400,
        ],
        'big'    => [
            'width'  => 800,
            'height' => 800,
        ],
    ];

}
