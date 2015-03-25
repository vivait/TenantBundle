<?php

namespace Vivait\TenantBundle\Locator;

use Symfony\Component\HttpFoundation\Request;

/**
 * @see HostnameLocatorSpec
 */
class HostnameLocator implements TenantLocator
{
    private $pattern = '#^(?P<tenant>.+?)\.(.*?)\.#';
    private $request;

    public function __construct( Request $request )
    {
        $this->request = $request;
    }

    /**
     * @return string Tenant key
     */
    public function getTenant() {
        // Get the server name
        $host = $this->request->getHost();

        if (!preg_match( $this->pattern, $host, $matches )) {
            throw new \RuntimeException(sprintf('Could not match tenant for host "%s"', $host));
        }

        return $matches['tenant'];
    }

    /**
     * Gets the REGEX pattern used to match a tenant
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Sets the REGEX pattern used to match a tenant
     * @param string $pattern
     * @return $this
     */
    public function setPattern( $pattern )
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * @param Request $request
     * @return string Tenant key
     */
    public static function getTenantFromRequest(Request $request) {
        $locator = new self($request);
        return $locator->getTenant();
    }
}
