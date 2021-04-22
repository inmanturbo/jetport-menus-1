<?php

namespace App\Http\Livewire\Frontend;


use App\Http\Livewire\BaseTable;
use App\MenuSystem\Menu;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class UsersTable.
 */
class ChildrenTable extends BaseTable
{
    use HtmlComponents;

    /**
     * @var string
     */
    public $sortField = 'id';

    /**
     * @var int
     */
    public $parentId;

    /**
     * @var array
     */
    protected $options = [
        'bootstrap.container' => false,
        'bootstrap.classes.table' => 'table',
    ];

    /**
     * @param  string  $status
     */
    public function mount($parentId): void
    {
        $this->parentId = $parentId;
        $this->tableHeaderEnabled = false;
        $this->tableFooterEnabled = false;
        $this->searchEnabled = false;
        $this->paginationEnabled = false;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = Menu::with('parent', 'icon')
            ->where('menu_id', $this->parentId);

        return $query;
    }


    public function setTableRowId($model): ?string
    {
        return 'menuRow-' . $model->id;
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('Menu'), 'parent.label')
                ->sortable()
                ->searchable()
                ->format(function (Menu $model) {
                    return view('frontend.menus.includes.parent-label', ['menu' => $model]);
                }),
            Column::make(__('Group'), 'group')
                ->searchable()
                ->sortable(),
            Column::make(__('Active'), 'active')
                ->searchable()
                ->sortable()
                ->format(function (Menu $model) {
                    return view('frontend.menus.includes.active', ['menu' => $model]);
                }),
            Column::make(__('Label'), 'label')
                ->sortable()
                ->searchable(),
            Column::make(__('Icon'))
                ->format(function (Menu $model) {
                    return view('frontend.menus.includes.icon', ['menu' => $model]);
                }),
            Column::make(__('Navigation'), 'link')
                ->searchable()
                ->sortable(),
            Column::make(__('Actions'))
                ->format(function (Menu $model) {
                    return view('frontend.menus.includes.actions', ['menu' => $model]);
                }),
        ];
    }
}
