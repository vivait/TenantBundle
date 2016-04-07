<?php

namespace spec\Vivait\TenantBundle\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Vivait\TenantBundle\Locator\CookieLocator;

/**
 * @mixin CookieLocator
 */
class CookieLocatorSpec extends ObjectBehavior
{

    function it_correctly_retrieves_the_tenant_name_from_a_cookie()
    {
        $request = Request::create('http://dataflow.dev', 'GET', [], ['tenant' => 'acme_co']);
        $this->beConstructedWith($request, 'tenant');

        $this->getTenant()->shouldReturn('acme_co');
    }

    function it_throws_an_exception_if_no_tenant_cookie_is_found()
    {
        $request = Request::create('http://dataflow.dev', 'GET');
        $this->beConstructedWith($request, 'tenant');

        $this->shouldThrow('\RuntimeException')->duringGetTenant();
    }

    function it_throws_an_exception_if_the_tenant_cookie_has_no_value()
    {
        $request = Request::create('http://dataflow.dev', 'GET', [], ['tenant' => '']);
        $this->beConstructedWith($request, 'tenant');

        $this->shouldThrow('\RuntimeException')->duringGetTenant();
    }
}
