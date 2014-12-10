<?php

namespace Vivait\TenantBundle\Kernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Vivait\TenantBundle\Registry\TenantRegistry;

abstract class TenantKernel extends Kernel {
    /**
     * @var TenantRegistry
     */
    private $tenantRegistry;

    /**
     * @var bool Enable tenanting?
     */
    public $enableTenanting;

    public function __construct($environment, $debug)
    {
        $this->enableTenanting = !(bool)$debug;

        parent::__construct($environment, $debug);
    }

    protected function initializeContainer()
    {
        parent::initializeContainer();

        // Inject the registry to the container
        $this->getContainer()->set(
            'vivait_tenant.registry',
            $this->getTenantRegistry()
        );
    }

    /**
     * Provides an array of all Tenants
     *
     * For example:
     * <code>
     * <?php
     * $configProvider = new \Vivait\TenantBundle\Provider\ConfigProvider( __DIR__ . '/config/' );
     * return $configProvider->loadTenants();
     * ?>
     * </code>
     * @return \Vivait\TenantBundle\Model\Tenant[]
     */
    protected abstract function getAllTenants();

    protected function getTenantRegistry() {
        if ($this->tenantRegistry === null) {
            $tenants = $this->getAllTenants();

            $this->tenantRegistry = new TenantRegistry(
                $tenants
            );
        }

        return $this->tenantRegistry;
    }

    /**
     * Provides the current tenant's key
     * @param Request $request
     * @return string The current tenant's key
     */
    protected abstract function getCurrentTenantKey(Request $request);

    public function handle(
        Request $request,
        $type = HttpKernelInterface::MASTER_REQUEST,
        $catch = true
    ) {
        if (false === $this->booted && $this->enableTenanting) {
            // Find and set the current tenant
            try {
                $tenant = $this->getCurrentTenantKey( $request );
                $this->getTenantRegistry()->setCurrent( $tenant );

                // Change the environment to the tenant's environment
                $this->environment = 'tenant_' . $tenant;
            }
            catch (\OutOfBoundsException $e) {
                throw new NotFoundHttpException('Could not find tenant');
            }
            catch (\RuntimeException $e) {
                // Do nothing
            }

            $this->boot();
        }

        return parent::handle( $request, $type, $catch );
    }
}
