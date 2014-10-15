<?php

namespace Vivait\TenantBundle;

use Vivait\TenantBundle\Locator\TenantLocator;
use Vivait\TenantBundle\Provider\TenantProvider;
use Vivait\TenantBundle\Registry\TenantRegistry;

class Bootstrap {

    public static function createRegistry(TenantProvider $provider, TenantLocator $locator) {
        // Populate the tenants from the provider
        $registry = new TenantRegistry($provider->loadTenants());

        $registry->setCurrent( $registry->get($locator->getTenant()) );

        return $registry;
    }

}
