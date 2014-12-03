<?php
/**
 * Copyright (C) 2014 David Young
 *
 * Defines the interface for dependency injection containers to implement
 */
namespace RDev\IoC;

interface IContainer
{
    /**
     * Binds a class to an interface or abstract class
     *
     * @param string $interface The name of the interface to bind to
     * @param string|mixed $concreteClass Either the name of or an instance of the concrete class to bind
     * @param string|null $targetClass The name of the target class to bind on, or null if binding to all classes
     */
    public function bind($interface, $concreteClass, $targetClass = null);

    /**
     * Gets the name of the concrete class bound to the interface
     * If a target is specified, but nothing has been explicitly bound to it, then the universal binding is returned
     *
     * @param string $interface The name of the interface whose binding we want
     * @param string|null $targetClass The name of the target class whose binding we want, or null for universal bindings
     * @return string|null The name of the concrete class bound to the interface if there is one, otherwise null
     */
    public function getBinding($interface, $targetClass = null);

    /**
     * Gets whether or not an interface is bound
     * If a target is specified, but the interface has not been explicitly bound to it, then this returns false
     *
     * @param string $interface The name of the interface
     * @param string|null $targetClass The name of the class whose bindings we're checking, or null for universal bindings
     * @return bool True if the interface is bound, otherwise false
     */
    public function isBound($interface, $targetClass = null);

    /**
     * Creates an instance of the input class name
     *
     * @param string $component The name of the component to instantiate
     * @param bool $forceNewInstance True if we want to create a new instance, otherwise false
     * @param array $constructorPrimitives The primitive parameter values to pass into the constructor
     * @param array $methodCalls The array of method calls and their primitive parameter values
     *      Should be structured like so:
     *      [
     *          NAME_OF_METHOD => [VALUES_OF_PRIMITIVE_PARAMETERS],
     *          ...
     *      ]
     * @return mixed An instance of the input class
     * @throws IoCException Thrown if there was an error making the component
     */
    public function make($component, $forceNewInstance, array $constructorPrimitives = [], array $methodCalls = []);

    /**
     * Creates a new instance of the input class name
     *
     * @param string $component The name of the component to instantiate
     * @param array $constructorPrimitives The primitive parameter values to pass into the constructor
     * @param array $methodCalls The array of method calls and their primitive parameter values
     *      Should be structured like so:
     *      [
     *          NAME_OF_METHOD => [VALUES_OF_PRIMITIVE_PARAMETERS],
     *          ...
     *      ]
     * @return mixed A new instance of the input class
     * @throws IoCException Thrown if there was an error making the component
     */
    public function makeNew($component, array $constructorPrimitives = [], array $methodCalls = []);

    /**
     * Creates a shared instance of the input class name
     *
     * @param string $component The name of the component to instantiate
     * @param array $constructorPrimitives The primitive parameter values to pass into the constructor
     * @param array $methodCalls The array of method calls and their primitive parameter values
     *      Should be structured like so:
     *      [
     *          NAME_OF_METHOD => [VALUES_OF_PRIMITIVE_PARAMETERS],
     *          ...
     *      ]
     * @return mixed An instance of the input class
     * @throws IoCException Thrown if there was an error making the component
     */
    public function makeShared($component, array $constructorPrimitives = [], array $methodCalls = []);

    /**
     * Removes a binding from the container
     *
     * @param string $interface The name of the interface whose binding we're removing
     * @param string|null $targetClass The name of the target class whose binding we're removing, or null if it's universal
     */
    public function unbind($interface, $targetClass = null);
} 