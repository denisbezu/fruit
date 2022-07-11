<?php

namespace Fruit\Controller\Admin;

use Fruit\Grid\Definition\Factory\FruitsDefinitionFactory;
use Fruit\Grid\Search\FruitFilters;
use Fruit\Service\FruitService;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\ModuleActivated;
use PrestaShopBundle\Service\Grid\ResponseBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @ModuleActivated(moduleName="fruit", redirectRoute="admin_module_manage")
 */
class AdminFruitsController extends FrameworkBundleAdminController
{
    public function indexAction(FruitFilters $filters, Request $request)
    {
        $fruitGridFactory = $this->get('fruit.grid.fruit_grid_factory');
        $fruitGrid = $fruitGridFactory->getGrid($filters);

        $gridPresenter = $this->get('prestashop.core.grid.presenter.grid_presenter');

        return $this->render('@Modules/fruit/views/templates/admin/fruits/list.html.twig', [
            'fruitGrid' => $gridPresenter->present($fruitGrid),
            'enableSidebar' => true,
            'help_link' => $this->generateSidebarLink($request->attributes->get('_legacy_controller')),
        ]);
    }

    public function searchAction(Request $request)
    {
        $filterId = FruitsDefinitionFactory::GRID_ID;

        /** @var ResponseBuilder $responseBuilder */
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('fruit.grid.definition.factory.fruits_definition_factory'),
            $request,
            $filterId,
            'fruit_admin_index'
        );
    }

    public function syncAction()
    {
        /** @var FruitService $fruitService */
        $fruitService = $this->get('fruit.service.fruit_service');
        $result = $fruitService->synchronizeFruits();

        return $this->redirectToRoute('fruit_admin_index', [
            'sync_result' => $result,
        ]);
    }
}
