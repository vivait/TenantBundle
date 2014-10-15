<?php

use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vivait\TenantBundle\Model\Tenant;

class TenantBundleContext implements Context, KernelAwareContext {
    use KernelDictionary;

    /**
     * @var Response
     */
    private $response;

    /**
     * @Transform :tenant
     */
    public function castTenant($tenant)
    {
        return new Tenant($tenant);
    }

    /**
     * @When I have a tenant :tenant
     * @param Tenant $tenant
     */
    public function iHaveATenant( Tenant $tenant ) {
        $registry = $this->getContainer()->get('vivait_tenant.registry');
        $registry->add($tenant);
    }

    /**
     * @Given /^I make a request to "([^"]*)"$/
     */
    public function iMakeARequestTo( $url )
    {
        $request = Request::create($url);

        $kernel = new test\Vivait\TenantBundle\app\AppKernel('prod', true);
        $this->response = $kernel->handle($request);

        $kernel->terminate($request, $this->response);
    }

    /**
     * @Then /^I should see "([^"]*)"$/
     */
    public function iShouldSee( $match )
    {
        //\assertContains($pattern, $this->tester->getDisplay());
        \assertContains( $match, $this->response->getContent() );
    }
}
