<?php

namespace Vivait\TenantBundle\Locator;

use Symfony\Component\HttpFoundation\Request;

class CookieLocator implements TenantLocator
{

    private $request;

    /**
     * @var string
     */
    private $cookieName;

    public function __construct(Request $request, $cookieName)
    {
        $this->request = $request;
        $this->cookieName = $cookieName;
    }

    /**
     * @return string
     */
    public function getTenant()
    {
        $tenantName = $this->request->cookies->get($this->cookieName);

        if ( ! $tenantName) {
            throw new \RuntimeException;
        }

        return $tenantName;
    }

    /**
     * @param Request $request
     * @param string  $cookieName
     *
     * @return string Tenant key
     */
    public static function getTenantFromRequest(Request $request, $cookieName = 'tenant')
    {
        $locator = new self($request, $cookieName);

        return $locator->getTenant();
    }
}
