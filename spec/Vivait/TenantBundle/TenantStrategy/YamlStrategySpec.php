<?php

namespace spec\Vivait\TenantBundle\TenantStrategy;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vivait\TenantBundle\Model\Tenant;
use Vivait\TenantBundle\TenantStrategy\YamlStrategy;

/**
 * @mixin YamlStrategy
 */
class YamlStrategySpec extends ObjectBehavior
{
	function let() {
		$this->beConstructedWith('fixtures/tenants.yml');
	}

    function it_is_a_strategy_interface()
    {
        $this->shouldHaveType('Vivait\TenantBundle\TenantStrategy\TenantStrategy');
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
