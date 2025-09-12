<nav class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <div class="flex">
        <div class="flex-shrink-0 flex items-center">
          <a href="{{ url('/') }}" class="text-xl font-bold">Infanect</a>
        </div>
        <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
          <a href="{{ route('dashboard') }}" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">Dashboard</a>
          <a href="{{ route('activities.index') }}" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium"></a>
        </div>
      </div>
      <div class="flex items-center">
        <div class="hidden md:block">
          <input type="search" placeholder="Search" class="border rounded px-3 py-1" />
        </div>
        <div class="ml-4 relative">
          @auth
            <span class="text-sm mr-4">{{ auth()->user()->name }}</span>
            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="text-sm text-gray-600">Logout</a>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none">@csrf</form>
          @else
            <a href="{{ route('login') }}" class="text-sm text-gray-600">Login</a>
          @endauth
        </div>
      </div>
    </div>
  </div>
</nav>
