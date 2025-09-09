{{-- resources/views/partials/search-filter-bar.blade.php --}}
<section x-data="{
        selected: 'activities',
        showModal: false,
        bookingItem: '',
        topItems: {
            activities: ['Yoga with Kids', 'Family Cooking Class', 'Mindfulness Walk', 'Parent-Child Art', 'Storytelling Night'],
            bonding: ['Group Hiking', 'Parent-Child Camping', 'Sports Tournament', 'Beach Clean-Up', 'Dance Night'],
            services: ['Therapy Session - Dr. Aisha', 'Nutrition Consultation', 'Parenting Workshop', 'Speech Therapy', 'Music Class'],
            providers: ['Happy Minds Clinic', 'Green Valley Wellness', 'Care4U Center', 'Parenting Hub', 'Mindful Growth Org'],
            bookings: ['Therapy Booking #1012', 'Parenting Workshop Booking', 'Speech Therapy Slot', 'Yoga Class Booking', 'Nutrition Class Booking']
        },
        addBooking(item){
            this.topItems.bookings.unshift(item);
            if(this.topItems.bookings.length > 5){
                this.topItems.bookings.pop();
            }
        }
    }" class="bg-white shadow-md border border-gray-100 rounded-2xl p-6 space-y-6">

    <!-- 🔖 Section Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b pb-3">
        <h3 class="text-lg font-semibold text-gray-800 mb-2 md:mb-0">Search, Filter & Book</h3>
        <span class="text-xs text-gray-500">Find activities, services, bonding events, and more</span>
    </div>

    <!-- 🔍 Filter Row -->
    <div
        class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between space-y-4 lg:space-y-0 lg:space-x-6">
        <div class="w-full lg:w-1/3">
            <select x-model="selected"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm text-gray-700">
                <option value="activities">Activities</option>
                <option value="bonding">Bonding Activities</option>
                <option value="services">Services</option>
                <option value="providers">Providers</option>
                <option value="bookings">Bookings</option>
            </select>
        </div>
    </div>

    <!-- 🌟 Top 5 Suggested Items Below -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mt-4">
        <h4 class="text-sm font-medium text-gray-700 mb-2">
            Top 5 <span x-text="selected.charAt(0).toUpperCase() + selected.slice(1)"></span>
        </h4>
        <ul class="space-y-2 text-sm">
            <template x-for="item in topItems[selected]" :key="item">
                <li class="flex items-center justify-between">
                    <a href="#"
                        class="flex-1 block px-3 py-2 rounded-md hover:bg-green-100 hover:text-green-700 transition">
                        <span x-text="item"></span>
                    </a>
                    <button @click="showModal = true; bookingItem = item"
                        class="ml-3 px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md shadow-sm transition">
                        Book
                    </button>
                </li>
            </template>
        </ul>
    </div>

    <!-- 📌 Booking Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        x-transition>
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md relative">
            <button @click="showModal = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
                ✕
            </button>

            <h3 class="text-lg font-semibold text-gray-800 mb-4">Book: <span x-text="bookingItem"></span></h3>

            <!-- AJAX Booking Form -->
            <form id="bookingForm">
                @csrf
                <input type="hidden" name="item" x-model="bookingItem">

                <div class="mb-3">
                    <label class="text-sm text-gray-600">Your Name</label>
                    <input type="text" name="name" required
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div class="mb-3">
                    <label class="text-sm text-gray-600">Date</label>
                    <input type="date" name="date" required
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div class="mb-3">
                    <label class="text-sm text-gray-600">Notes</label>
                    <textarea name="notes"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium shadow-md">
                    Confirm Booking
                </button>
            </form>

            <p id="bookingSuccess" class="hidden mt-3 text-green-600 font-medium text-sm"></p>
        </div>
    </div>
</section>

<div class="input-group mb-3">
    <input type="text" id="dashboard-search" class="form-control" placeholder="{{ __('dashboard.search_placeholder') }}"
        aria-label="Search everything...">
    <button class="btn btn-success" type="button" id="search-btn">
        🔍
    </button>
</div>


<!-- 📌 AJAX + Live Update Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('bookingForm');
    const successMessage = document.getElementById('bookingSuccess');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        fetch("{{ route('bookings.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    item: form.item.value,
                    name: form.name.value,
                    date: form.date.value,
                    notes: form.notes.value
                })
            })
            .then(response => response.json())
            .then(data => {
                successMessage.textContent = "✅ Booking confirmed successfully!";
                successMessage.classList.remove("hidden");
                successMessage.classList.remove("text-red-600");

                // Add booking to topItems.bookings and keep max 5
                Alpine.store('topItems').bookings.unshift(form.item.value);
                if (Alpine.store('topItems').bookings.length > 5) {
                    Alpine.store('topItems').bookings.pop();
                }

                form.reset();
                setTimeout(() => {
                    successMessage.classList.add("hidden");
                    showModal = false;
                }, 2500);
            })
            .catch(error => {
                successMessage.textContent = "❌ Something went wrong!";
                successMessage.classList.remove("hidden");
                successMessage.classList.add("text-red-600");
            });
    });
});
</script>
