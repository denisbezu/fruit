<?php

namespace Fruit\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\SimpleGridAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\IdentifierColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShopBundle\Form\Admin\Type\DateRangeType;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FruitsDefinitionFactory extends AbstractGridDefinitionFactory
{
    const GRID_ID = 'fruits';

    protected function getId()
    {
        return self::GRID_ID;
    }

    protected function getName()
    {
        return $this->trans('Fruits', [], 'Modules.Fruit.Admin');
    }

    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new IdentifierColumn('id_fruit'))
                ->setName($this->trans('ID', [], 'Modules.Fruit.Admin'))
                ->setOptions([
                    'identifier_field' => 'id_fruit',
                ])
            )
            ->add((new DataColumn('name'))
                ->setName($this->trans('Fruit name', [], 'Modules.Fruit.Admin'))
                ->setOptions([
                    'field' => 'name',
                ])
            )
            ->add((new DataColumn('family'))
                ->setName($this->trans('Family', [], 'Modules.Fruit.Admin'))
                ->setOptions([
                    'field' => 'family',
                ])
            )
            ->add((new DataColumn('order'))
                ->setName($this->trans('Order', [], 'Modules.Fruit.Admin'))
                ->setOptions([
                    'field' => 'order',
                ])
            )
            ->add((new DataColumn('genus'))
                ->setName($this->trans('Genus', [], 'Modules.Fruit.Admin'))
                ->setOptions([
                    'field' => 'genus',
                ])
            )
            ->add((new DataColumn('date_add'))
                ->setName($this->trans('Date add', [], 'Modules.Fruit.Admin'))
                ->setOptions([
                    'field' => 'date_add',
                ])
            )
            ->add((new ActionColumn('actions')));
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilters()
    {
        $actionsTypeOptions = [
            'reset_route' => 'admin_common_reset_search_by_filter_id',
            'reset_route_params' => [
                'filterId' => self::GRID_ID,
            ],
            'redirect_route' => 'fruit_admin_index',
        ];

        return (new FilterCollection())
            ->add((new Filter('id_fruit', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('ID', [], 'Modules.Fruit.Admin'),
                    ],
                ])
                ->setAssociatedColumn('id_fruit')
            )
            ->add((new Filter('name', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Fruit name', [], 'Modules.Fruit.Admin'),
                    ],
                ])
                ->setAssociatedColumn('name')
            )
            ->add((new Filter('family', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Family', [], 'Modules.Fruit.Admin'),
                    ],
                ])
                ->setAssociatedColumn('family')
            )
            ->add((new Filter('order', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Order', [], 'Modules.Fruit.Admin'),
                    ],
                ])
                ->setAssociatedColumn('order')
            )
            ->add((new Filter('genus', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Genus', [], 'Modules.Fruit.Admin'),
                    ],
                ])
                ->setAssociatedColumn('genus')
            )
            ->add((new Filter('date_add', DateRangeType::class))
                ->setTypeOptions([
                    'required' => false,
                ])
                ->setAssociatedColumn('date_add')
            )
            ->add((new Filter('actions', SearchAndResetType::class))
                ->setTypeOptions($actionsTypeOptions)
                ->setAssociatedColumn('actions'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getGridActions()
    {
        return (new GridActionCollection())
            ->add((new SimpleGridAction('common_refresh_list'))
                ->setName($this->trans('Refresh list', [], 'Admin.Advparameters.Feature'))
                ->setIcon('refresh')
            )
            ->add((new SimpleGridAction('common_show_query'))
                ->setName($this->trans('Show SQL query', [], 'Admin.Actions'))
                ->setIcon('code')
            )
            ->add((new SimpleGridAction('common_export_sql_manager'))
                ->setName($this->trans('Export to SQL Manager', [], 'Admin.Actions'))
                ->setIcon('storage')
            );
    }
}
