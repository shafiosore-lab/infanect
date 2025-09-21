<div class="bg-gradient-to-r from-green-500 via-teal-500 to-blue-600 rounded-3xl shadow-2xl overflow-hidden">
    <div class="p-8 md:p-12">
        <div class="flex items-center">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-5">
                <i class="fas fa-heart text-3xl text-white"></i>
            </div>
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-white">
                    Welcome Back, {{ Auth::user()->name }} 👋
                </h2>
                <p class="text-green-100 mt-2 text-lg">
                    Empowering {{ $metrics['activeFamilies'] ?? 89 }} families this month
                </p>
            </div>
        </div>

        {{-- Top Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-white bg-opacity-20 rounded-2xl p-6 hover:bg-opacity-30 transition-all duration-300">
                <div class="flex items-center">
                    <i class="fas fa-calendar-check text-2xl text-white mr-4"></i>
                    <div>
                        <p class="text-white text-2xl font-bold">{{ $metrics['weeklyActivities'] ?? 32 }}</p>
                        <p class="text-green-100 text-sm">Weekly Activities</p>
                    </div>
                </div>
            </div>

            <div class="bg-white bg-opacity-20 rounded-2xl p-6 hover:bg-opacity-30 transition-all duration-300">
                <div class="flex items-center">
                    <i class="fas fa-star text-2xl text-yellow-300 mr-4"></i>
                    <div>
                        <p class="text-white text-2xl font-bold">{{ $metrics['satisfactionRate'] ?? '96%' }}</p>
                        <p class="text-green-100 text-sm">Satisfaction</p>
                    </div>
                </div>
            </div>

            <div class="bg-white bg-opacity-20 rounded-2xl p-6 hover:bg-opacity-30 transition-all duration-300">
                <div class="flex items-center">
                    <i class="fas fa-chart-line text-2xl text-white mr-4"></i>
                    <div>
                        <p class="text-white text-2xl font-bold">KSh {{ number_format($metrics['totalRevenue'] ?? 154200, 0) }}K</p>
                        <p class="text-green-100 text-sm">Revenue</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
