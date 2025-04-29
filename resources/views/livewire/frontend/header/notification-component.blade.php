<div>
    <a class="nav-link dropdown-toggle withoutAfter" id="notificationDropdown" href="#" data-toggle="dropdown"
       aria-haspopup="true" aria-expanded="false">
        <div class="avatar-with-badge">
            <img src="{{ optional(auth()->user())->user_image == null ? asset('backend/uploads/customers/avatar.svg') : asset('backend/uploads/customers/' . auth()->user()->user_image) }}" alt="" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
            <span class="badge badge-danger badge-counter">{{ $unReadNotificationsCount }}</span>
        </div>

    </a>
    <div class="dropdown-menu mt-3  notification-dropdown" aria-labelledby="notificationDropdown">
        @forelse($unReadNotifications as $notification)
            @if ($notification->type == 'App\Notifications\Frontend\Customer\OrderThanksNotification')
                <a class="dropdown-item d-flex align-items-center" wire:click="markAsRead('{{ $notification->id }}')">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">{{ $notification->data['created_date'] }}</div>
                        <span class="font-weight-bold">Order #{{ $notification->data['order_ref'] }} completed successfully.</span>
                    </div>
                </a>
            @endif

            @if ($notification->type == 'App\Notifications\Backend\Order\OrderNotification')
                <a class="dropdown-item d-flex align-items-center" wire:click="markAsRead('{{ $notification->id }}')">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">{{ $notification->data['created_date'] }}</div>
                        <span class="font-weight-bold">Order {{ $notification->data['order_ref'] }} status is {{ $notification->data['last_transaction'] }}</span>
                    </div>
                </a>
            @endif
        @empty
            <div class="dropdown-item text-center">No notifications found!</div>
        @endforelse

    </div>
</div>
