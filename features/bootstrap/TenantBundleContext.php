<?php

use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Vivait\TenantBundle\Model\Tenant;

class TenantBundleContext implements Context, KernelAwareContext {
    use KernelDictionary;

    /**
     * @Transform :tenant
     */
    public function castTenant($tenant)
    {
        return new Tenant($tenant);
    }

    /**
     * @When I have a tenant :tenant
     * @param Tenant $tenant
     */
    public function iHaveATenant( Tenant $tenant ) {
        $registry = $this->getContainer()->get('vivait_tenant.registry');
        $registry->add($tenant);
    }
}
