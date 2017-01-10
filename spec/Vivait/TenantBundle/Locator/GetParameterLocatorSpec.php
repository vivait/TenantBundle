<?php

namespace spec\Vivait\TenantBundle\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Vivait\TenantBundle\Locator\GetParameterLocator;
use Vivait\TenantBundle\Model\Tenant;
use Vivait\TenantBundle\Registry\TenantRegistry;

/**
 * @mixin GetParameterLocator
 */
class GetParameterLocatorSpec extends ObjectBehavior
{
    function it_correctly_retrieves_the_tenant_name_from_a_get_parameter()
    {
        $request = Request::create('http://mysite.dev?tenant=acme_co', 'GET');
        $tenantRegistry = new TenantRegistry([new Tenant('acme_co'), new Tenant('my_co')]);
        $this->beConstructedWith($request, 'tenant', $tenantRegistry);

        $this->getTenant()->shouldReturn('acme_co');
    }

    function it_correctly_retrieves_the_tenant_name_from_a_differently_named_get_parameter()
    {
        $request = Request::create('http://mysite.dev?code=acme_co', 'GET');
        $tenantRegistry = new TenantRegistry([new Tenant('acme_co'), new Tenant('my_co')]);
        $this->beConstructedWith($request, 'code', $tenantRegistry);

        $this->getTenant()->shouldReturn('acme_co');
    }

    function it_throws_an_exception_when_the_tenant_code_is_not_in_the_url()
    {
        $request = Request::create('http://mysite.dev', 'GET');
        $tenantRegistry = new TenantRegistry([new Tenant('acme_co'), new Tenant('my_co')]);
        $this->beConstructedWith($request, 'tenant', $tenantRegistry);

        $this->shouldThrow(\RuntimeException::class)->duringGetTenant();
    }

    function it_throws_an_exception_when_the_tenant_does_not_exist()
    {
        $request = Request::create('http://mysite.dev?tenant=acme_co', 'GET');
        $tenantRegistry = new TenantRegistry([new Tenant('my_co')]);
        $this->beConstructedWith($request, 'tenant', $tenantRegistry);

        $this->shouldThrow(\OutOfBoundsException::class)->duringGetTenant();
    }
}
