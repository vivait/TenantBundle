<?php

namespace Vivait\TenantBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Vivait\TenantBundle\Kernel\TenantKernel;
use Vivait\TenantBundle\Model\Tenant;
use Vivait\TenantBundle\Registry\TenantRegistry;

class ListTenantsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('vivait:tenants:list')
            ->setDescription('Provides a list of tenants')
            ->addOption(
                'null',
                '0',
                InputOption::VALUE_NONE,
                'Use a null character as a separator (instead of the newline character).'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $null = $input->getOption('null');

        /** @var TenantRegistry $registry */
        $registry = $this->getContainer()->get('vivait_tenant.registry');

        $separator = $null ? "\0" : "\n";

        foreach ($registry->getAll() as $tenant) {
            $output->write($tenant->getKey() . $separator);
        }
    }
}
