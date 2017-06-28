<?php

namespace Dhii\Di\FuncTest;

use Dhii\Di\AbstractContainer;
use Xpmock\TestCase;

/**
 * Tests {@see Dhii\Di\AbstractServiceProvider}.
 *
 * @since [*next-version*]
 */
class AbstractServiceProviderTest extends TestCase
{
    /**
     * The name of the test subject.
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Di\\AbstractServiceProvider';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $definitions Definitions for the provider to provide.
     *
     * @return AbstractContainer
     */
    public function createInstance(array $definitions = array())
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
            ->new();

        return $mock;
    }

    /**
     * Create a service definition that returns a simple value.
     *
     * @param mixed $value The value that the service definition will return.
     *
     * @return callable A service definition that will return the given value.
     */
    public function createDefinition($value)
    {
        return function ($container = null, $previous = null) use ($value) {
            return $value;
        };
    }

    /**
     * Tests the service getter method to ensure that all services are correctly retrieved in an array.
     *
     * @since [*next-version*]
     */
    public function testGetServices()
    {
    }
}
