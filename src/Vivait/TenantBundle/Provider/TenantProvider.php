<?php

namespace Vivait\TenantBundle\Provider;

interface TenantProvider {

    /**
     * @return \Vivait\TenantBundle\Model\Tenant[]
     */
    public function loadTenants();
}
