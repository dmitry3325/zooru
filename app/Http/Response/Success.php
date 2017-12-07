<?php
/**
 * Created by PhpStorm.
 * User: dmi
 * Date: 07.12.2017
 * Time: 14:29
 */

namespace App\Http\Response;

class Success extends AbstractResponse
{
    protected $code = 200;
    protected $result = true;
}