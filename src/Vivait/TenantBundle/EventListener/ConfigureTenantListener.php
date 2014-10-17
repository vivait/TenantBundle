<?php


namespace Vivait\TenantBundle\EventListener;


use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Vivait\TenantBundle\Registry\TenantRegistry;

class ConfigureTenantListener
{
    /**
     * @var TenantRegistry
     */
    private $registry;
    private $name;
    private $attributes;

    /**
     * @param TenantRegistry $registry
     * @param $name
     * @param $attributes
     */
    public function __construct(TenantRegistry $registry, $name, $attributes)
    {
        $this->registry = $registry;
        $this->name = $name;
        $this->attributes = $attributes;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $tenant = $this->registry->getCurrent();
        $tenant
            ->setName($this->name)
            ->setAttributes($this->attributes);
        $this->registry->setCurrent($tenant);
    }
} 