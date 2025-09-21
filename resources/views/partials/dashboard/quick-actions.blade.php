<div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-teal-50">
        <h3 class="text-lg font-bold text-gray-900 flex items-center">
            <i class="fas fa-bolt text-green-600 mr-3"></i>
            Quick Actions
        </h3>
        <p class="text-sm text-gray-600 mt-1">Manage your activities efficiently</p>
    </div>
    <div class="p-6 space-y-4">
        <button onclick="createNewActivity()" class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-2xl font-semibold hover:from-green-600 hover:to-teal-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
            <i class="fas fa-plus-circle mr-3 text-lg"></i>
            Create New Activity
        </button>
        <button onclick="sendFamilyUpdate()" class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-2xl font-semibold hover:from-blue-600 hover:to-purple-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
            <i class="fas fa-bullhorn mr-3 text-lg"></i>
            Notify Families
        </button>
        <button onclick="viewBookingRequests()" class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-2xl font-semibold hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
            <i class="fas fa-calendar-check mr-3 text-lg"></i>
            Review Bookings
        </button>
    </div>
</div>
