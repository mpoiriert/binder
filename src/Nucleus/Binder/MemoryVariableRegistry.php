<?php

namespace Nucleus\Binder;

/**
 * This class should only be use for testing purpose. You should proper registry in real context
 */
class MemoryVariableRegistry implements IVariableRegistry
{
    private $scope = null;

    /**
     * @var array
     */
    private $entries = array();

    public function __construct($scope = IBinder::DEFAULT_SCOPE)
    {
        $this->scope = $scope;
    }

    public function getScopeName()
    {
        return $this->scope;
    }

    public function get($name)
    {
        if(!$this->has($name)) {
            return null;
        }

        return $this->entries[$name];
    }

    public function set($name, $value)
    {
        $this->entries[$name] = $value;
    }

    public function has($name)
    {
        return array_key_exists($name,$this->entries);
    }
}