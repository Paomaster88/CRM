<?php

namespace Webkul\Admin\DataGrids\Setting;

use Webkul\UI\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class RoleDataGrid extends DataGrid
{
    protected $redirectRow = [
        "id"    => "id",
        "route" => "admin.settings.roles.edit",
    ];

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('roles')
            ->addSelect(
                'roles.id',
                'roles.name',
                'roles.description',
                'roles.permission_type'
            );

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'           => 'id',
            'head_style'      => 'width: 50px',
            'label'           => trans('admin::app.datagrid.id'),
            'type'            => 'string',
            'searchable'      => true,
            'sortable'        => true,
            'filterable_type' => 'add'
        ]);

        $this->addColumn([
            'index'           => 'name',
            'label'           => trans('admin::app.datagrid.name'),
            'type'            => 'string',
            'searchable'      => true,
            'sortable'        => true,
            'filterable_type' => 'add'
        ]);

        $this->addColumn([
            'index'      => 'description',
            'label'      => trans('admin::app.datagrid.description'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'              => 'permission_type',
            'label'              => trans('admin::app.datagrid.permission_type'),
            'type'               => 'boolean',
            'searchable'         => true,
            'sortable'           => false,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.settings.roles.all'),
                    'value' => 'all',
                ], [
                    'label' => trans('admin::app.settings.roles.custom'),
                    'value' => 'custom',
                ],
            ],
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('ui::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.settings.roles.edit',
            'icon'   => 'pencil-icon',
        ]);

        $this->addAction([
            'title'        => trans('ui::app.datagrid.delete'),
            'method'       => 'DELETE',
            'route'        => 'admin.settings.roles.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'user']),
            'icon'         => 'trash-icon',
        ]);
    }

    public function prepareMassActions()
    {
    }
}
