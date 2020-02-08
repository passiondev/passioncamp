<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;

class OrganizationNotification extends Component
{
    use AuthorizesRequests;

    public $notifications;

    public function mount($notifications)
    {
        $this->notifications = $notifications;
    }

    public function render()
    {
        return view('livewire.organization-notification');
    }

    public function markAsRead(Request $request, $notificationId)
    {
        $notification = $request->user()->organization->notifications()->findOrFail($notificationId);

        $this->authorize('update', $notification);

        $notification->markAsRead();

        $this->notifications = $request->user()->organization->unreadNotifications()->get();
    }
}
