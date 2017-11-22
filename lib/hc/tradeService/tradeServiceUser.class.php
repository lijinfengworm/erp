<?php
/**
 * Created by PhpStorm.
 * User: wp
 * Date: 15/3/24
 * Time: 下午9:14
 */

class tradeServiceUser {

    private $attributeHolder;
    public function __construct($params)
    {
        $this->attributeHolder = new sfParameterHolder();
        $this->attributeHolder->add($params);
    }
    /**
     * Retrieves the attributes holder.
     *
     * @return sfParameterHolder The attribute holder
     */
    public function getAttributeHolder()
    {
        return $this->attributeHolder;
    }

    /**
     * Retrieves an attribute from the current request.
     *
     * @param  string $name     Attribute name
     * @param  string $default  Default attribute value
     *
     * @return mixed An attribute value
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attributeHolder->get($name, $default);
    }

    /**
     * Indicates whether or not an attribute exist for the current request.
     *
     * @param  string $name  Attribute name
     *
     * @return bool true, if the attribute exists otherwise false
     */
    public function hasAttribute($name)
    {
        return $this->attributeHolder->has($name);
    }
} 