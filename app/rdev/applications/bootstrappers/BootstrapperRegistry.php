<?php
/**
 * Copyright (C) 2015 David Young
 *
 * Defines the bootstrapper registry
 */
namespace RDev\Applications\Bootstrappers;
use RDev\Applications\Environments\Environment;
use RDev\Applications\Paths;
use RuntimeException;

class BootstrapperRegistry implements IBootstrapperRegistry
{
    /** @var Environment The current environment */
    private $environment = null;
    /** @var Paths The application paths */
    private $paths = null;
    /** @var array The list of deferred bootstrapper classes */
    private $bindingsToLazyBootstrapperClasses = [];
    /** @var array The list of expedited bootstrapper classes */
    private $eagerBootstrapperClasses = [];
    /** @var array The list of bootstrapper classes to their instances */
    private $instances = [];

    /**
     * @param Paths $paths The application paths
     * @param Environment $environment The current environment
     */
    public function __construct(Paths $paths, Environment $environment)
    {
        $this->paths = $paths;
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getBindingsToLazyBootstrapperClasses()
    {
        return $this->bindingsToLazyBootstrapperClasses;
    }

    /**
     * {@inheritdoc}
     */
    public function getEagerBootstrapperClasses()
    {
        return $this->eagerBootstrapperClasses;
    }

    /**
     * {@inheritdoc}
     */
    public function getInstance($bootstrapperClass)
    {
        if(!isset($this->instances[$bootstrapperClass]))
        {
            $this->instances[$bootstrapperClass] = new $bootstrapperClass($this->paths, $this->environment);
        }

        $bootstrapper = $this->instances[$bootstrapperClass];

        if(!$bootstrapper instanceof Bootstrapper)
        {
            throw new RuntimeException("\"$bootstrapperClass\" does not extend Bootstrapper");
        }

        return $bootstrapper;
    }

    /**
     * {@inheritdoc}
     */
    public function registerEagerBootstrapper($eagerBootstrapperClasses)
    {
        $eagerBootstrapperClasses = (array)$eagerBootstrapperClasses;
        $this->eagerBootstrapperClasses = array_merge($this->eagerBootstrapperClasses, $eagerBootstrapperClasses);
    }

    /**
     * {@inheritdoc}
     */
    public function registerLazyBootstrapper($bindings, $lazyBootstrapperClass)
    {
        $bindings = (array)$bindings;

        foreach($bindings as $boundClass)
        {
            $this->bindingsToLazyBootstrapperClasses[$boundClass] = $lazyBootstrapperClass;
        }
    }
}