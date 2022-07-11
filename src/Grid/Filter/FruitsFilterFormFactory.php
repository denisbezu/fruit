<?php

namespace Fruit\Grid\Filter;

use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use PrestaShop\PrestaShop\Core\Grid\Filter\GridFilterFormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FruitsFilterFormFactory implements GridFilterFormFactoryInterface
{
    /**
     * @var GridFilterFormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param GridFilterFormFactoryInterface $formFactory
     * @param UrlGeneratorInterface $urlGenerator
     * @param RequestStack $requestStack
     */
    public function __construct(
        GridFilterFormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        RequestStack $requestStack
    ) {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function create(GridDefinitionInterface $definition)
    {
        $fruitsFilterForm = $this->formFactory->create($definition);

        $newFunnelFormBuilder = $fruitsFilterForm->getConfig()->getFormFactory()->createNamedBuilder(
            $definition->getId(),
            FormType::class
        );

        /** @var FormInterface $fruitFromItem */
        foreach ($fruitsFilterForm as $fruitFromItem) {
            $newFunnelFormBuilder->add(
                $fruitFromItem->getName(),
                get_class($fruitFromItem->getConfig()->getType()->getInnerType()),
                $fruitFromItem->getConfig()->getOptions()
            );
        }

        $request = $this->requestStack->getCurrentRequest();

        if (null !== $request) {
            $newActionUrl = $this->urlGenerator->generate('fruit_admin_index_search');
            $newFunnelFormBuilder->setAction($newActionUrl);
        }

        return $newFunnelFormBuilder->getForm();
    }
}
