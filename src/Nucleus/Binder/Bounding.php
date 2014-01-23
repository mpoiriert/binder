<?php

namespace Nucleus\Binder\Bound;

/**
 * @Annotation
 * 
 * @Target({"PROPERTY"})
 */
class Bounding
{
    public $scope = IBinder::DEFAULT_SCOPE;

    public $namespace = IBinder::DEFAULT_NAMESPACE;

    public $variableName = null;
}