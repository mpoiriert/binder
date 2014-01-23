<?php

namespace Nucleus\Binder;

use ReflectionClass;
use ReflectionProperty;

/**
 * @author Martin Poirier Théorêt <mpoiriert@gmail.com>
 */
class Binder implements IBinder
{
    /**
     * Array of restore parameters call that need to be use for the bindAll method call
     *
     * @var array
     */
    private $autoBinding = array();

    /**
     * @var IVariableRegistry[]
     */
    private $variableRegistries = array();

    /**
     * @param IVariableRegistry $registry
     */
    public function registerVariableRegistry(IVariableRegistry $registry)
    {
        $this->variableRegistries[$registry->getScopeName()] = $registry;
    }

    /**
     * @param $object
     * @param $propertyName
     * @param string $namespace
     * @param string $scope
     */
    public function bind($object, $propertyName, $namespace = 'default', $scope = 'default')
    {
        $registry = $this->getVariableRegistry($scope);
        $fullVariableName = $this->getFullVariableName($namespace,$propertyName);
        $registry->set($fullVariableName,$this->getPropertyValue($object,$propertyName));
    }

    /**
     * @param $object
     * @param $propertyName
     * @param string $namespace
     * @param string $scope
     */
    public function restore($object, $propertyName, $namespace = 'default', $scope = 'default')
    {
        $this->autoBinding[] = get_defined_vars();
        $registry = $this->getVariableRegistry($scope);
        $fullVariableName = $this->getFullVariableName($namespace,$propertyName);
        if(!$registry->has($fullVariableName)) {
            return;
        }

        $this->setPropertyValue($object,$propertyName,$registry->get($fullVariableName));
    }

    public function bindAll()
    {
        foreach($this->autoBinding as $bindingParameters) {
            call_user_func_array(array($this,'bind'),$bindingParameters);
        }
    }

    /**
     * @param $scope
     * @return IVariableRegistry
     * @throws InvalidScopeException
     */
    private function getVariableRegistry($scope)
    {
        if(!array_key_exists($scope,$this->variableRegistries)) {
            throw new InvalidScopeException(InvalidScopeException::formatMessage($scope));
        }

        return $this->variableRegistries[$scope];
    }

    private function getPropertyValue($object,$propertyName)
    {
        if(!$property = $this->getProperty($propertyName,$object)) {
            return;
        }

        $this->setPropertyAccessibility($property);

        $value = $property->getValue($object);

        $this->setPropertyAccessibility($property, true);

        return $value;
    }

    private function setPropertyValue($object, $propertyName, $value)
    {
        if(!$property = $this->getProperty($propertyName,$object)) {
            return;
        }

        $this->setPropertyAccessibility($property);

        $property->setValue($object, $value);

        $this->setPropertyAccessibility($property, true);
    }

    private function setPropertyAccessibility(ReflectionProperty $property, $initialState = false)
    {
        if(!($property->isPrivate() || $property->isProtected())) {
            return;
        }

        $property->setAccessible($initialState ? false : true);
    }

    private function getProperty($name, $object)
    {
        $class = new ReflectionClass(get_class($object));

        do {
            if ($class->hasProperty($name)) {
                return $class->getProperty($name);
            }
        } while ($class = $class->getParentClass());

        return null;
    }

    private function getFullVariableName($namespace, $variableName)
    {
        return 'binding.' . $namespace . '.' . $variableName;
    }
}
