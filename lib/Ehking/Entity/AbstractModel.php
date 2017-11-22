<?php
/**
 * 
 *  
 * PHP Version 5
 *
 * @category  Class
 * @file      Model.php
 * @package Ehking/Entity
 * @author    chao.ma <chao.ma@ehking.com>

 */

//namespace Ehking/Entity;


abstract class AbstractModel {

    public function __toArray()
    {
        return get_object_vars($this);
    }
} 