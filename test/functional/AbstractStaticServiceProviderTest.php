<?php

namespace Dhii\Di\FuncTest;

use Exception;
use Dhii\Di\AbstractContainer;
use Interop\Container\ServiceProvider;
use Xpmock\TestCase;

/**
 * Tests {@see Dhii\Di\AbstractStaticServiceProvider}.
 *
 * @since [*next-version*]
 */
class AbstractStaticServiceProviderTest extends TestCase
{
    /**
     * The name of the test subject.
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Di\\AbstractStaticServiceProvider';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param ServiceProvider $provider Optional service provider. Default: null
     *
     * @return AbstractContainer
     */
    public function createInstance(array $definitions = array())
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
            ->new();

        $mock->this()->serviceDefinitions = array_merge(
            $mock->this()->serviceDefinitions,
            $definitions
        );

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
        $definitions = array(
            'one' => $this->createDefinition('one'),
            'two' => $this->createDefinition(2),
            'three' => $this->createDefinition('three'),
        );
        $subject = $this->createInstance($definitions);

        $this->assertEquals($definitions, $subject->this()->_getServices());
    }

    /**
     * Tests the service definition registration method to ensure that definitions are correctly
     * registered in the provider.
     *
     * @since [*next-version*]
     */
    public function testAdd()
    {
        $subject = $this->createInstance();

        $subject->this()->_addService('test', $this->createDefinition('this is a test'));
        $subject->this()->_addService('pi', $this->createDefinition(3.14159265359));

        $this->assertArrayHasKey('test', $subject->this()->serviceDefinitions);
        $this->assertArrayHasKey('pi', $subject->this()->serviceDefinitions);
    }

    /**
     * Tests the multiple service definition registration method to ensure that definitions are
     * correctly registered in the provider.
     *
     * @since [*next-version*]
     */
    public function testAddMany()
    {
        $subject = $this->createInstance();

        $subject->this()->_addServices(array(
            'test' => $this->createDefinition('this is a test'),
            'pi' => $this->createDefinition(3.14159265359),
        ));

        $this->assertArrayHasKey('test', $subject->this()->serviceDefinitions);
        $this->assertArrayHasKey('pi', $subject->this()->serviceDefinitions);
    }

    /**
     * Tests the service definition registration method with an invalid definition to ensure that an
     * exception is thrown in such cases.
     *
     * @since [*next-version*]
     */
    public function testAddInvalidDefinition()
    {
        $subject = $this->createInstance();

        $this->setExpectedException('\\InvalidArgumentException');

        $subject->this()->_addService('test', new \DOMText('this is not a definition!'));
    }
}
