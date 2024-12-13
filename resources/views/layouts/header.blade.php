<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <img src="{{asset('logo.png')}}" alt="Progress Up Logo" class="app-brand-logo">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-alt"></i>
                <div data-i18n="Analytics">Home</div>
            </a>
        </li>


        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Interface</span>
        </li>
        <li class="menu-item {{ request()->routeIs('parent.index', 'student.index', 'room.index, staff.index') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bxs-school"></i>
                <div data-i18n="Account Settings">My School</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('parent.index') ? 'active' : '' }}">
                    <a href="{{ route('parent.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-user"></i>
                        <div data-i18n="Account">Parents</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('student.index') ? 'active' : '' }}">
                    <a href="{{ route('student.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bxs-graduation"></i>
                        <div data-i18n="Account">Students</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('room.index') ? 'active' : '' }}">
                    <a href="{{ route('room.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-building-house"></i>
                        <div data-i18n="Connections">Branches</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('staff.index') ? 'active' : '' }}">
                    <a href="{{route('staff.index')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-group"></i>
                        <div data-i18n="Staff">Staff</div>
                    </a>
                </li>
            </ul>
        </li>


        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Relationship</span>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-envelope"></i>
                <div data-i18n="Patient Records">Messaging</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Reports</span>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart"></i>
                <div data-i18n="Patient Records">Reporting</div>
            </a>
        </li>
    </ul>
</aside>
