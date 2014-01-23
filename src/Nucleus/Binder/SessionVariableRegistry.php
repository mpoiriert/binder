<?php

namespace Nucleus\Binder;


class SessionVariableRegistry implements IVariableRegistry
{
    private $scope = null;

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

        return $_SESSION[$name];
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function has($name)
    {
        return array_key_exists($name,$_SESSION);
    }
}