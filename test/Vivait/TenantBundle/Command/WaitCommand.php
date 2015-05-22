<?php

namespace test\Vivait\TenantBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Vivait\TenantBundle\Kernel\TenantKernel;
use Vivait\TenantBundle\Model\Tenant;
use Vivait\TenantBundle\Registry\TenantRegistry;

class WaitCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('vivait:tenants:wait')
            ->setDescription('Waits a specified number of seconds - use only for debugging')
            ->addArgument('time', InputArgument::OPTIONAL, 'Number of seconds to wait', 2)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i = 1; $i <= $input->getArgument('time'); $i++) {
            echo $i;
            sleep(1);
        }
    }
}
