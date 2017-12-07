<?php
/**
 * Created by PhpStorm.
 * User: dmi
 * Date: 07.12.2017
 * Time: 14:31
 */

namespace App\Http\Response;

abstract class AbstractResponse
{
    protected $code;
    protected $result;
    protected $message;
    protected $data;

    public function __construct(string $message = null)
    {
        $this->setMessage($message);
    }

    public function setBody(array $body): void
    {
        $this->body = $body;
    }

    private function setMessage(string $message):void
    {
        $this->message = $message;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function setResult(bool $result): void
    {
        $this->body = $result;
    }

    public function __toString()
    {
        return json_encode(get_object_vars($this));
    }
}