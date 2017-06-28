<?php

namespace Dhii\Di;

use InvalidArgumentException;
use Dhii\I18n\StringTranslatorConsumingTrait;
use Dhii\I18n\StringTranslatorAwareTrait;
use Traversable;

/**
 * Abstract implementation of an object that can provide services.
 *
 * @since [*next-version*]
 */
abstract class AbstractStaticServiceProvider extends AbstractServiceProvider
{
    use StringTranslatorConsumingTrait;
    use StringTranslatorAwareTrait;

    /**
     * The service definitions.
     *
     * @since [*next-version*]
     *
     * @var callable[]
     */
    protected $serviceDefinitions = [];

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getServices()
    {
        return $this->serviceDefinitions;
    }

    /**
     * Adds a service definition to this provider.
     *
     * @since [*next-version*]
     *
     * @param string   $id         The ID of the service definition.
     * @param callable $definition The service definition.
     *
     * @throws InvalidArgumentException If definition is invalid.
     */
    protected function _addService($id, $definition)
    {
        // Checking only format, because the definition may become available later
        if (!is_callable($definition, true)) {
            throw new InvalidArgumentException($this->__('The definition for service with ID "%1$s" must be a callable', [$id]));
        }

        $this->serviceDefinitions[$id] = $definition;

        return $this;
    }

    /**
     * Adds multiple service definitions to this provider.
     *
     * @since [*next-version*]
     *
     * @param array|Traversable $definitions An associative array of service definitions mapped by string keys.
     *
     * @return $this This instance.
     */
    protected function _addServices($definitions)
    {
        foreach ($definitions as $_id => $_definition) {
            $this->_addService($_id, $_definition);
        }

        return $this;
    }
}
