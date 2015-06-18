<?php

namespace test\Vivait\TenantBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Kernel;
use Vivait\TenantBundle\Kernel\TenantKernel;
use Vivait\TenantBundle\Model\Tenant;
use Vivait\TenantBundle\Registry\TenantRegistry;

class CrashCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('vivait:tenants:crash')
            ->setDescription('Waits a specified number of seconds and then crashes - use only for debugging')
            ->addArgument('tenant', InputArgument::REQUIRED, 'Tenant to crash for')
            ->addArgument('time', InputArgument::OPTIONAL, 'Number of seconds to wait', 2)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tenant = 'tenant_'. $input->getArgument('tenant');
        /** @var Kernel $kernel */
        $kernel = $this->getContainer()->get('kernel');

        for ($i = 1; $i <= $input->getArgument('time'); $i++) {
            echo $i;
            sleep(1);

            if ($tenant == $kernel->getEnvironment()) {
                throw new \Exception('Crashed');
            }
        }
    }
}
