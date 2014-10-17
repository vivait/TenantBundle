<?php

namespace Vivait\TenantBundle\Twig;

use Vivait\TenantBundle\Registry\TenantRegistry;

class TenantExtension extends \Twig_Extension
{
    /**
     * @var TenantRegistry
     */
    private $registry;

    /**
     * @param TenantRegistry $registry
     */
    public function __construct(TenantRegistry $registry)
    {
        $this->registry = $registry;
    }
    public function getCurrentTenant()
    {
        return $this->registry->getCurrent();
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('tenant', [$this, 'getCurrentTenant'])
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'vivait_tenant_twig_extension';
    }
}
