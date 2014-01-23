<?php

namespace Nucleus\Binder;

interface IBinder
{
    const NUCLEUS_SERVICE_NAME = 'nucleus.binder';

    const DEFAULT_SCOPE = 'default';

    const DEFAULT_NAMESPACE = 'default';

    /**
     * Register a variable registry that will handle bind/restore of a specific scope
     *
     * @param IVariableRegistry $registry
     *
     * @return
     */
    public function registerVariableRegistry(IVariableRegistry $registry);

    /**
     * Bind a object property
     *
     * @param $object
     * @param $propertyName
     * @param string $namespace
     * @param string $scope
     *
     * @throws InvalidScopeException
     *
     * @return null
     */
    public function bind($object, $propertyName, $namespace = self::DEFAULT_NAMESPACE, $scope = self::DEFAULT_SCOPE);

    /**
     * Restore a object property
     *
     * @param $object
     * @param $propertyName
     * @param string $namespace
     * @param string $scope
     *
     * @throws InvalidScopeException
     *
     * @return null
     */
    public function restore($object, $propertyName, $namespace = self::DEFAULT_NAMESPACE, $scope = self::DEFAULT_SCOPE);

    /**
     * Calling this method will call a bind equivalent of every restore call that have been made prior to this call.
     * Normally this should be call on the shutdown process of your application.
     *
     * @return null
     */
    public function bindAll();
}