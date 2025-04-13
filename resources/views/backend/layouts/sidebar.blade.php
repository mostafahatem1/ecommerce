@php
    $current_page = \Route::currentRouteName();

@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.index') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ config('app.name') }}<sup>2</sup></div>
    </a>

    <hr class="sidebar-divider my-0">
    {{--
    |--------------------------------------------------------------------------
    | Admin Side Menu (Cached in Redis)
    |--------------------------------------------------------------------------
    | This menu data is retrieved from Redis cache for optimal performance.
    | Cache key: 'admin_side_menu'
    | Cache duration: Forever (until manually cleared or updated)
    | Structure: Nested Permission model collection with parent/child relationships
    --}}
    @role(['admin'])
        @isset($admin_side_menu)
            @foreach ($admin_side_menu as $menu)
                @php
                    $hasChildren = $menu->appearedChildren && count($menu->appearedChildren) > 0;
                    $isActive = isActiveMenu($menu, $current_page);
                @endphp

                @if (!$hasChildren)
                    <li class="nav-item {{ $isActive ? 'active' : null }}">
                        <a href="{{ route('admin.' . $menu->as) }}" class="nav-link">
                            <i class="{{ $menu->icon ?: 'fas fa-home' }}"></i>
                            <span>{{ $menu->display_name }}</span>
                        </a>
                    </li>
                    <hr class="sidebar-divider">
                @else
                    <li class="nav-item {{ $isActive ? 'active' : null }}">
                        <a class="nav-link {{ $isActive ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
                            data-target="#collapse_{{ $menu->route }}" aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                            aria-controls="collapse_{{ $menu->route }}">
                            <i class="{{ $menu->icon ?: 'fas fa-home' }}"></i>
                            <span>{{ $menu->display_name }}</span>
                        </a>
                        @if ($menu->appearedChildren)
                            <div id="collapse_{{ $menu->route }}" class="collapse {{ $isActive ? 'show' : null }}">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    @foreach ($menu->appearedChildren as $sub_menu)
                                        @php
                                            $isChildActive = isActiveMenu($sub_menu, $current_page);
                                        @endphp
                                        <a class="collapse-item {{ $isChildActive ? 'active' : null }}"
                                            href="{{ route('admin.' . $sub_menu->as) }}">
                                            {{ $sub_menu->display_name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </li>
                @endif
            @endforeach
        @else
            <div class="text-danger p-3">Menu configuration not found</div>
        @endisset
    @endrole

    @role(['supervisor'])
        @isset($admin_side_menu)
            @foreach ($admin_side_menu as $menu)
                @permission($menu->name)
                    @php
                        $hasChildren = $menu->appearedChildren && count($menu->appearedChildren) > 0;
                        $isActive = isActiveMenu($menu, $current_page);
                    @endphp

                    @if (!$hasChildren)
                        <li class="nav-item {{ $isActive ? 'active' : null }}">
                            <a href="{{ route('admin.' . $menu->as) }}" class="nav-link">
                                <i class="{{ $menu->icon ?: 'fas fa-home' }}"></i>
                                <span>{{ $menu->display_name }}</span>
                            </a>
                        </li>
                        <hr class="sidebar-divider">
                    @else
                        <li class="nav-item {{ $isActive ? 'active' : null }}">
                            <a class="nav-link {{ $isActive ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
                                data-target="#collapse_{{ $menu->route }}" aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                                aria-controls="collapse_{{ $menu->route }}">
                                <i class="{{ $menu->icon ?: 'fas fa-home' }}"></i>
                                <span>{{ $menu->display_name }}</span>
                            </a>
                            @if ($menu->appearedChildren)
                                <div id="collapse_{{ $menu->route }}" class="collapse {{ $isActive ? 'show' : null }}">
                                    <div class="bg-white py-2 collapse-inner rounded">
                                        @foreach ($menu->appearedChildren as $sub_menu)
                                            @permission($sub_menu->name)
                                                @php
                                                    $isChildActive = isActiveMenu($sub_menu, $current_page);
                                                @endphp
                                                <a class="collapse-item {{ $isChildActive ? 'active' : null }}"
                                                    href="{{ route('admin.' . $sub_menu->as) }}">
                                                    {{ $sub_menu->display_name }}
                                                </a>
                                            @endpermission
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </li>
                    @endif
                @endpermission
            @endforeach
        @else
            <div class="text-danger p-3">Menu configuration not found</div>
        @endisset
    @endrole


    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
