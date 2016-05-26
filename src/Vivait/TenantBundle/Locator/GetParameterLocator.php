<?php

namespace Vivait\TenantBundle\Locator;

use Symfony\Component\HttpFoundation\Request;
use Vivait\TenantBundle\Registry\TenantRegistry;

class GetParameterLocator
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $parameterName;
    /**
     * @var TenantRegistry
     */
    private $registry;

    /**
     * @param Request        $request
     * @param string         $parameterName
     * @param TenantRegistry $registry
     */
    public function __construct(Request $request, $parameterName, TenantRegistry $registry)
    {
        $this->request = $request;
        $this->parameterName = $parameterName;
        $this->registry = $registry;
    }

    /**
     * @throws \RuntimeException
     * @throws \OutOfBoundsException
     * @return string
     */
    public function getTenant()
    {
        $tenantName = $this->request->get($this->parameterName, null);

        if ( ! isset($tenantName)) {
            throw new \RuntimeException;
        }

        if ( ! $this->registry->contains($tenantName)) {
            throw new \OutOfBoundsException;
        }

        return $tenantName;
    }

    /**
     * @param Request        $request
     * @param string         $parameterName
     * @param TenantRegistry $registry
     *
     * @return mixed
     */
    public static function getTenantFromRequest(Request $request, $parameterName = 'tenant', TenantRegistry $registry)
    {
        $locator = new self($request, $parameterName, $registry);

        return $locator->getTenant();
    }
}
