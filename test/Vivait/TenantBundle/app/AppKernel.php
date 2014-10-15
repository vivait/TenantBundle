<?php

namespace test\Vivait\TenantBundle\app;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Vivait\TenantBundle\Locator\HostnameLocator;

class AppKernel extends Kernel
{
    /**
     * @return \Symfony\Component\HttpKernel\Bundle\Bundle[]
     */
    public function registerBundles()
    {
        return array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Vivait\TenantBundle\VivaitTenantBundle()
        );
    }

    private $tenantRegistry;

    protected function initializeContainer()
    {
        parent::initializeContainer();

        // Inject the registry to the container
        $this->getContainer()->set(
            'vivait_tenant.registry',
            $this->getTenantRegistry()
        );
    }

    private function getTenantRegistry() {
        if ($this->tenantRegistry === null) {
            $provider = new \Vivait\TenantBundle\Provider\ConfigProvider( $this->getRootDir() . '/config/' );

            $this->tenantRegistry = new \Vivait\TenantBundle\Registry\TenantRegistry(
                $provider->loadTenants()
            );
        }

        return $this->tenantRegistry;
    }

    public function handle(
        \Symfony\Component\HttpFoundation\Request $request,
        $type = \Symfony\Component\HttpKernel\HttpKernelInterface::MASTER_REQUEST,
        $catch = true
    ) {
        if (false === $this->booted) {
            // Find and set the current tenant
            $tenant = HostnameLocator::getTenantFromRequest( $request );
            $this->getTenantRegistry()->setCurrent( $tenant );

            // Change the environment to the tenant's environment
            $this->environment = 'tenant_' . $tenant;

            $this->boot();
        }

        return parent::handle( $request, $type, $catch );
    }


    /**
     * @return null
     */
    public function registerContainerConfiguration( LoaderInterface $loader )
    {
        $loader->load( __DIR__ . '/config/config_' . $this->getEnvironment() . '.yml' );
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir() . '/VivaitTenantBundle/cache';
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return sys_get_temp_dir() . '/VivaitTenantBundle/logs';
    }
}
