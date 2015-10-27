<?php

namespace Vivait\TenantBundle\Locator;

use Symfony\Component\HttpFoundation\Request;

/**
 * @see HostnameLocatorSpec
 */
class HeaderLocator implements TenantLocator
{
    private $request;

    /**
     * @var string
     */
    private $tenant_key;

    public function __construct(Request $request, $tenant_key)
    {
        $this->request = $request;
        $this->tenant_key = $tenant_key;
    }

    /**
     * @return string Tenant key
     */
    public function getTenant()
    {
        if (!$this->request->headers->has($this->tenant_key)) {
            throw new \RuntimeException('Could not locate a tenant in the header');
        }

        return $this->request->headers->get($this->tenant_key);
    }

    /**
     * @param Request $request
     * @param string $tenant_key
     * @return string Tenant key
     */
    public static function getTenantFromRequest(Request $request, $tenant_key = 'tenant')
    {
        $locator = new self($request, $tenant_key);

        return $locator->getTenant();
    }
}
