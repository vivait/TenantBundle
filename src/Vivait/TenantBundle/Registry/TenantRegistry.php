<?php

namespace Vivait\TenantBundle\Registry;

use Vivait\TenantBundle\Model;
use Vivait\TenantBundle\Model\Tenant;

class TenantRegistry {
    /**
     * @var Tenant[]
     */
    private $tenants = [];

    /**
     * @var Tenant
     */
    private $current;

    /**
     * @param Tenant[] $tenants
     */
    function __construct( array $tenants = array() ) {
        $this->addAll($tenants);
    }

    /**
     * @param Tenant|string $tenant
     * @return bool
     */
    public function contains($tenant) {
        if ($tenant instanceOf Tenant) {
            return (isset($this->tenants[$tenant->getKey()]));
        }

        return (isset($this->tenants[$tenant]));
    }

    /**
     * @param Tenant[] $tenants
     * @return $this
     */
    public function addAll(array $tenants) {
        foreach ($tenants as $tenant) {
            if (!($tenant instanceOf Tenant)) {
                throw new \InvalidArgumentException('Invalid Tenant added to registry');
            }

            $this->tenants[$tenant->getKey()] = $tenant;
        }

        return $this;
    }

    /**
     * @param Tenant $tenant
     * @return $this
     */
    public function add(Tenant $tenant) {
        if (!$this->contains($tenant)) {
            $this->tenants[$tenant->getKey()] = $tenant;
        }

        return $this;
    }

    /**
     * Get a tenant based on their key
     * @param string $key The tenant key
     * @return Tenant
     */
    public function get($key) {
        if (!$this->contains($key)) {
            throw new \OutOfBoundsException(sprintf('No tenant found in registry for key "%s"', $key));
        }

        return $this->tenants[$key];
    }

    /**
     * Gets all tenants
     * @return Model\Tenant[]
     */
    public function getAll() {
        return $this->tenants;
    }

    /**
     * Gets current
     * @return Tenant
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Sets current
     * @param Tenant $current
     * @return $this
     */
    public function setCurrent( Tenant $current )
    {
        $this->current = $current;

        return $this;
    }
}
