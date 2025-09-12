@extends('layouts.app')

@section('title', 'Financial Insights - Infanect')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50">
    <!-- Hero Header -->
    <section class="relative bg-gradient-to-r from-green-600 via-teal-600 to-blue-700 text-white py-12 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>

        <!-- Floating Elements -->
        <div class="absolute top-8 left-8 w-16 h-16 bg-white/10 rounded-full blur-lg animate-pulse"></div>
        <div class="absolute bottom-8 right-8 w-20 h-20 bg-green-200/10 rounded-full blur-xl animate-bounce"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-chart-line text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-white to-green-100 bg-clip-text text-transparent">
                    Financial Insights
                </h1>
                <p class="text-xl text-green-100 max-w-3xl mb-8">
                    Track your family spending and get personalized budget recommendations
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- This Month Spending -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-credit-card text-blue-600"></i>
                        </div>
                        <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-full">
                            +20.8%
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">${{ number_format($insights->total_spent_this_month, 2) }}</h3>
                    <p class="text-sm text-gray-600">This Month</p>
                </div>

                <!-- Last Month Comparison -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-gray-600"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">${{ number_format($insights->total_spent_last_month, 2) }}</h3>
                    <p class="text-sm text-gray-600">Last Month</p>
                </div>

                <!-- Average Monthly -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-bar text-purple-600"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">${{ number_format($insights->average_monthly_spending, 2) }}</h3>
                    <p class="text-sm text-gray-600">6-Month Average</p>
                </div>

                <!-- Savings -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-piggy-bank text-green-600"></i>
                        </div>
                        <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-full">
                            Saved
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold text-green-600">${{ number_format($insights->savings_this_month, 2) }}</h3>
                    <p class="text-sm text-gray-600">vs Budget</p>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Charts Area -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Spending by Category -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Spending by Category</h3>

                        <div class="space-y-4">
                            @foreach($insights->spending_categories as $category => $amount)
                                @php
                                    $percentage = ($amount / array_sum($insights->spending_categories)) * 100;
                                    $color = $category === 'Activities' ? 'green' : 'blue';
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-gray-900">{{ $category }}</span>
                                        <span class="font-bold text-gray-900">${{ number_format($amount, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-{{ $color }}-500 h-3 rounded-full transition-all duration-500"
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">{{ number_format($percentage, 1) }}% of total</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Monthly Trend -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">6-Month Spending Trend</h3>

                        <div class="space-y-3">
                            @foreach($insights->monthly_breakdown as $month => $amount)
                                @php
                                    $maxAmount = max($insights->monthly_breakdown);
                                    $percentage = ($amount / $maxAmount) * 100;
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-16 text-sm font-medium text-gray-600">{{ $month }}</div>
                                    <div class="flex-1 mx-4">
                                        <div class="w-full bg-gray-200 rounded-full h-4">
                                            <div class="bg-gradient-to-r from-green-500 to-teal-500 h-4 rounded-full transition-all duration-500"
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                    <div class="w-20 text-sm font-bold text-gray-900 text-right">
                                        ${{ number_format($amount, 0) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Upcoming Expenses -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Upcoming Expenses</h3>

                        <div class="space-y-4">
                            @foreach($insights->upcoming_expenses as $expense)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900 text-sm">{{ $expense->title }}</h4>
                                        <span class="px-2 py-1 bg-{{ $expense->type === 'activity' ? 'green' : 'blue' }}-50 text-{{ $expense->type === 'activity' ? 'green' : 'blue' }}-700 rounded-full text-xs font-medium">
                                            {{ ucfirst($expense->type) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">{{ date('M j, Y', strtotime($expense->date)) }}</span>
                                        <span class="font-bold text-gray-900">${{ number_format($expense->amount, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Budget Recommendations -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-lightbulb text-blue-600"></i>
                            </div>
                            <h3 class="text-lg font-bold text-blue-900">Smart Recommendations</h3>
                        </div>

                        <div class="space-y-3">
                            @foreach($insights->budget_recommendations as $recommendation)
                                <div class="flex items-start">
                                    <i class="fas fa-check-circle text-blue-600 mr-2 mt-0.5 text-sm"></i>
                                    <span class="text-sm text-blue-800">{{ $recommendation }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                        <div class="space-y-3">
                            <a href="{{ route('financial.reports') }}" class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm text-center block">
                                <i class="fas fa-file-alt mr-2"></i>
                                Download Report
                            </a>

                            <button class="w-full px-4 py-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-medium text-sm">
                                <i class="fas fa-bell mr-2"></i>
                                Set Budget Alerts
                            </button>

                            <a href="{{ route('services.my-bookings') }}" class="w-full px-4 py-3 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors font-medium text-sm text-center block">
                                <i class="fas fa-calendar-check mr-2"></i>
                                View All Bookings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
