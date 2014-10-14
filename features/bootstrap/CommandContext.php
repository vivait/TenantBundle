<?php

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';
require_once 'Tester/ApplicationCommandTester.php';

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Symfony\Bundle\FrameworkBundle\Console\Application;

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

    /**
     * @When /^I run the command "([^"]*)"$/
     * @param $command
     * @param array $parameters
     */
    public function iRunTheCommand( $command, array $parameters = array() ) {
        $this->application = new Application( $this->getKernel() );
        $this->application->setAutoExit( false );

        $parameters['command'] = $command;

        $this->tester = new ApplicationCommandTester( $this->application );
        $this->exitCode = $this->tester->execute($parameters);
    }

    /**
     * @Given /^I run the command "([^"]*)" with parameters:$/
     * @param $command
     * @param PyStringNode $parameterJson
     */
    public function iRunTheCommandWithParameters($command, PyStringNode $parameterJson)
    {
        $parameters = json_decode($parameterJson->getRaw(), true);

        if (null === $parameters) {
            throw new \InvalidArgumentException(
                "PyStringNode could not be converted to json."
            );
        }

        $this->iRunTheCommand($command, $parameters);
    }

    /**
     * @Then /^I should see "([^"]*)" in the command output$/
     */
    public function iShouldSeeInTheCommandOutput($pattern)
    {
        \assertContains($pattern, $this->tester->getDisplay());
    }
}
