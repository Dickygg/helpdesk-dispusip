<ul class="navbar-nav bg-white sidebar sidebar-light" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon me-5">
            <img src="{{ asset('sb-admin/img/logo-dispusip.png')}}" alt="Dispusip" style="width: 60px; height: 60px; object-fit: contain;">
        </div>
        <div class="sidebar-brand-text me-5" style="color:  #0e4a65;"> Helpdesk.</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    @foreach(config('navigation.'. ucwords(Auth::user()->getRoleNames()->first())) as $item)

    @if(isset($item['divider']))
    <hr class="sidebar-divider">
    @elseif(isset($item['heading']))
    <div class="sidebar-heading">{{ $item['heading'] }}</div>
    @else
    <li class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($item['route']) }}">
            <i class="{{ $item['icon'] }}"></i>
            <span>{{ $item['title'] }}</span>
        </a>
    </li>
    @endif

    @endforeach

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>