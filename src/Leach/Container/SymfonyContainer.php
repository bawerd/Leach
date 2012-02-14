<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leach\Container;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

use Leach\Event\SetUpEvent;
use Leach\Event\TearDownEvent;

class SymfonyContainer extends Container
{
    /**
     * @var array
     */
    protected $defaults = array();

    /**
     * @var HttpKernelInterface
     */
    private $kernel;

    /**
     * Constructor.
     *
     * @param HttpKernelInterface $kernel A HttpKernelInterface instance
     * @param array $options (optional) An array of options
     * @param EventDispatcherInterface $dispatcher A EventDispatcherInterface instance
     *
     * @see Container::__construct
     * @see ParameterBag
     */
    public function __construct(HttpKernelInterface $kernel, array $options = array(), EventDispatcherInterface $dispatcher = null)
    {
        /*
         * **NOTICE**
         *
         * As we reboot the kernel on each run, we cannot use its event dispatcher
         * instance like the Silex container does. But you can use your own Symfony
         * container which does not reboot the kernel each time, so that the event
         * dispatcher instance is available permanently.
         *
         * If we would use it, we would lose all event listeners with each run.
         */
        parent::__construct($options, $dispatcher);

        $this->kernel = $kernel;

        // boot KernelInterface instances
        if ($this->getOptions()->get('kernel_boot', true)) {
            $this->setUp(array(__CLASS__, 'boot')/* , 256 */);
        }

        // terminate TerminableInterface instances
        if ($this->getOptions()->get('kernel_terminate', true)) {
            $this->tearDown(array(__CLASS__, 'terminate')/* , 512 */);
        }

        // shutdown KernelInterface instances
        if ($this->getOptions()->get('kernel_shutdown', true)) {
            $this->tearDown(array(__CLASS__, 'shutdown')/* , 1024 */);
        }
    }

    /**
     * Returns a HttpKernelInterface instance.
     *
     * @return HttpKernelInterface
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        return $this->kernel->handle($request, $type, $catch);
    }

    /**
     * Boots the current kernel.
     *
     * @param SetUpEvent $event A SetUpEvent instance
     * @return void
     *
     * @see KernelInterface::boot
     */
    static public function boot(SetUpEvent $event)
    {
        $kernel = $event->getContainer()->getKernel();
        if ($kernel instanceof KernelInterface) {
            $kernel->boot();
        }
    }

    /**
     * Terminates the current kernel.
     *
     * @param TearDownEvent $event A TearDownEvent instance
     * @return void
     *
     * @see TerminableInterface::terminate
     */
    static public function terminate(TearDownEvent $event)
    {
        $kernel = $event->getContainer()->getKernel();
        if ($kernel instanceof TerminableInterface) {
            $kernel->terminate($event->getRequest(), $event->getResponse());
        }
    }

    /**
     * Shutdowns the current kernel.
     *
     * @param TearDownEvent $event A TearDownEvent instance
     * @return void
     *
     * @see KernelInterface::shutdown
     */
    static public function shutdown(TearDownEvent $event)
    {
        $kernel = $event->getContainer()->getKernel();
        if ($kernel instanceof KernelInterface) {
            $kernel->shutdown();
        }
    }
}
