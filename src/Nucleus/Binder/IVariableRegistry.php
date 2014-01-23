<?php

namespace Nucleus\Binder;

interface IVariableRegistry
{
    public function getScopeName();

    public function get($name);

    public function set($name, $value);

    public function has($name);
}