<?php

use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Symfony\Component\Process\Process;

class CommandContext implements Context, KernelAwareContext {
    use KernelDictionary;

    private $commandOutput;

    /** @var Process */
    private $process;

    /**
     * @When I run the tenanted command :command
     * @When I run the tenanted command :command with options :options
     * @param $command
     * @param $options
     */
    public function iRunTheCommand( $command, $options = null ) {
        $tenantedCommand = 'bin/tenant ' . $options . ' ' . PHP_BINARY . ' test/Vivait/TenantBundle/app/console --no-ansi ' . $command;

        $this->process = new Process($tenantedCommand);
        $this->process->run();

        PHPUnit_Framework_Assert::assertSame(0, $this->process->getExitCode(), 'Non zero return code received from command');
    }

    /**
     * @When I run the tenanted command :command in the background
     * @When I run the tenanted command :command in the background with options :options
     * @param $command
     * @param $options
     *
     * This doesn't actually run the command, but prepares it
     */
    public function iRunTheCommandInTheBackground( $command, $options = null ) {
        // Exec is needed to make it cancellable: https://github.com/symfony/symfony/issues/5759
        $this->process = new Process('exec bin/tenant '. $options .' '. PHP_BINARY . ' test/Vivait/TenantBundle/app/console --no-ansi '. $command);
    }

    /**
     * @Then /^I should see "([^"]*)" in the command output$/
     */
    public function iShouldSeeInTheCommandOutput($pattern)
    {
        PHPUnit_Framework_Assert::assertContains($pattern, $this->process->getOutput());
    }

    /**
     * @Then /^I should not see "([^"]*)" in the command output$/
     */
    public function iShouldNotSeeInTheCommandOutput($pattern)
    {
        PHPUnit_Framework_Assert::assertNotContains($pattern, $this->process->getOutput());
    }

    /**
     * @Then I should be able to cancel the command
     */
    public function iShouldBeAbleToCancelTheCommand()
    {
        // How many seconds to allow it to stop
        $tolerance = 2;

        $this->process->run(function($type, $buffer) use ($tolerance) {
            static $cancelled = false;

            if (!$cancelled) {
                $this->process->signal(15);
                $cancelled = time();
            }
            else if ((time() - $cancelled) > $tolerance) {
                throw new \Exception('Process did not cancel in time, still sending output: '. $buffer);
            }
        });

        if (!$this->process) {
            throw new LogicException('No process started');
        }
    }
}
