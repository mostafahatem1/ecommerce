<?php

namespace App\Http\Livewire\Backend\Navbar;

use Livewire\Component;

class NotificationComponent extends Component
{

    public $notificationCount = '';
    public $unreadNotifications;

    public function getListeners(): array
    {
        $userId = auth()->id();
        return [
            "echo-notification:App.Models.User.{$userId},notification" => 'mount'
        ];
    }

    public function mount()
    {
        $this->notificationCount = auth()->user()->unreadNotifications()->count();
        $this->unreadNotifications = auth()->user()->unreadNotifications()->get();
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->unreadNotifications->where('id', $id)->first();
        $notification->markAsRead();
        return redirect()->to($notification->data['order_url']);
    }

    public function render()
    {
        return view('livewire.backend.navbar.notification-component');
    }
}
