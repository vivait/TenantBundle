<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    private $tenant = null;

    /**
     * @return array<Symfony\Component\HttpKernel\Bundle\Bundle\Bundle>
     */
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Vivait\TenantBundle\VivaitTenantBundle()
        );
    }

    /**
     * @return null
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        try {
            $loader->load(__DIR__.'/config/tenant/'.$this->getEnvironment().'.yml');
        }
        catch (InvalidArgumentException $e) {
            $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
        }
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        // Locate the tenant
        $tenant = \Vivait\TenantBundle\Locator\HostnameLocator::getTenantFromRequest( $request );

        // Get the tenant from the registry


        return parent::handle( $request, $type, $catch );
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir().'/VivaitTenantBundle/cache';
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return sys_get_temp_dir().'/VivaitTenantBundle/logs';
    }
}
