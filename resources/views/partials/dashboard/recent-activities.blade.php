<div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-blue-50">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-list-alt text-gray-600 mr-3"></i>
                    Recent Family Activities
                </h3>
                <p class="text-sm text-gray-600 mt-1">Latest bookings and family interactions</p>
            </div>
            <a href="{{ route('activities.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg transform hover:scale-105">
                <i class="fas fa-external-link-alt mr-2"></i>
                View All Activities
            </a>
        </div>
    </div>

    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Family Details</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Activity Info</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Schedule</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-6 py-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
                                    JM
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">Johnson Family</div>
                                    <div class="text-xs text-gray-500 flex items-center mt-1">
                                        <i class="fas fa-users mr-1"></i>
                                        2 children • Ages 6-10
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="text-sm font-semibold text-gray-900">Parent-Child Cooking Workshop</div>
                            <div class="text-xs text-gray-500 flex items-center mt-2">
                                <span class="inline-block w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                Weekend Bonding Session
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="text-sm font-semibold text-gray-900">Today, 2:00 PM</div>
                            <div class="text-xs text-gray-500 flex items-center mt-1">
                                <i class="fas fa-clock mr-1"></i>
                                Duration: 2 hours
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-800 shadow-sm">
                                <i class="fas fa-check-circle mr-1"></i>
                                Confirmed
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-center space-x-2">
                                <button class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-xl transition-all duration-200" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-2 text-green-600 hover:text-green-800 hover:bg-green-100 rounded-xl transition-all duration-200" title="Contact Family">
                                    <i class="fas fa-phone"></i>
                                </button>
                                <button class="p-2 text-purple-600 hover:text-purple-800 hover:bg-purple-100 rounded-xl transition-all duration-200" title="Send Message">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-yellow-50 transition-colors duration-200">
                        <td class="px-6 py-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
                                    AS
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">Anderson Family</div>
                                    <div class="text-xs text-gray-500 flex items-center mt-1">
                                        <i class="fas fa-user mr-1"></i>
                                        1 child • Age 12
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="text-sm font-semibold text-gray-900">Nature Walk & Picnic</div>
                            <div class="text-xs text-gray-500 flex items-center mt-2">
                                <span class="inline-block w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                Outdoor Adventure
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="text-sm font-semibold text-gray-900">Tomorrow, 10:00 AM</div>
                            <div class="text-xs text-gray-500 flex items-center mt-1">
                                <i class="fas fa-clock mr-1"></i>
                                Duration: 3 hours
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 shadow-sm">
                                <i class="fas fa-clock mr-1"></i>
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-center space-x-2">
                                <button class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-xl transition-all duration-200" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-2 text-green-600 hover:text-green-800 hover:bg-green-100 rounded-xl transition-all duration-200" title="Confirm Booking">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="p-2 text-orange-600 hover:text-orange-800 hover:bg-orange-100 rounded-xl transition-all duration-200" title="Reschedule">
                                    <i class="fas fa-calendar-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
