<?php

namespace spec\Vivait\TenantBundle\TenantProvider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vivait\TenantBundle\Model\Tenant;
use Vivait\TenantBundle\TenantProvider\YamlProvider;

/**
 * @mixin YamlProvider
 */
class YamlProviderSpec extends ObjectBehavior
{
	function let() {
		$this->beConstructedWith('fixtures/tenants.yml');
	}

    function it_has_a_provider_interface()
    {
        $this->shouldHaveType('Vivait\TenantBundle\TenantProvider\TenantProvider');
    }

	function it_provides_a_list_of_tenants()
	{
		$tenants = $this->loadTenants();

		$tenants->shouldHaveCount(3);
		$tenants->shouldBeAnArrayOfTenants();
	}

	public function getMatchers()
	{
		return [
			'beAnArrayOfTenants' => function($subject) {
				foreach ($subject as $class) {
					if (!($class instanceOf Tenant)) {
						return false;
					}
				}

				return true;
			}
		];
	}
}
