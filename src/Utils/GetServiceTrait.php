<?php

namespace Fruit\Utils;

use Context;
use Exception;
use PrestaShop\PrestaShop\Adapter\ContainerBuilder;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

trait GetServiceTrait
{
    protected static function getService($service)
    {
        if (!is_null(SymfonyContainer::getInstance())) {
            return SymfonyContainer::getInstance()->get($service);
        } elseif (!empty(Context::getContext()->controller) && !is_null(Context::getContext()->controller->getContainer())) {
            return Context::getContext()->controller->get($service);
        }

        global $kernel;

        if ($kernel === null) {
            try {
                $container = ContainerBuilder::getContainer('admin', false);

                if ($container->has($service)) {
                    return $container->get($service);
                }
            } catch (Exception $e) {
            }

            try {
                $container = ContainerBuilder::getContainer('front', false);

                return $container->get($service);
            } catch (Exception $e) {
            }
        }

        try {
            return $kernel->getContainer()->get($service);
        } catch (Exception $e) {
            return null;
        }
    }
}
