<?php

namespace Vivait\TenantBundle\Provider;

use Vivait\TenantBundle\Model\Tenant;

interface TenantProvider {

    /**
     * @return Tenant[]
     */
    public function loadTenants();
}
