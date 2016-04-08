<?php

namespace Vivait\TenantBundle\Locator;

use Symfony\Component\HttpFoundation\Request;

class HeaderLocator implements TenantLocator
{
    private $request;

    /**
     * @var string
     */
    private $headerKey;

    public function __construct(Request $request, $headerKey)
    {
        $this->request = $request;
        $this->headerKey = $headerKey;
    }

    /**
     * @return string Tenant key
     */
    public function getTenant()
    {
        if (!$this->request->headers->has($this->headerKey)) {
            throw new \RuntimeException('Could not locate a tenant header in the request');
        }

        return $this->request->headers->get($this->headerKey);
    }

    /**
     * @param Request $request
     * @param string $headerKey
     * @return string Tenant key
     */
    public static function getTenantFromRequest(Request $request, $headerKey = 'tenant')
    {
        $locator = new self($request, $headerKey);

        return $locator->getTenant();
    }
}
