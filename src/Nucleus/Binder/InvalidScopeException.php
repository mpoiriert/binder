<?php

namespace Nucleus\Binder;

class InvalidScopeException extends \RuntimeException
{
    public static function formatMessage($scope)
    {
        return 'The scope [' . $scope . '] cannot be found';
    }
}