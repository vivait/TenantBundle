<?php
namespace Vivait\TenantBundle\Locator;

interface TenantLocator
{
    /**
     * @return string
     */
    public function getTenant();
}