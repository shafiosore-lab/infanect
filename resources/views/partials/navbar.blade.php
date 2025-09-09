<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fw-bold text-success" href="{{ url('/') }}">
            Infanect
        </a>

        <!-- Toggle for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#infanectNavbar"
            aria-controls="infanectNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="infanectNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- Available Services -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="servicesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Available Services
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                        <li><a class="dropdown-item" href="{{ route('services.index') }}">All Services</a></li>
                        <li><a class="dropdown-item" href="{{ route('services.popular') }}">Popular Services</a></li>
                        <li><a class="dropdown-item" href="{{ route('services.categories') }}">By Category</a></li>
                    </ul>
                </li>

                <!-- Top Bonding Activities -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="activitiesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Top Bonding Activities
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="activitiesDropdown">
                        <li><a class="dropdown-item" href="{{ route('activities.index') }}">All Activities</a></li>
                        <li><a class="dropdown-item" href="{{ route('activities.family') }}">Family Activities</a></li>
                        <li><a class="dropdown-item" href="{{ route('activities.outdoor') }}">Outdoor Activities</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('activities.indoor') }}">Indoor Activities</a></li>
                    </ul>
                </li>

                <!-- Top Providers -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="providersDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Top Providers
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="providersDropdown">
                        <li><a class="dropdown-item" href="{{ route('providers.index') }}">All Providers</a></li>
                        <li><a class="dropdown-item" href="{{ route('providers.featured') }}">Featured Providers</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('providers.top-rated') }}">Top Rated</a></li>
                    </ul>
                </li>

                <!-- Training Modules -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="trainingDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Training Modules
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="trainingDropdown">
                        <li><a class="dropdown-item" href="{{ route('training-modules.index') }}">All Modules</a></li>
                        <li><a class="dropdown-item" href="{{ route('training-modules.my-progress') }}">My Progress</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('ai-chat.index') }}">AI Assisted Learning</a></li>
                    </ul>
                </li>
            </ul>

            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand fw-bold text-success" href="#">Infanect</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarScroll">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 navbar-nav-scroll">
                            <li class="nav-item">
                                <a class="nav-link active text-dark" href="#">Dashboard</a>
                            </li>

                            <!-- Language Switcher -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-dark" href="#" id="languageDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    🌍 {{ strtoupper(app()->getLocale()) }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ url('locale/en') }}">🇬🇧 English</a></li>
                                    <li><a class="dropdown-item" href="{{ url('locale/fr') }}">🇫🇷 Français</a></li>
                                    <li><a class="dropdown-item" href="{{ url('locale/sw') }}">🇰🇪 Kiswahili</a></li>
                                </ul>
                            </li>
                        </ul>

                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search dashboard..."
                                aria-label="Search" />
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </nav>


            <!-- Global Search -->
            <form class="d-flex" method="GET" action="{{ route('dashboard.search') }}">
                <input class="form-control me-2" type="search" name="q" placeholder="Search dashboard..."
                    aria-label="Search">
                <button class="btn btn-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
