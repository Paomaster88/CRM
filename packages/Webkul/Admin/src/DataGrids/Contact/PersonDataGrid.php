<?php

namespace Webkul\Admin\DataGrids\Contact;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Webkul\UI\DataGrid\DataGrid;

class PersonDataGrid extends DataGrid
{
    protected $organizations = [];

    protected $redirectRow = [
        "id"    => "id",
        "route" => "admin.contacts.persons.edit",
    ];

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('persons')
            ->addSelect(
                'persons.id',
                'persons.name',
                'persons.emails',
                'persons.contact_numbers',
                'organizations.name as organization'
            )
            ->leftJoin('organizations', 'persons.organization_id', '=', 'organizations.id');

        $this->addFilter('id', 'persons.id');
        $this->addFilter('name', 'persons.name');
        $this->addFilter('organization', 'organizations.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'             => 'id',
            'type'              => 'hidden',
            'searchable'        => true,
        ]);

        $this->addColumn([
            'index'             => 'name',
            'label'             => trans('admin::app.datagrid.name'),
            'type'              => 'string',
            'searchable'        => true,
            'sortable'          => true,
            'filterable_type'   => 'add'
        ]);

        $this->addColumn([
            'index'             => 'emails',
            'label'             => trans('admin::app.datagrid.emails'),
            'type'              => 'string',
            'searchable'        => true,
            'sortable'          => false,
            'filterable_type'   => 'add',
            'closure'           => function ($row) {
                $emails = json_decode($row->emails, true);

                if ($emails) {
                    $emails = \Arr::pluck($emails, 'value');
    
                    return implode(', ', $emails);
                }
            },
        ]);

        $this->addColumn([
            'index'             => 'contact_numbers',
            'label'             => trans('admin::app.datagrid.contact_numbers'),
            'type'              => 'string',
            'searchable'        => true,
            'sortable'          => false,
            'filterable_type'   => 'add',
            'closure'           => function ($row) {
                $contactNumbers = json_decode($row->contact_numbers, true);

                if ($contactNumbers) {
                    $contactNumbers = \Arr::pluck($contactNumbers, 'value');
    
                    return implode(', ', $contactNumbers);
                }
            },
        ]);

        $this->addColumn([
            'index'              => 'organization',
            'label'              => trans('admin::app.datagrid.organization_name'),
            'type'               => 'string',
            'searchable'         => true,
            'sortable'           => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => app('\Webkul\Contact\Repositories\OrganizationRepository')->get(['id as value', 'name as label'])->toArray(),
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('ui::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.contacts.persons.edit',
            'icon'   => 'pencil-icon',
        ]);

        $this->addAction([
            'title'        => trans('ui::app.datagrid.delete'),
            'method'       => 'DELETE',
            'route'        => 'admin.contacts.persons.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => trans('admin::app.contacts.persons.person')]),
            'icon'         => 'trash-icon',
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('ui::app.datagrid.delete'),
            'action' => route('admin.contacts.persons.mass_delete'),
            'method' => 'PUT',
        ]);
    }
}
