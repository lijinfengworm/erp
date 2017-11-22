<?php

/**
 * Created by PhpStorm.
 * User: wp
 * Date: 15/3/31
 * Time: 下午1:36
 */
class tradeServiceResponse
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function hasError()
    {
        if ($this->data['status'] != 200) {
            return true;
        }
        return false;
    }

    public function getStatusCode()
    {
        return $this->data['status'];
    }

    public function getError()
    {
        if ($this->data['status'] != 200) {
            return $this->data['msg'];
        }
        return false;
    }

    public function getMsg()
    {
        return $this->data['msg'];
    }

    public function getValue($key)
    {
        return $this->data['data'][$key];
    }

    public function getData()
    {
        return $this->data;
    }
}