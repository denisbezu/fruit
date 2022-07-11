<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'fruit/vendor/autoload.php';

use Fruit\Hook\HookDispatcher;
use Fruit\Install\Installer;
use Fruit\Utils\GetServiceTrait;
use Psr\Container\ContainerInterface;

class Fruit extends Module
{
    use GetServiceTrait;

    /** @var array */
    public $ps_versions_compliancy = [
        'min' => '1.7.6.0',
        'max' => '1.7.9.99',
    ];

    /** @var string */
    public $php_version_required = '7.0';

    /**
     * @var array
     */
    public $controllers = [];

    public $hooks = [];

    /**
     * @var HookDispatcher
     */
    protected $hookDispatcher;

    /**
     * Constructor of module
     */
    public function __construct()
    {
        $this->name = 'fruit';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Denys BEZUHLYI';
        $this->need_instance = 1;
        $this->bootstrap = true;

        parent::__construct();
        $this->displayName = $this->trans('Fruits', [], 'Modules.Fruits.Admin');
        $this->description = $this->trans('Show fruits from API', [], 'Modules.Fruits.Admin');
        $this->hookDispatcher = new HookDispatcher($this);
        $this->hooks = array_merge($this->hooks, $this->hookDispatcher->getAvailableHooks());
    }

    public function getContent()
    {
        $url = Context::getContext()->link->getAdminLink('AdminFruitFruits');
        Tools::redirectAdmin($url);
    }

    public function install()
    {
        $result = parent::install();

        $installer = $this->getInstaller();
        $result &= $installer->createTables();
        $result &= $installer->installTabs();

        if (empty($this->hooks)) {
            return $result;
        }

        foreach ($this->hooks as $hook) {
            $this->registerHook($hook);
        }

        return $result;
    }

    public function uninstall()
    {
        $result = true;
        $installer = $this->getInstaller();

        $result &= $installer->removeTables();
        $result &= $installer->uninstallTabs();

        return parent::uninstall() && $result;
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    protected function getInstaller()
    {
        /** @var ContainerInterface $container */
        $container = $this->get('service_container');

        return new Installer($container);
    }

    public function __call($name, $arguments)
    {
        $result = false;
        if ($this->hookDispatcher != null) {
            $moduleHookResult = $this->hookDispatcher->dispatch($name, $arguments);
            if ($moduleHookResult != null) {
                $result = $moduleHookResult;
            }
        }

        return $result;
    }

    public function get($serviceName)
    {
        return self::getService($serviceName);
    }
}
