<?php

namespace Fruit\Hook;

use Module;

abstract class AbstractHook
{
    const AVAILABLE_HOOKS = [];

    /**
     * @var Module
     */
    protected $module;

    /***
     * @param Module $module
     */
    public function __construct($module)
    {
        $this->module = $module;
    }

    /**
     * @return array
     */
    public function getAvailableHooks()
    {
        return static::AVAILABLE_HOOKS;
    }

    /**
     * @param string $functionName
     *
     * @return string
     */
    protected function getHookNameFromFunction($functionName)
    {
        return lcfirst(substr($functionName, 4, strlen($functionName)));
    }
}
