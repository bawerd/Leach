<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leach\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Leach\Container\ContainerInterface;
use Leach\Server;
use Leach\Transport;

/**
 * @codeCoverageIgnore
 */
class StartCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('container', InputArgument::REQUIRED),
                new InputOption('send-spec', null, InputOption::VALUE_REQUIRED, '', 'tcp://127.0.0.1:9997'),
                new InputOption('send-id', null, InputOption::VALUE_REQUIRED),
                new InputOption('recv-spec', null, InputOption::VALUE_OPTIONAL),
                new InputOption('recv-id', null, InputOption::VALUE_OPTIONAL),
            ))
            ->setDescription('')
            ->setHelp(<<<'EOT'

EOT
            )
            ->setName('start')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server = new Server($this->getTransport($input), $this->getContainer($input));
        $server->start();
    }

    /**
     * Returns a Transport instance.
     *
     * @param InputInterface $input A InputInterface instance
     *
     * @return Transport
     */
    protected function getTransport(InputInterface $input)
    {
        return new Transport(
            $input->getOption('send-spec'),
            $input->getOption('send-id'),
            $input->getOption('recv-spec'),
            $input->getOption('recv-id')
        );
    }

    /**
     * Returns a ContainerInterface instance.
     *
     * @param InputInterface $input A InputInterface instance
     *
     * @return ContainerInterface
     *
     * @throws \RuntimeException
     */
    protected function getContainer(InputInterface $input)
    {
        $file = $input->getArgument('container');
        if (!$file = stream_resolve_include_path($file)) {
            throw new \RuntimeException('container file');
        }

        $container = include $file;
        if (!$container instanceof ContainerInterface) {
            throw new \RuntimeException('container');
        }

        return $container;
    }
}
