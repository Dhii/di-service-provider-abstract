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
    public function createInstance(array $definitions = [], $prefix = '', array $methods = [])
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
            ->_getServicePrefix($prefix)
            ->_getServices($definitions);

        foreach ($methods as $_method => $_callback) {
            call_user_func_array([$mock, $_method], [$_callback]);
        }

        return $mock->new();
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
        $definitions = [
            'iterator' => $this->createDefinition(new \ArrayIterator([1, 2, 3])),
            'datetime' => $this->createDefinition(new \DateTime())
        ];
        $subject = $this->createInstance($definitions);
        $reflect = $this->reflect($subject);

        $this->assertEquals($definitions, $reflect->_getServices());
    }

    /**
     * Tests the service ID prefixing functionality to ensure that the service IDs are correctly generated
     * with the prefix.
     *
     * @since [*next-version*]
     */
    public function testPrefix()
    {
        $prefix    = 'foo_';
        $serviceId = 'bar';
        $expected  = $prefix . $serviceId;
        $subject   = $this->createInstance([], $prefix);
        $reflect   = $this->reflect($subject);

        $this->assertEquals($expected, $reflect->_p($serviceId));
    }

    /**
     * Tests the method callable wrapping functionality to ensure that the generated callbacks are valid.
     *
     * @since [*next-version*]
     */
    public function testCallableWrap()
    {
        $methods  = [
            'test' => $this->createDefinition('12345')
        ];
        $subject  = $this->createInstance([], '', $methods);
        $reflect  = $this->reflect($subject);
        $callback = $reflect->_m('test');

        $this->assertEquals('12345', $callback());
    }

    /**
     * Tests the service preparation utility functionality to ensure that the service definitions are
     * correctly generated.
     *
     * @since [*next-version*]
     */
    public function testPrepare()
    {
        $prefix   = 'foo_';
        $subject  = $this->createInstance([], $prefix);
        $reflect  = $this->reflect($subject);
        $prepared = $reflect->_prepare([
            'iterator' => 'getIterator',
            'datetime' => 'getDateTime'
        ]);
        $expected = [
            $prefix . 'iterator' => [$subject, 'getIterator',],
            $prefix . 'datetime' => [$subject, 'getDateTime'],
        ];

        $this->assertEquals($expected, $prepared);
    }

    /**
     * Tests the service preparation utility functionality with the second parameter to ensure that any
     * additional service definitions are added as is.
     *
     * @since [*next-version*]
     */
    public function testPrepareWithAdditional()
    {
        $prefix   = 'foo_';
        $subject  = $this->createInstance([], $prefix);
        $reflect  = $this->reflect($subject);
        $extra    = [
            'extra1' => 'some_global_function1',
            'extra2' => 'some_global_function2',
        ];
        $prepared = $reflect->_prepare([
            'iterator' => 'getIterator',
            'datetime' => 'getDateTime'
        ], $extra);
        $expected = array_merge([
            $prefix . 'iterator' => [$subject, 'getIterator',],
            $prefix . 'datetime' => [$subject, 'getDateTime'],
        ], $extra);

        $this->assertEquals($expected, $prepared);
    }
}
