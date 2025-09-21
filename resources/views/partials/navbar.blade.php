<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fw-bold text-success" href="{{ url('/') }}">
            Infanect
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#infanectNavbar"
            aria-controls="infanectNavbar" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar -->
        <div class="collapse navbar-collapse" id="infanectNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Services -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="servicesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">Available Services</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('services.index') }}">All Services</a></li>
                        <li><a class="dropdown-item" href="{{ route('services.popular') }}">Popular</a></li>
                        <li><a class="dropdown-item" href="{{ route('services.categories') }}">By Category</a></li>
                    </ul>
                </li>

                <!-- Activities -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="activitiesDropdown" role="button"
                        data-bs-toggle="dropdown">Bonding Activities</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('activities.index') }}">All</a></li>
                        <li><a class="dropdown-item" href="{{ route('activities.family') }}">Family</a></li>
                        <li><a class="dropdown-item" href="{{ route('activities.outdoor') }}">Outdoor</a></li>
                        <li><a class="dropdown-item" href="{{ route('activities.indoor') }}">Indoor</a></li>
                    </ul>
                </li>

                <!-- Providers -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="providersDropdown" role="button"
                        data-bs-toggle="dropdown">Providers</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('providers.index') }}">All</a></li>
                        <li><a class="dropdown-item" href="{{ route('providers.featured') }}">Featured</a></li>
                        <li><a class="dropdown-item" href="{{ route('providers.top-rated') }}">Top Rated</a></li>
                    </ul>
                </li>

                <!-- Training -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="trainingDropdown" role="button"
                        data-bs-toggle="dropdown">Training</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('training-modules.index') }}">All Modules</a></li>
                        <li><a class="dropdown-item" href="{{ route('training-modules.my-progress') }}">My Progress</a></li>
                        <li><a class="dropdown-item" href="{{ route('ai-chat.index') }}">AI Learning</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Right Section -->
            <ul class="navbar-nav ms-auto">
                <!-- Language Switcher -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="languageDropdown" role="button"
                        data-bs-toggle="dropdown">🌍 {{ strtoupper(app()->getLocale()) }}</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ url('locale/en') }}">🇬🇧 English</a></li>
                        <li><a class="dropdown-item" href="{{ url('locale/fr') }}">🇫🇷 Français</a></li>
                        <li><a class="dropdown-item" href="{{ url('locale/sw') }}">🇰🇪 Kiswahili</a></li>
                    </ul>
                </li>

                <!-- Auth / Roles -->
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('dashboard') }}">
                            Dashboard ({{ Auth::user()->roles->first()->name ?? 'User' }})
                        </a>
                    </li>
                @endauth
            </ul>

            <!-- Search -->
            <form class="d-flex ms-2" method="GET" action="{{ route('dashboard.search') }}">
                <input class="form-control me-2" type="search" name="q" placeholder="Search..." aria-label="Search">
                <button class="btn btn-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
