<?php

namespace Vivait\TenantBundle\Locator;

use Symfony\Component\HttpFoundation\Request;
use Vivait\TenantBundle\Registry\TenantRegistry;

class CookieLocator implements TenantLocator
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $cookieName;

    /**
     * @var TenantRegistry
     */
    private $tenantRegistry;

    /**
     * @param Request        $request
     * @param                $cookieName
     * @param TenantRegistry $tenantRegistry
     */
    public function __construct(Request $request, $cookieName, TenantRegistry $tenantRegistry)
    {
        $this->request = $request;
        $this->cookieName = $cookieName;
        $this->tenantRegistry = $tenantRegistry;
    }

    /**
     * @return string
     */
    public function getTenant()
    {
        $tenantName = $this->request->cookies->get($this->cookieName);

        $this->checkTenantName($tenantName);

        return $tenantName;
    }

    /**
     * @param Request        $request
     * @param string         $cookieName
     * @param TenantRegistry $tenantRegistry
     *
     * @return string Tenant key
     */
    public static function getTenantFromRequest(
        Request $request,
        $cookieName = 'tenant',
        TenantRegistry $tenantRegistry
    ) {
        $locator = new self($request, $cookieName, $tenantRegistry);

        return $locator->getTenant();
    }

    /**
     * @param $tenantName
     *
     * @return bool
     */
    private function tenantNameIsValid($tenantName)
    {
        return $this->tenantRegistry->contains($tenantName) && $tenantName !== 'dev' && $tenantName !== 'test';
    }

    /**
     * @throws \RuntimeException
     * @throws \OutOfBoundsException
     *
     * @param $tenantName
     */
    private function checkTenantName($tenantName)
    {
        // if no cookie, throw runtime so it'll continue as prod
        if ( ! $tenantName) {
            throw new \RuntimeException;
        }

        // if tenant is set and doesn't exist, throw out of bounds
        if ( ! $this->tenantNameIsValid($tenantName)) {
            throw new \OutOfBoundsException;
        }
    }
}
