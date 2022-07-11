<?php

namespace Fruit\Hook;

use Module;

class HookDispatcher
{
    protected $hookClasses = [
        LayoutHook::class,
    ];

    private $defaultHooks = [];
    /**
     * List of available hooks
     *
     * @var string[]
     */
    protected $availableHooks = [];
    /**
     * Hook classes
     */
    protected $hooks = [];
    /**
     * Module
     *
     * @var Module
     */
    protected $module;

    /**
     * Init hooks
     *
     * @param Module $module
     */
    public function __construct($module)
    {
        $this->module = $module;
        $this->availableHooks = array_merge($this->availableHooks, $this->defaultHooks);
        foreach ($this->hookClasses as $hookClass) {
            /** @var AbstractHook $hook */
            $hook = new $hookClass($this->module);
            $this->availableHooks = array_merge($this->availableHooks, $hook->getAvailableHooks());
            $this->hooks[] = $hook;
        }
    }

    /**
     * Get available hooks
     *
     * @return string[]
     */
    public function getAvailableHooks()
    {
        return $this->availableHooks;
    }

    /**
     * Find hook or widget and dispatch it
     *
     * @param string $hookName
     * @param array $params
     *
     * @return mixed|void
     */
    public function dispatch($hookName, $params = [])
    {
        $hookName = preg_replace('~^hook~', '', $hookName);
        $hookName = lcfirst($hookName);

        if (!empty($hookName)) {
            foreach ($this->hooks as $hook) {
                if (is_callable([$hook, $hookName])) {
                    return call_user_func([$hook, $hookName], $params);
                }
            }
        }

        return null;
    }

    /**
     * Get hook classes
     *
     * @return array
     */
    public function getHookClasses()
    {
        return $this->hookClasses;
    }
}
