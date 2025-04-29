<div>
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <!-- Counter - Alerts -->
        <span class="badge badge-danger badge-counter">{{$notificationCount}}</span>
    </a>
    <!-- Dropdown - Alerts -->
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
         aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">
            Alerts Center
        </h6>
        @forelse($unreadNotifications as $unreadNotification)
            <a  class="dropdown-item d-flex align-items-center" wire:click="markAsRead('{{$unreadNotification->id}}')" >
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">{{$unreadNotification->data['created_date']}}</div>
                    <span class="font-weight-bold">A new order with amount ({{ $unreadNotification->data['amount'] }}) from {{ $unreadNotification->data['customer_name'] }}</span>
                </div>
            </a>
        @empty
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div>
                    <span class="font-weight-bold">No new notifications</span>
                </div>
            </a>

        @endforelse
        <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
    </div>
</div>
