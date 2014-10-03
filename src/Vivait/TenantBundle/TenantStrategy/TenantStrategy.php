<?php


namespace Vivait\TenantBundle\TenantStrategy;


use Symfony\Component\Yaml\Yaml;
use Vivait\TenantBundle\Model\Tenant;

interface TenantStrategy {

	public function loadTenants();
}