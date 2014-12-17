<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use test\Vivait\TenantBundle\Tester\ApplicationCommandTester;

class CommandContext implements Context, KernelAwareContext {
    use KernelDictionary;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var ApplicationCommandTester
     */
    private $tester;

    /**
     * @var int
     */
    private $exitCode;
    private $commandOutput;

    /**
     * @When I run the command :command
     * @param $command
     */
    public function iRunTheCommand( $command ) {
        exec(PHP_BINARY . ' test/Vivait/TenantBundle/app/console --no-ansi '. $command, $this->commandOutput, $return);

        PHPUnit_Framework_Assert::assertSame(0, $return, 'Non zero return code received from command');
    }

    /**
     * @Then /^I should see "([^"]*)" in the command output$/
     */
    public function iShouldSeeInTheCommandOutput($pattern)
    {
        PHPUnit_Framework_Assert::assertContains($pattern, implode("\n", $this->commandOutput));
    }
}
