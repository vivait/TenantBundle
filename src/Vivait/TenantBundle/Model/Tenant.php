<?php

namespace Vivait\TenantBundle\Model;

class Tenant {
    /**
     * @var string
     */
    private $key;

    /**
     * @var
     */
    private $name;

    /**
     * @var array
     */
    private $attributes = array();

    /**
     * @param string $key The unique identifier for the tenant
     */
    function __construct( $key = null) {
        $this->key = $key;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @param $attribute
     * @return $this
     */
    public function addAttribute($attribute)
    {
        $this->attributes[] = $attribute;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets key
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * Sets key
     * @param string $key
     * @return $this
     */
    public function setKey( $key ) {
        $this->key = $key;

        return $this;
    }
}
