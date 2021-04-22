<?php

namespace App\MenuSystem;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MenusLayoutComposer
{
    public $user;

    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->user = auth()->user();
        $this->menuService = $menuService;
    }

    public function compose(View $view)
    {

        /**
         * Todo: extract the values in this data array to an interface
         */
        $data = [
            'user_icon_styles' => $this->getUserSettings('icons'),
            // 'user_navbar_styles'  => $this->getUserSettings('navbar'),
            'user_hotlink_styles' => $this->getUserSettings('hotlink'),
            'user_sidebar_styles' => $this->getUserSettings('sidebar'),

            'admin_menus' => $this->getAdminMenus(),
            'hotlink_menus' => $this->getHotlinkMenus(),
            'office_menus' => $this->getOfficeMenus(),

            'database_tables' => $this->getTables(),

            'full_name' => $this->getFullName(),
            'first_name' => $this->getFirstName(),

            'is_admin' => $this->getIsAdmin(),
        ];



        $data['user_navbar_styles'] = $this->mergeStyles(config('ui.navbar_bootstrap4_classes'), $this->getUserSettings('navbar'));

        $view->with($data);
    }

    /** Get all the table names top appear in the sidebar */
    protected function getTables()
    {
        $db = DB::connection(config('database.default', 'sqlite'));
        $driver = (DB::connection(config('database.default', 'sqlite'))->getPdo())->getAttribute(\PDO::ATTR_DRIVER_NAME);

        switch ($driver) {
            case 'sqlite':
                $tables = array_map('current', $db->select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;"));

                break;

            default:
                $tables = array_map('current', $db->select('SHOW TABLES'));

                break;
        }

        return $tables;
    }
    protected function getUserSettings($key)
    {
        $userIconStyles = isset(($this->user)->settings[$key]) ?
            ($this->user)->settings[$key] :
            null;

        return $userIconStyles;
    }
    protected function getAdminMenus()
    {
        $adminMenus = $this->menuService->getAllMenusInGroup('admin');

        return $adminMenus;
    }
    protected function getHotlinkMenus()
    {
        return Auth::user()->menus()->whereNull('menus.menu_id')->where('active', 1)->with('icon', 'children')->get();
    }
    protected function getOfficeMenus()
    {
        $officeMenus = $this->menuService->getAllMenusInGroup('office');

        return $officeMenus;
    }
    protected function getFullName()
    {
        $fullName = $this->user->full_name ?? $this->user->name;

        return $fullName;
    }
    protected function getFirstName()
    {
        $firstName = $this->user->first_name ?? $this->user->name;

        return $firstName;
    }
    protected function getIsAdmin()
    {
        $isAdmin = $this->user->hasAnyRole('Administrator', 'admin');

        return $isAdmin;
    }

    protected function mergeStyles($defaults, $userStyles)
    {
        if (strlen($userStyles) < 5) {
            return $defaults;
        }

        return $userStyles;
    }
}
