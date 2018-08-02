<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-white">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item @if (Route::currentRouteName() == 'home') active @endif">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fa fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item @if (Route::currentRouteName() == 'notifications.index') active @endif">
                    <a class="nav-link" href="{{ route('notifications.index') }}">
                        <i class="fa fa-bell"></i> Notifications
                    </a>
                </li>
                <li class="nav-item @if (Request::is('messages')) active @endif">
                    <a class="nav-link" href="{{ url('messages') }}">
                        <i class="fa fa-envelope"></i> Messages
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ Storage::url(Auth::user()->photo_url) }}" class="rounded-circle" style="max-width: 28px; max-height: 28px;">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="{{ route('users.show', Auth::user()->username) }}">
                            <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                            <span class="text-muted">{{ '@' . Auth::user()->username }}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('users.show', Auth::user()->username) }}">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('settings.index') }}">Settings</a>
                        <a class="dropdown-item" href="#">Help Center</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Night Mode</a>
                        @if (Auth::user()->hasRole('admin'))
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('admin.index') }}">Admin</a>
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
