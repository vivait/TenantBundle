<?php

namespace spec\Vivait\TenantBundle\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Vivait\TenantBundle\Locator\HostnameLocator;

/**
 * @mixin HostnameLocator
 */
class HostnameLocatorSpec extends ObjectBehavior
{
    function it_should_match_a_tenant_from_a_subdomain() {
        $request = Request::create('http://tenant1.example.org/login');

        $this->beConstructedWith($request);
        $this->getTenant()->shouldBe('tenant1');
    }

    function it_should_not_match_a_tenant_from_a_domain() {
        $request = Request::create('http://example.org/login');

        $this->beConstructedWith($request);
        $this->shouldThrow('\RuntimeException')->duringGetTenant();
    }

    function it_should_match_a_tenant_from_a_subdomain_with_a_dot_in_the_path() {
        $request = Request::create('http://tenant1.example.org/sign.up');

        $this->beConstructedWith($request);
        $this->getTenant()->shouldBe('tenant1');
    }

    function it_should_not_match_a_tenant_from_a_domain_with_a_dot_in_the_path() {
        $request = Request::create('http://example.org/sign.up');

        $this->beConstructedWith($request);
        $this->shouldThrow('\RuntimeException')->duringGetTenant();
    }
}
