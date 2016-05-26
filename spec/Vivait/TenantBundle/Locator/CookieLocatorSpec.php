<?php

namespace spec\Vivait\TenantBundle\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Vivait\TenantBundle\Locator\CookieLocator;
use Vivait\TenantBundle\Model\Tenant;
use Vivait\TenantBundle\Registry\TenantRegistry;

/**
 * @mixin CookieLocator
 */
class CookieLocatorSpec extends ObjectBehavior
{
    function it_correctly_retrieves_the_tenant_name_from_a_cookie()
    {
        $request = Request::create('http://mysite.dev', 'GET', [], ['tenant' => 'acme_co']);
        $tenantRegistry = new TenantRegistry([new Tenant('acme_co'), new Tenant('my_co')]);
        $this->beConstructedWith($request, 'tenant', $tenantRegistry);

        $this->getTenant()->shouldReturn('acme_co');
    }

    function it_throws_a_runtime_exception_if_no_tenant_cookie_is_found()
    {
        $request = Request::create('http://mysite.dev', 'GET');
        $tenantRegistry = new TenantRegistry([new Tenant('acme_co')]);
        $this->beConstructedWith($request, 'tenant', $tenantRegistry);

        $this->shouldThrow(\RuntimeException::class)->duringGetTenant();
    }

    function it_throws_a_runtime_exception_if_the_tenant_cookie_has_no_value()
    {
        $request = Request::create('http://mysite.dev', 'GET', [], ['tenant' => '']);
        $tenantRegistry = new TenantRegistry([new Tenant('acme_co')]);
        $this->beConstructedWith($request, 'tenant', $tenantRegistry);

        $this->shouldThrow(\OutOfBoundsException::class)->duringGetTenant();
    }

    function it_throws_an_out_of_bounds_exception_if_the_tenant_is_not_in_the_list_of_available_tenants()
    {
        $request = Request::create('http://mysite.dev', 'GET', [], ['tenant' => 'my_co']);
        $tenantRegistry = new TenantRegistry([new Tenant('acme_co')]);
        $this->beConstructedWith($request, 'tenant', $tenantRegistry);

        $this->shouldThrow(\OutOfBoundsException::class)->duringGetTenant();
    }

    function it_throws_an_out_of_bounds_exception_if_you_try_to_access_a_development_tenant()
    {
        $request = Request::create('http://mysite.dev', 'GET', [], ['tenant' => 'dev']);
        $tenantRegistry = new TenantRegistry([new Tenant('acme_co'), new Tenant('dev')]);
        $this->beConstructedWith($request, 'tenant', $tenantRegistry);

        $this->shouldThrow(\OutOfBoundsException::class)->duringGetTenant();
    }

    function it_throws_an_out_of_bounds_runtime_exception_if_you_try_to_access_a_test_tenant()
    {
        $request = Request::create('http://mysite.dev', 'GET', [], ['tenant' => 'test']);
        $tenantRegistry = new TenantRegistry([new Tenant('acme_co'), new Tenant('test')]);
        $this->beConstructedWith($request, 'tenant', $tenantRegistry);

        $this->shouldThrow(\OutOfBoundsException::class)->duringGetTenant();
    }
}
