<?php

namespace Vivait\TenantBundle\Model;

class Tenant {
    /**
     * @var string
     */
    private $key;

    function __construct( $key ) {
        $this->key = $key;
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
