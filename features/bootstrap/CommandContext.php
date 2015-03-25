<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use test\Vivait\TenantBundle\Tester\ApplicationCommandTester;

class CommandContext implements Context, KernelAwareContext {
    use KernelDictionary;

    private $commandOutput;

    /**
     * @When I run the tenanted command :command
     * @When I run the tenanted command :command with options :options
     * @param $command
     * @param $options
     */
    public function iRunTheCommand( $command, $options = null ) {
        exec('bin/tenant '. $options .' '. PHP_BINARY . ' test/Vivait/TenantBundle/app/console --no-ansi '. $command, $this->commandOutput, $return);

        PHPUnit_Framework_Assert::assertSame(0, $return, 'Non zero return code received from command');
    }

    /**
     * @Then /^I should see "([^"]*)" in the command output$/
     */
    public function iShouldSeeInTheCommandOutput($pattern)
    {
        PHPUnit_Framework_Assert::assertContains($pattern, implode("\n", $this->commandOutput));
    }

    /**
     * @Then /^I should not see "([^"]*)" in the command output$/
     */
    public function iShouldNotSeeInTheCommandOutput($pattern)
    {
        PHPUnit_Framework_Assert::assertNotContains($pattern, implode("\n", $this->commandOutput));
    }
}
