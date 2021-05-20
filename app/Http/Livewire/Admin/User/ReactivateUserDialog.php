<?php

namespace App\Http\Livewire\Admin\User;

use App\Http\Livewire\Concerns\HandlesReactivateDialogInteraction;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class ReactivateUserDialog extends Component
{
    use AuthorizesRequests,
        HandlesReactivateDialogInteraction,
        InteractsWithBanner;

    private $eloquentRepository = User::class;

    public $confirmingReactivateUser = false;

    public $listeners = [
        'confirmReactivate',
        'closeConfirmReactivate',
    ];

    public function reactivateUser(UserService $users)
    {
        $this->authorize('admin.access.users.reactivate');

        $users->mark($this->model, (int) 1);

        $this->emit('userReactivated');

        $this->confirmingReactivate = false;

        session()->flash('flash.banner', 'User Reactivated!');
        session()->flash('falsh.bannerStyle', 'success');

        return redirect()->route('admin.users');
    }

    public function render()
    {
        return view('admin.users.reactivate', [
            'user' => $this->model,
        ]);
    }
}
