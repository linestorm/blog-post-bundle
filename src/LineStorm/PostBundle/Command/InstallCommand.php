<?php

namespace LineStorm\PostBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * This command will install all the assets
 *
 * Class IndexCommand
 *
 * @package LineStorm\SearchBundle\Command
 */
class InstallCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('linestorm:cms:post')
            ->setDescription('Trigger an cms assets build')
            ->addArgument('input')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        switch($input->getArgument('input'))
        {
            case 'install':
                $container->getParameter('linestorm.cms.');
                $moduleManager = $container->get('linestorm.cms.module_manager');

                $modules = $moduleManager->getModules();

                foreach($modules as $module)
                {
                    $config = $module->getConfig();
                }

                break;

            default:
                $output->writeln('Unknown option');
                return;
                break;
        }

        $output->writeln("Finished");

    }
}
