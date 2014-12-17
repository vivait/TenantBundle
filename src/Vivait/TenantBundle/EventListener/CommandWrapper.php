<?php

namespace Vivait\TenantBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Vivait\TenantBundle\Kernel\TenantKernel;
use Vivait\TenantBundle\Registry\TenantRegistry;

class CommandWrapper {
    /**
     * @var TenantRegistry
     */
    private $tenantRegistry;

    static $wrapped = false;

    function __construct( TenantRegistry $tenantRegistry ) {
        $this->tenantRegistry = $tenantRegistry;
    }

    public function onConsoleCommand(ConsoleCommandEvent $event) {
        $command = $event->getCommand();
        $application = $command->getApplication();
        $inputDefinition = $application->getDefinition();

        // It's not a Symfony command, no need to tenant it
        if (!$application instanceOf Application) {
            return;
        }

        $kernel = $application->getKernel();

        if (!($kernel instanceOf TenantKernel) || !$kernel->enableTenanting) {
            return;
        }

        $output = $event->getOutput();
        $input = $event->getInput();
        $originalInput = clone $input;

        $this->alterInputDefinition( $inputDefinition, $input, $command );

        $tenant = $input->getOption('tenant');

        if ($tenant) {
            $returnCode = 1;

            foreach ($this->tenantRegistry->getAll() as $id => $tenant) {
                $returnCode = $this->performCommand( $kernel, $id, $originalInput, $output );

                if ($returnCode > 0) {
                    exit ($returnCode);
                }
            }

//            $application->
            if (method_exists($command, 'disableComment')) {
                /** @noinspection PhpUndefinedMethodInspection */
                $command->disableCommand();
            }
            else {
                exit ($returnCode);
            }
        }
    }

    /**
     * Gets tenantRegistry
     * @return TenantRegistry
     */
    public function getTenantRegistry() {
        return $this->tenantRegistry;
    }

    /**
     * Sets tenantRegistry
     * @param TenantRegistry $tenantRegistry
     * @return $this
     */
    public function setTenantRegistry( $tenantRegistry ) {
        $this->tenantRegistry = $tenantRegistry;

        return $this;
    }

    /**
     * @param KernelInterface $kernel
     * @param integer $environment
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function performCommand( KernelInterface $kernel, $environment, InputInterface $input, OutputInterface $output ) {

        exec(PHP_BINARY . $this->prepareArguments($_SERVER['argv'], $environment), $rawOutput, $return);

        $output->writeln($rawOutput);

        return $return;
    }

    /**
     * @param InputDefinition $inputDefinition
     * @param InputInterface $input
     * @param Command $command
     * @return ArgvInput
     */
    private function alterInputDefinition( InputDefinition $inputDefinition, InputInterface $input, Command $command ) {
        $inputDefinition->addOption(
            new InputOption( 'tenant', null, InputOption::VALUE_NONE, 'Perform the command on all tenants?')
        );

        // merge the application's input definition
        $command->mergeApplicationDefinition();

        // we use the input definition of the command
        $input->bind( $command->getDefinition() );

        return $input;
    }

    /**
     * @param integer $env
     */
    private function prepareArguments($argv, $env) {
        $string = '';

        foreach ($argv as $argument) {
            if (strpos($argument, '--tenant') === 0 || strpos($argument, '--env')) {
                continue;
            }

            $string .= ' '. escapeshellarg($argument);
        }

        return $string .' --env=tenant_'. $env;
    }
}
