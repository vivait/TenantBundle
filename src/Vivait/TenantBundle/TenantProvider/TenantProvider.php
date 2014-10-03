<?php


namespace Vivait\TenantBundle\TenantProvider;


use Symfony\Component\Yaml\Yaml;
use Vivait\TenantBundle\Model\Tenant;

interface TenantProvider {

	public function loadTenants();
}