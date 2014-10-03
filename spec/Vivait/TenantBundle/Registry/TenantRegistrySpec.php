<?php

namespace spec\Vivait\TenantBundle\Registry;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vivait\TenantBundle\Model\Tenant;
use Vivait\TenantBundle\Registry\TenantRegistry;

/**
 * @mixin TenantRegistry
 */
class TenantRegistrySpec extends ObjectBehavior
{
	function it_stores_a_tenant() {
		$tenant = new Tenant('tenant1');
		$this->add($tenant);
		$this->get('tenant1')->shouldBe($tenant);
	}

	function it_throws_an_exception_when_getting_an_invalid_tenant() {
		$tenant = new Tenant('tenant2');
		$this->add($tenant);
		$this->shouldThrow('\OutOfBoundsException')->duringGet('tenant1');
	}

	function it_wont_store_a_tenant_twice() {
		$tenant = new Tenant('tenant1');
		$this->add($tenant);
		$this->add($tenant);
		$this->getAll()->shouldHaveCount(1);
	}

	function it_will_overwrite_duplicate_keys() {
		$tenant1 = new Tenant('tenant1');
		$tenant2 = new Tenant('tenant1');

		$this->addAll([$tenant1, $tenant2]);

		$this->getAll()->shouldHaveCount(1);
		$this->get( 'tenant1' )->shouldbe( $tenant2 );
	}

	function it_will_say_if_it_contains_a_tenant() {
		$tenant = new Tenant('tenant1');
		$this->add($tenant);

		$this->contains( 'tenant1' )->shouldBe(true);
		$this->contains( 'tenant2' )->shouldBe(false);
	}

	function it_wont_store_invalid_tenant_objects() {
		$this->shouldThrow('\InvalidArgumentException')->duringAddAll([new Tenant('tenant1'), 'invalid']);

	}
}
