<?php

namespace Fruit\Hook;

use Context;
use Fruit\Model\FruitDisplayModel;
use Fruit\Service\FruitService;

class LayoutHook extends AbstractHook
{
    public const AVAILABLE_HOOKS = [
        'displayHome',
    ];

    /**
     * @param array $params
     *
     * @return false|string|void
     *
     * @throws \SmartyException
     */
    public function displayHome($params)
    {
        /** @var FruitService $fruitService */
        $fruitService = $this->module->get('fruit.service.fruit_service');

        $fruits = array_map(function (FruitDisplayModel $fruitDisplayModel) {
            return $fruitDisplayModel->toArray();
        }, $fruitService->getAll());

        if (empty($fruits)) {
            return;
        }

        $tpl = Context::getContext()->smarty->createTemplate('module:fruit/views/templates/front/home/fruits.tpl');

        $tpl->assign([
            'fruits' => $fruits,
        ]);

        return $tpl->fetch();
    }
}
