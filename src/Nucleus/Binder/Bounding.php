<?php

namespace Nucleus\Binder;

/**
 * @Annotation
 *
 * @Target({"PROPERTY"})
 */
class Bounding
{
    public $scope = IBinder::DEFAULT_SCOPE;

    public $namespace = null;

    public $variableName = null;
}