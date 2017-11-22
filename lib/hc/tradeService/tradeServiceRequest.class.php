<?php
/**
 *
 * User: wp
 * Date: 15/3/24
 * Time: 下午9:13
 */

class tradeServiceRequest {

    private $parameterHolder;
    private $getParameterHolder;
    private $postParameterHolder;
    private $files;
    public function __construct($getParams,$postParams,$fileParams)
    {
        $this->parameterHolder = new sfParameterHolder();
        $this->parameterHolder->add($getParams);
        $this->parameterHolder->add($postParams);

        $this->getParameterHolder = new sfParameterHolder();
        $this->getParameterHolder->add($getParams);

        $this->postParameterHolder = new sfParameterHolder();
        $this->postParameterHolder->add($postParams);

        $this->files = $fileParams;
        //$this->parameterHolder->add($fileParams);
    }
    /**
     * Retrieves a parameter for the current request.
     *
     * @param string $name     Parameter name
     * @param string $default  Parameter default value
     *
     */
    public function getParameter($name, $default = null)
    {
        return $this->parameterHolder->get($name, $default);
    }

    /**
     * Indicates whether or not a parameter exist for the current request.
     *
     * @param  string $name  Parameter name
     *
     * @return bool true, if the parameter exists otherwise false
     */
    public function hasParameter($name)
    {
        return $this->parameterHolder->has($name);
    }

    /**
     * Sets a parameter for the current request.
     *
     * @param string $name   Parameter name
     * @param string $value  Parameter value
     *
     */
    public function setParameter($name, $value)
    {
        $this->parameterHolder->set($name, $value);
    }
    /**
     * Retrieves a parameter for the current request.
     *
     * @param string $name     Parameter name
     * @param string $default  Parameter default value
     *
     */
    public function getGetParameter($name, $default = null)
    {
        return $this->getParameterHolder->get($name, $default);
    }

    /**
     * Indicates whether or not a parameter exist for the current request.
     *
     * @param  string $name  Parameter name
     *
     * @return bool true, if the parameter exists otherwise false
     */
    public function hasGetParameter($name)
    {
        return $this->getParameterHolder->has($name);
    }

    /**
     * Sets a parameter for the current request.
     *
     * @param string $name   Parameter name
     * @param string $value  Parameter value
     *
     */
    public function setGetParameter($name, $value)
    {
        $this->getParameterHolder->set($name, $value);
    }
    /**
     * Retrieves a parameter for the current request.
     *
     * @param string $name     Parameter name
     * @param string $default  Parameter default value
     *
     */
    public function getPostParameter($name, $default = null)
    {
        return $this->postParameterHolder->get($name, $default);
    }

    /**
     * Indicates whether or not a parameter exist for the current request.
     *
     * @param  string $name  Parameter name
     *
     * @return bool true, if the parameter exists otherwise false
     */
    public function hasPostParameter($name)
    {
        return $this->postParameterHolder->has($name);
    }

    /**
     * Sets a parameter for the current request.
     *
     * @param string $name   Parameter name
     * @param string $value  Parameter value
     *
     */
    public function setPostParameter($name, $value)
    {
        $this->postParameterHolder->set($name, $value);
    }

    public function getFiles($key)
    {
        return isset($this->fixedFileArray[$key]) ? $this->fixedFileArray[$key] : array();
    }
} 