<?php

namespace App\Http\Livewire;

use App\Models\Menu;
use App\Support\Concerns\InteractsWithBanner;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MenusTable extends DataTableComponent
{
    use InteractsWithBanner;

    public $status;

    protected $listeners = [
        'menuCreated' => 'menuCreated',
        'itemUpdated' => 'itemUpdated',
        'deleted' => 'deleted',
        'restored' => 'restored',
        // 'menuDeactivated' => 'menuDeactivated',
        // 'menuReactivated' => 'menuReactivated',
        'refreshDatatable' => '$refresh',
    ];

    public function updated()
    {
        parent::updated();
    }

    public function itemUpdated()
    {
        $this->emit('refreshDatatable');
        $this->banner('Successfully saved changes!');
    }

    public function restored()
    {
        $this->emit('refreshDatatable');
        return redirect('/admin/auth/menus')
            ->with('flash.banner', 'Menu Restored!.')
            ->with('flash.bannerStyle', 'success');
    }

    public function menuCreated()
    {
        $this->emit('refreshDatatable');
        $this->banner('Successfully created menu!');
    }

    public function openEditor($id, $params = null)
    {
        $params = (array) json_decode($params);

        if (count($params)) {
            $this->emit('openEditor', $id, json_encode($params));
        } else {
            $this->emit('openEditor', $id);
        }
    }

    public function confirmDelete($id)
    {
        $this->emit('confirmDelete', $id);
    }

    public function confirmRestore($id)
    {
        $this->emit('confirmRestore', $id);
    }

    public function confirmReactivate($id)
    {
        $this->emit('confirmReactivate', $id);
    }

    public function confirmDeactivate($id)
    {
        $this->emit('confirmDeactivate', $id);
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = Menu::withCount('roles')
            ->with('icon')
            ->withCount('users')
            ->withCount('children');

        if ($this->status === 'deleted') {
            $query = $query->onlyTrashed();
        } elseif ($this->status === 'deactivated') {
            $query = $query->onlyDeactivated();
        } else {
            $query = $query->onlyActive()->whereNull('menu_id')->with('children');
        }

        return $query->when($this->getFilter('search'), fn ($query, $term) => $query->search($term));
    }

    public function columns(): array
    {
        return [
            Column::make(__('Group'), 'group')
                ->sortable(),
            Column::make(__('Name'), 'name')
                ->sortable(),
            Column::make(__('Icon / link'), 'link_with_art'),
            Column::make(__('Roles Count')),
            Column::make(__('Users Count'), 'users_count')
                ->sortable(),
            Column::make(__('Actions')),
            Column::make(__('Menu Items'), 'children_count'),
        ];
    }

    public function rowView(): string
    {
        return 'admin.menus.includes.row';
    }

    /**
     * @return mixed
     */
    public function render()
    {
        return view('admin.users.livewire-tables.' . config('livewire-tables.theme') . '.datatable')
            ->with([
                'columns' => $this->columns(),
                'rowView' => $this->rowView(),
                'filtersView' => $this->filtersView(),
                'customFilters' => $this->filters(),
                'rows' => $this->rows,
            ]);
    }
}