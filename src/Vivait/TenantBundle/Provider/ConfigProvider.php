<?php

namespace Vivait\TenantBundle\Provider;

use Symfony\Component\Finder\Expression\Expression;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Glob;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;
use Vivait\TenantBundle\Model\Tenant;

/**
 * @see ConfigProviderSpec
 */
final class ConfigProvider implements TenantProvider
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var string
     */
    private $file_pattern;

    public function __construct($path, $file_pattern = '/^config_tenant_(?P<tenant>.+?)\.(.*?)$/', Finder $finder = null)
    {
        $this->path = $path;
        $this->file_pattern = $file_pattern;

        if ($finder === null) {
            $this->finder = new Finder();
        }
        else {
            $this->finder = $finder;
        }
    }

    public function loadTenants() {
        /* @var $files SplFileInfo[] */
        $files = $this->finder
            ->files()
            ->in($this->path)
            ->name($this->file_pattern);

        $tenants = [];

        foreach ($files as $file) {
            if (!preg_match($this->file_pattern, $file->getFilename(), $matches)) {
                throw new \RuntimeException(sprintf('Could not detect tenant key based on filename "%s"', $file->getFilename()));
            }

            $tenants[$matches['tenant']] = new Tenant($matches['tenant']);
        }

        return $tenants;
    }

    /**
     * Sets path
     * @param string $path
     * @return $this
     */
    public function setPath( $path )
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Gets finder
     * @return Finder
     */
    public function getFinder()
    {
        return $this->finder;
    }

    /**
     * Sets finder
     * @param Finder $finder
     * @return $this
     */
    public function setFinder( $finder )
    {
        $this->finder = $finder;

        return $this;
    }
}
