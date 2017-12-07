<?php
/**
 * Created by PhpStorm.
 * User: dmi
 * Date: 07.12.2017
 * Time: 14:52
 */

namespace App\Http\Response;

class Error extends AbstractResponse
{
    protected $code = 500;
    protected $result = false;
}