<ul class="nav flex-column pt-3 pt-md-0">
    <li class="nav-item">
        <a href="{{ route('home') }}" class="nav-link d-flex align-items-center">
            <!-- Logo (Image) -->
            <img src="{{ asset('images/bindtogether-logo.png') }}" height="40" width="40" alt="Logo" class="me-3">
            <!-- Text -->
            <div style="font-family: 'Poppins', sans-serif;">
                <span class="sidebar-text" style="font-size: 20px; font-weight: bold;">Bind Together</span> <br>
                <small class="text-muted" style="font-size: 10px;">Bataan Peninsula State University</small>
            </div>
        </a>
    </li>


    @super_admin
    <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <a href="{{ route('home') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Dashboard') }}</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('newsfeed.index') ? 'active' : '' }}">
                    <a href="{{ route('newsfeed.index') }}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">{{ __('Newsfeed') }}</span>
                    </a>
                </li>

    <li class="nav-item">
        <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
            data-bs-target="#submenu-app">
            <span>
                <span class="sidebar-icon me-3">
                    <i class="fas fa-circle fa-fw"></i>
                </span>
                <span class="sidebar-text">Manage Users</span>
            </span>
            <span class="link-arrow">
                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd">
                    </path>
                </svg>
            </span>
        </span>
        <div class="multi-level collapse" role="list" id="submenu-app" aria-expanded="false">
            <ul class="flex-column nav">
                <li class="nav-item {{ request()->query('role') == 'super_admin' ? 'active' : '' }}">
                    <a href="{{ route('users.index', ['role' => 'super_admin']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Super Admin') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->query('role') == 'admin_org' ? 'active' : '' }}">
                    <a href="{{ route('users.index', ['role' => 'admin_org']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Administrators') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->query('role') == 'coach' ? 'active' : '' }}">
                    <a href="{{ route('users.index', ['role' => 'coach']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Coach') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->query('role') == 'adviser' ? 'active' : '' }}">
                    <a href="{{ route('users.index', ['role' => 'adviser']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Adviser') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->query('role') == 'student' ? 'active' : '' }}">
                    <a href="{{ route('users.index', ['role' => 'student']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Student') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>


    <li class="nav-item">
        <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
            data-bs-target="#submenu-app1">
            <span>
                <span class="sidebar-icon me-3">
                    <i class="fas fa-circle fa-fw"></i>
                </span>
                <span class="sidebar-text">Management</span>
            </span>
            <span class="link-arrow">
                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd">
                    </path>
                </svg>
            </span>
        </span>
        <div class="multi-level collapse" role="list" id="submenu-app1" aria-expanded="false">
            <ul class="flex-column nav">

                <li class="nav-item {{ request()->routeIs('campus.index') ? 'active' : '' }}">
                    <a href="{{ route('campus.index') }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Campus') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('program.index') ? 'active' : '' }}">
                    <a href="{{ route('program.index') }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Program') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('sport.index') ? 'active' : '' }}">
                    <a href="{{ route('sport.index') }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Sport') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('organization.index') ? 'active' : '' }}">
                    <a href="{{ route('organization.index') }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Organization') }}</span>
                    </a>
                </li>

            </ul>
        </div>
    </li>
    <li class="nav-item">
        <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
            data-bs-target="#submenu-app2">
            <span>
                <span class="sidebar-icon me-3">
                    <i class="fas fa-circle fa-fw"></i>
                </span>
                <span class="sidebar-text">Reported Items</span>
            </span>
            <span class="link-arrow">
                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd">
                    </path>
                </svg>
            </span>
        </span>
        <div class="multi-level collapse" role="list" id="submenu-app2" aria-expanded="false">
            <ul class="flex-column nav">
                <li class="nav-item {{ request()->routeIs('reported-comment.index') ? 'active' : '' }}">
                    <a href="{{ route('reported-comment.index') }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text" style="font-size: 14px">{{ __('Reported Comment') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('reported-post.index') ? 'active' : '' }}">
                    <a href="{{ route('reported-post.index') }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Reported Post') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
            data-bs-target="#submenu-app3">
            <span>
                <span class="sidebar-icon me-3">
                    <i class="fas fa-circle fa-fw"></i>
                </span>
                <span class="sidebar-text">Deleted Items</span>
            </span>
            <span class="link-arrow">
                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd">
                    </path>
                </svg>
            </span>
        </span>
        <div class="multi-level collapse" role="list" id="submenu-app3" aria-expanded="false">
            <ul class="flex-column nav">
                <li class="nav-item {{ request()->routeIs('deleted-comment.index') ? 'active' : '' }}">
                    <a href="{{ route('deleted-comment.index') }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text" style="font-size: 14px">{{ __('Deleted Comment') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('deleted-post.index') ? 'active' : '' }}">
                    <a href="{{ route('deleted-post.index') }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Deleted Post') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('feedback.index') ? 'active' : '' }}">
        <a href="{{ route('feedback.index') }}" class="nav-link">
            <span class="sidebar-icon me-3">
                <i class="fas fa-user-alt fa-fw"></i>
            </span>
            <span class="sidebar-text">{{ __('Contact Us') }}</span>
        </a>
    </li>
    @endsuper_admin

    @admin_sport
    <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <a href="{{ route('home') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Dashboard') }}</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('newsfeed.index') ? 'active' : '' }}">
        <a href="{{ route('newsfeed.index') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Newsfeed') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
            data-bs-target="#submenu-app">
            <span>
                <span class="sidebar-icon me-3">
                    <i class="fas fa-circle fa-fw"></i>
                </span>
                <span class="sidebar-text">Manage User</span>
            </span>
            <span class="link-arrow">
                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd">
                    </path>
                </svg>
            </span>
        </span>
        <div class="multi-level collapse" role="list" id="submenu-app" aria-expanded="false">
            <ul class="flex-column nav">
                <li class="nav-item {{ request()->query('role') == 'coach' ? 'active' : '' }}">
                    <a href="{{ route('users.index', ['role' => 'coach']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Coach') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
            data-bs-target="#submenu-app1">
            <span>
                <span class="sidebar-icon me-3">
                    <i class="fas fa-circle fa-fw"></i>
                </span>
                <span class="sidebar-text">Athlete Record</span>
            </span>
            <span class="link-arrow">
                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd">
                    </path>
                </svg>
            </span>
        </span>
        <div class="multi-level collapse" role="list" id="submenu-app1" aria-expanded="false">
            <ul class="flex-column nav">
                {{-- <li class="nav-item  {{ request()->routeIs('practice.index') ? 'active' : '' }}">
                    <a href="{{ route('practice.index', ['status' => '0']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Practice') }}</span>
                    </a>
                </li> --}}

                <li class="nav-item  {{ request()->routeIs('registered.participant') ? 'active' : '' }}">
                    <a href="{{ route('registered.participant', ['status' => '0']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text" style="font-size: 12px">{{ __('Registered Participant') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->query('status') == '1' ? 'active' : '' }}">
                    <a href="{{ route('registered.participant', ['status' => '1']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Official Player') }}</span>
                    </a>
                </li>

                <li class="nav-item  {{ request()->routeIs('registered.participant') ? 'active' : '' }}">
                    <a href="{{ route('registered.participant', ['status' => '0', 'isArchived' => 1]) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text" style="font-size: 12px">{{ __('Archived Participants') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('activity.index') ? 'active' : '' }}">
        <a href="{{ route('activity.index') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Activity') }}</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('deleted.activities') ? 'active' : '' }}">
        <a href="{{ route('deleted.activities') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Archive Activity') }}</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('calendar-of-activities') ? 'active' : '' }}">
        <a href="{{ route('calendar-of-activities') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Calendar') }}</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('report.view-sport') ? 'active' : '' }}">
        <a href="{{ route('report.view-sport') }}" class="nav-link">
            <span class="sidebar-icon me-3">
                <i class="fas fa-user-alt fa-fw"></i>
            </span>
            <span class="sidebar-text">{{ __('Reports') }}</span>
        </a>
    </li>
    @endadmin_sport


    @admin_org
    <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <a href="{{ route('home') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Dashboard') }}</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('newsfeed.index') ? 'active' : '' }}">
        <a href="{{ route('newsfeed.index') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Newsfeed') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
            data-bs-target="#submenu-app">
            <span>
                <span class="sidebar-icon me-3">
                    <i class="fas fa-circle fa-fw"></i>
                </span>
                <span class="sidebar-text">Manage User</span>
            </span>
            <span class="link-arrow">
                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd">
                    </path>
                </svg>
            </span>
        </span>
        <div class="multi-level collapse" role="list" id="submenu-app" aria-expanded="false">
            <ul class="flex-column nav">
                <li class="nav-item {{ request()->query('role') == 'adviser' ? 'active' : '' }}">
                    <a href="{{ route('users.index', ['role' => 'adviser']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Adviser') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>


    <li class="nav-item {{ request()->routeIs('activity.index') ? 'active' : '' }}">
        <a href="{{ route('activity.index') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Activity') }}</span>
        </a>
    </li>

    <!-- <li class="nav-item {{ request()->routeIs('deleted.activities') ? 'active' : '' }}">
        <a href="{{ route('deleted.activities') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Deleted Activity') }}</span>
        </a>
    </li> -->

    <li class="nav-item {{ request()->routeIs('deleted.activities') ? 'active' : '' }}">
        <a href="{{ route('deleted.activities') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Archive Activity') }}</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('calendar-of-activities') ? 'active' : '' }}">
        <a href="{{ route('calendar-of-activities') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Calendar') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
            data-bs-target="#submenu-app1">
            <span>
                <span class="sidebar-icon me-3">
                    <i class="fas fa-circle fa-fw"></i>
                </span>
                <span class="sidebar-text">Performer Record</span>
            </span>
            <span class="link-arrow">
                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd">
                    </path>
                </svg>
            </span>
        </span>
        <div class="multi-level collapse" role="list" id="submenu-app1" aria-expanded="false">
            <ul class="flex-column nav">
                <li class="nav-item {{ request()->query('status') == '0' ? 'active' : '' }}">
                    <a href="{{ route('audition.list', ['status' => '0', 'type' => '3']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Registered Participants') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->query('status') == '1' ? 'active' : '' }}">
                    <a href="{{ route('audition.list', ['status' => '1']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Official Performers') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item {{ request()->routeIs('report.view-org') ? 'active' : '' }}">
        <a href="{{ route('report.view-org') }}" class="nav-link">
            <span class="sidebar-icon me-3">
                <i class="fas fa-user-alt fa-fw"></i>
            </span>
            <span class="sidebar-text">{{ __('Reports') }}</span>
        </a>
    </li>
    @endadmin_org

    @coach
    <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <a href="{{ route('home') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Dashboard') }}</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('newsfeed.index') ? 'active' : '' }}">
        <a href="{{ route('newsfeed.index') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Newsfeed') }}</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('activity.index') ? 'active' : '' }}">
        <a href="{{ route('activity.index') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Activity') }}</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('deleted.activities') ? 'active' : '' }}">
        <a href="{{ route('deleted.activities') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Archive Activity') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
            data-bs-target="#submenu-app1">
            <span>
                <span class="sidebar-icon me-3">
                    <i class="fas fa-circle fa-fw"></i>
                </span>
                <span class="sidebar-text">Athlete Record</span>
            </span>
            <span class="link-arrow">
                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd">
                    </path>
                </svg>
            </span>
        </span>
        <div class="multi-level collapse" role="list" id="submenu-app1" aria-expanded="false">
            <ul class="flex-column nav">
                <li class="nav-item  {{ request()->routeIs('practice.index') ? 'active' : '' }}">
                    <a href="{{ route('practice.index', ['status' => '0']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Practice') }}</span>
                    </a>
                </li>

                <li class="nav-item  {{ request()->routeIs('registered.participant') ? 'active' : '' }}">
                    <a href="{{ route('registered.participant', ['status' => '0']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Tryout List') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->query('status') == '1' ? 'active' : '' }}">
                    <a href="{{ route('registered.participant', ['status' => '1']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Official Player') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('calendar-of-activities') ? 'active' : '' }}">
        <a href="{{ route('calendar-of-activities') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Calendar') }}</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('report.coach') ? 'active' : '' }}">
        <a href="{{ route('report.coach') }}" class="nav-link">
            <span class="sidebar-icon me-3">
                <i class="fas fa-user-alt fa-fw"></i>
            </span>
            <span class="sidebar-text">{{ __('Reports') }}</span>
        </a>
    </li>
    @endcoach

    @adviser
    <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <a href="{{ route('home') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Dashboard') }}</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('newsfeed.index') ? 'active' : '' }}">
        <a href="{{ route('newsfeed.index') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Newsfeed') }}</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('activity.index') ? 'active' : '' }}">
        <a href="{{ route('activity.index') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Activity') }}</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('calendar-of-activities') ? 'active' : '' }}">
        <a href="{{ route('calendar-of-activities') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Calendar') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
            data-bs-target="#submenu-app">
            <span>
                <span class="sidebar-icon me-3">
                    <i class="fas fa-circle fa-fw"></i>
                </span>
                <span class="sidebar-text">Performer Record</span>
            </span>
            <span class="link-arrow">
                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd">
                    </path>
                </svg>
            </span>
        </span>
        <div class="multi-level collapse" role="list" id="submenu-app" aria-expanded="false">
            <ul class="flex-column nav">
                <li class="nav-item {{ request()->query('status') == '0' ? 'active' : '' }}">
                    <a href="{{ route('audition.list', ['status' => '0']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('List of Auditionees') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->query('status') == '1' ? 'active' : '' }}">
                    <a href="{{ route('audition.list', ['status' => '1']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Official Performers') }}</span>
                    </a>
                </li>

                <li class="nav-item  {{ request()->routeIs('practice.index') ? 'active' : '' }}">
                    <a href="{{ route('practice.index', ['status' => '0']) }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Practice') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item {{ request()->routeIs('report.view-adviser') ? 'active' : '' }}">
        <a href="{{ route('report.view-adviser') }}" class="nav-link">
            <span class="sidebar-icon me-3">
                <i class="fas fa-user-alt fa-fw"></i>
            </span>
            <span class="sidebar-text">{{ __('Reports') }}</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('deleted.activities') ? 'active' : '' }}">
        <a href="{{ route('deleted.activities') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Archive Activity') }}</span>
        </a>
    </li>
    @endadviser

    @student
    <li class="nav-item {{ request()->routeIs('newsfeed.index') ? 'active' : '' }}">
        <a href="{{ route('newsfeed.index') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Newsfeed') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
            data-bs-target="#submenu-app">
            <span>
                <span class="sidebar-icon me-3">
                    <i class="fas fa-circle fa-fw"></i>
                </span>
                <span class="sidebar-text">Activities</span>
            </span>
            <span class="link-arrow">
                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd">
                    </path>
                </svg>
            </span>
        </span>
        <div class="multi-level collapse" role="list" id="submenu-app" aria-expanded="false">
            <ul class="flex-column nav">
                <li class="nav-item {{ request()->routeIs('activity-registration.index') ? 'active' : '' }}">
                    <a href="{{ route('activity-registration.index') }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Activity') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('joined.activities') ? 'active' : '' }}">
                    <a href="{{ route('joined.activities') }}" class="nav-link">
                        <span class="sidebar-icon me-3">
                            <i class="fas fa-user-alt fa-fw"></i>
                        </span>
                        <span class="sidebar-text">{{ __('Joined Activity') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('calendar-of-activities') ? 'active' : '' }}">
                    <a href="{{ route('calendar-of-activities') }}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">{{ __('Calendar') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('feedback.create') ? 'active' : '' }}">
        <a href="{{ route('feedback.create') }}" class="nav-link">
            <span class="sidebar-icon me-3">
                <i class="fas fa-user-alt fa-fw"></i>
            </span>
            <span class="sidebar-text">{{ __('Contact Us') }}</span>
        </a>
    </li>
    @endstudent

    <li class="nav-item">
        <a href="{{ url('chatify') }}" class="nav-link">
            <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </span>
            <span class="sidebar-text">{{ __('Direct Message') }}</span>
        </a>
    </li>
</ul>