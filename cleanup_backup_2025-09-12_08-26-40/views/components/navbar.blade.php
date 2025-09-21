<!-- Fixed Navbar -->
<nav class="bg-white shadow fixed top-0 inset-x-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">

      <!-- Left side (Brand + Links) -->
      <div class="flex">
        <div class="flex-shrink-0 flex items-center">
          <a href="{{ url('/') }}" class="text-xl font-bold text-indigo-600">Infanect</a>
        </div>
        <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
          <a href="{{ route('dashboard') }}"
             class="border-b-2 border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">
             Dashboard
          </a>
          <a href="{{ route('activities.index') }}"
             class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium">
             Activities
          </a>
        </div>
      </div>

      <!-- Right side (Search + Auth) -->
      <div class="flex items-center">
        <div class="hidden md:block">
          <input type="search"
                 placeholder="Search"
                 class="border rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
        </div>
        <div class="ml-4 relative">
          @auth
            <span class="text-sm mr-4 font-medium text-gray-700">{{ auth()->user()->name }}</span>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();"
               class="text-sm text-gray-600 hover:text-indigo-600 font-medium">
               Logout
            </a>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
          @else
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600 font-medium">Login</a>
          @endauth
        </div>
      </div>

    </div>
  </div>
</nav>

<!-- Spacer for fixed navbar -->
<div class="h-16"></div>
{{-- End of Navbar --}}
