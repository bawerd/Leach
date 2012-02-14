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
use Symfony\Component\HttpFoundation\ParameterBag;

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
                new InputOption('send-spec', null, InputOption::VALUE_OPTIONAL, '', 'tcp://127.0.0.1:9998'),
                new InputOption('send-id', null, InputOption::VALUE_OPTIONAL, '', '296fef89-153f-4464-8f53-952b3a750b1b'),
                new InputOption('recv-spec', null, InputOption::VALUE_OPTIONAL),
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
        $this->getServer($input)->start();
    }

    /**
     * Returns a Server instance.
     *
     * @param InputInterface $input A InputInterface instance
     *
     * @return Server
     */
    protected function getServer(InputInterface $input)
    {
        return new Server(
            $container = $this->getContainer($input),
            $this->getTransport($input, $container->getOptions())
        );
    }

    /**
     * Returns a Transport instance.
     *
     * @param InputInterface $input A InputInterface instance
     * @param ParameterBag $options A ParameterBag instance
     *
     * @return Transport
     */
    protected function getTransport(InputInterface $input, ParameterBag $options)
    {
        return new Transport(
            $options->get('send_spec', $input->getOption('send-spec')),
            $options->get('send_id', $input->getOption('send-id')),
            $options->get('recv_spec', $input->getOption('recv-spec'))
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
