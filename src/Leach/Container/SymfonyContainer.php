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
    protected $defaults = array(
        'kernel_boot' => true,
        'kernel_terminate' => true,
        'kernel_shutdown' => true,
    );

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
        parent::__construct($options, $dispatcher);

        $this->kernel = $kernel;

        // boot KernelInterface instances
        if ($this->getOptions()->get('kernel_boot', true) &&
            $this->kernel instanceof KernelInterface
        ) {
            $this->setUp(array(__CLASS__, 'boot')/* , 256 */);
        }

        // terminate TerminableInterface instances
        if ($this->getOptions()->get('kernel_terminate', true) &&
            $this->kernel instanceof TerminableInterface
        ) {
            $this->tearDown(array(__CLASS__, 'terminate')/* , 512 */);
        }

        // shutdown KernelInterface instances
        if ($this->getOptions()->get('kernel_shutdown', true) &&
            $this->kernel instanceof KernelInterface
        ) {
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
        $event->getContainer()->getKernel()->boot();
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
        $event->getContainer()->getKernel()->terminate(
            $event->getRequest(),
            $event->getResponse()
        );
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
        $event->getContainer()->getKernel()->shutdown();
    }
}
