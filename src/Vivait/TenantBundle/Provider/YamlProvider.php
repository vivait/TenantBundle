<?php

namespace Vivait\TenantBundle\Provider;

use Symfony\Component\Yaml\Yaml;
use Vivait\TenantBundle\Model\Tenant;
use spec\Vivait\TenantBundle\TenantStrategy\YamlProviderSpec;

/**
 * @see YamlProviderSpec
 */
final class YamlProvider implements TenantProvider
{
    /**
     * @var string
     */
    private $resource;

    /**
     * @param null|string $resource The filename of the YAML containing the tenants
     */
    public function __construct($resource = null)
    {
        $this->resource = $resource;
    }

    /**
     * @param null|string $resource The filename of the YAML containing the tenants
     */
    public function setResource($resource) {
        $this->resource = $resource;
    }

    /**
     * @inheritDoc
     */
    public function loadTenants() {
        $yaml = Yaml::parse(file_get_contents($this->resource));

        if (!is_array($yaml)) {
            throw new \RuntimeException('YAML file didn\'t contain an array of tenants');
        }

        $tenants = [];

        // Convert them to Tenant objects
        foreach ($yaml as $tenant) {
            $tenants[$tenant] = new Tenant($tenant);
        }

        return $tenants;
    }
}
