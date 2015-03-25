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
            ->setDescription('Waits 20 seconds - use only for debugging')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i = 1; $i <= 2; $i++) {
            echo $i;
            sleep(1);
        }
    }
}
