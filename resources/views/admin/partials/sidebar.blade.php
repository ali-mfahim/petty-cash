<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header" style="background: #0c243c">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="{{route('dashboard.index')}}">
                    <span class="brand-logo">
                    </span>
                    <h2 class="brand-text">
                        <img src="{{asset('logos/white.png')}}" alt="" style="max-width :170px;">
                        <!-- KSMKLDMKLAD -->
                    </h2>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content" style="background-color: #0c243c;">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" style="background-color: #0c243c;">
            <!-- dasboard -->
            <li class=" nav-item  {{  Route::is('dashboard.*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{route('dashboard.index')}}">
                    <i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Dashboard</span></a>
            </li>
            <!-- dashboard -->


            <!-- users , roles and permissions -->
            @canany(["user-list" , "role-list" , "permission-list"])
            <li class=" nav-item @if(Route::is('users.*') || Route::is('roles.*')  || Route::is('permissions.*')  ) open active @endif">
                <a class="d-flex align-items-center" href="javascript:;">
                    <i data-feather="lock"></i>
                    <span class="menu-title text-truncate" data-i18n="Authentication">Authentication</span>
                    <!-- <span class="badge badge-light-warning rounded-pill ms-auto me-1">2</span> -->
                </a>
                <ul class="menu-content">
                    @can("user-list")
                    <li class=" {{  Route::is('users.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{route('users.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Users">Users</span>
                        </a>
                    </li>
                    @endcan
                    @can("role-list")
                    <li class="{{  Route::is('roles.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{route('roles.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Roles">Roles</span>
                        </a>
                    </li>
                    @endcan
                    @can("permission-list")
                    <li class="{{  Route::is('permissions.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{route('permissions.index')}}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Permissions">Permissions</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany
            <!-- users , roles and permissions -->
            @can("categories-list")
            <li class=" nav-item  {{  Route::is('categories.*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{route('categories.index')}}">
                    <i data-feather="grid"></i><span class="menu-title text-truncate" data-i18n="Categories">Categories</span></a>
            </li>
            @endcan


            @can("customer-list")
            <li class=" nav-item  {{  Route::is('customers.*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{route('customers.index')}}">
                    <i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Customers">Customers</span></a>
            </li>
            @endcan

            @can("coorporate-form-list")
            <li class=" nav-item  {{  Route::is('coorporate-forms.*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{route('coorporate-forms.index')}}">
                    <i data-feather="list"></i><span class="menu-title text-truncate" data-i18n="Coorporate Forms">Coorporate Forms</span></a>
            </li>
            @endcan
            @can("color-list")
            <li class=" nav-item  {{  Route::is('colors.*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{route('colors.index')}}">
                    <i data-feather="aperture"></i><span class="menu-title text-truncate" data-i18n="colors">Colors</span></a>
            </li>
            @endcan

        </ul>







    </div>
</div>