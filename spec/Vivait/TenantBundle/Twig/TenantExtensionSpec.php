<?php

namespace spec\Vivait\TenantBundle\Twig;

use PhpSpec\ObjectBehavior;
use Vivait\TenantBundle\Model\Tenant;
use Vivait\TenantBundle\Registry\TenantRegistry;

class TenantExtensionSpec extends ObjectBehavior
{
    function let(TenantRegistry $registry)
    {
        $this->beConstructedWith($registry);
    }

    function it_gets_the_current_tenant(TenantRegistry $registry, Tenant $tenant)
    {
        $registry->getCurrent()->willReturn($tenant);

        $this->getCurrentTenant()->shouldReturnAnInstanceOf('Vivait\\TenantBundle\\Model\\Tenant');
        $this->getCurrentTenant()->shouldReturn($tenant);
    }

    function it_does_not_throw_exceptions_for_null_tenant(TenantRegistry $registry){
        $registry->getCurrent()->willReturn(null);
        $this->shouldNotThrow('\Exception');
        $this->getCurrentTenant()->shouldReturn(null);
    }
} 