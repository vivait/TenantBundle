<?php

use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vivait\TenantBundle\Model\Tenant;
use Vivait\TenantBundle\Registry\TenantRegistry;

class TenantBundleContext implements Context, KernelAwareContext {
    use KernelDictionary;

    /**
     * @var Response|Exception
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
     * @throws Exception
     */
    public function iHaveATenant( Tenant $tenant ) {
        $registry = $this->getTenantRegistry();

        if (!$registry->contains($tenant)) {
            throw new Exception(sprintf('Tenant "%s" not registered in registry', $tenant->getKey()));
        }
    }

    /**
     * @Given /^I make a request to "([^"]*)"$/
     */
    public function iMakeARequestTo( $url )
    {
        $request = Request::create($url);

        $kernel = new test\Vivait\TenantBundle\app\AppKernel('prod', false);

        try {
            $this->response = $kernel->handle($request);
            $kernel->terminate($request, $this->response);
        }
        catch (\Exception $e) {
            $this->response = $e;
        }
    }

    /**
     * @Then /^I should see "([^"]*)"$/
     */
    public function iShouldSee( $match )
    {
        if ($this->response instanceOf \Exception) {
            throw $this->response;
        }

        PHPUnit_Framework_Assert::assertContains( $match, $this->response->getContent() );
    }

    /**
     * @Then /^I should get (?:a|an) "([^"]*)" exception$/
     */
    public function iShouldGetException( $match )
    {
        PHPUnit_Framework_Assert::assertInstanceOf( $match, $this->response );
    }

    /**
     * @return TenantRegistry
     */
    protected function getTenantRegistry()
    {
        return $this->getContainer()->get('vivait_tenant.registry');
    }
}
