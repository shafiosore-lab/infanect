<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $category = $request->get('category');
        $ageGroup = $request->get('age_group');
        $location = $request->get('location');
        $search = $request->get('search');

        // Generate sample activities data
        $allActivities = $this->generateActivities();

        // Apply filters
        if ($category) {
            $allActivities = $allActivities->filter(function($activity) use ($category) {
                return $activity->category === $category;
            });
        }

        if ($ageGroup) {
            $allActivities = $allActivities->filter(function($activity) use ($ageGroup) {
                return in_array($ageGroup, $activity->age_groups);
            });
        }

        if ($location) {
            $allActivities = $allActivities->filter(function($activity) use ($location) {
                return stripos($activity->location, $location) !== false;
            });
        }

        if ($search) {
            $allActivities = $allActivities->filter(function($activity) use ($search) {
                return stripos($activity->title, $search) !== false ||
                       stripos($activity->description, $search) !== false;
            });
        }

        // Paginate results (12 per page)
        $perPage = 12;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $activities = $allActivities->slice($offset, $perPage)->values();

        // Create pagination data
        $total = $allActivities->count();
        $lastPage = ceil($total / $perPage);

        $paginationData = (object)[
            'current_page' => $currentPage,
            'last_page' => $lastPage,
            'per_page' => $perPage,
            'total' => $total,
            'from' => $total > 0 ? $offset + 1 : 0,
            'to' => min($offset + $perPage, $total),
            'has_pages' => $lastPage > 1
        ];        // Get filter options
        $categories = $this->getCategories();
        $ageGroups = $this->getAgeGroups();
        $locations = $this->getLocations();

        // Ensure all variables are arrays to prevent null errors
        $categories = $categories ?? [];
        $ageGroups = $ageGroups ?? [];
        $locations = $locations ?? [];

        return view('activities.index', compact('activities', 'categories', 'ageGroups', 'locations', 'paginationData'));
    }

    public function show($id)
    {
        $activity = $this->getActivityById($id);
        $relatedActivities = $this->getRelatedActivities($activity->category, $id);

        return view('activities.show', compact('activity', 'relatedActivities'));
    }

    public function category($category)
    {
        $activities = $this->generateActivities()->filter(function($activity) use ($category) {
            return $activity->category === $category;
        });

        $categoryName = ucfirst(str_replace('-', ' ', $category));

        return view('activities.category', compact('activities', 'category', 'categoryName'));
    }

    public function book($id)
    {
        $activity = $this->getActivityById($id);

        return view('activities.book', compact('activity'));
    }

    public function storeBooking(Request $request, $id)
    {
        $activity = $this->getActivityById($id);

        $validated = $request->validate([
            'date' => 'required|date|after:today',
            'time' => 'required|string',
            'participants' => 'required|integer|min:1|max:' . ($activity->max_participants ?? 10),
            'participant_details' => 'required|array',
            'participant_details.*.name' => 'required|string|max:255',
            'participant_details.*.age' => 'required|integer|min:1|max:120',
            'participant_details.*.age_group' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'special_requirements' => 'nullable|string|max:500',
        ]);

        // Calculate total amount
        $totalAmount = $activity->price * $validated['participants'];

        // Store booking data in session for payment
        session([
            'activity_booking' => [
                'activity_id' => $id,
                'activity_title' => $activity->title,
                'date' => $validated['date'],
                'time' => $validated['time'],
                'participants' => $validated['participants'],
                'participant_details' => $validated['participant_details'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'special_requirements' => $validated['special_requirements'],
                'total_amount' => $totalAmount,
                'booking_reference' => 'ACT-' . strtoupper(uniqid()),
            ]
        ]);

        return redirect()->route('activities.payment', $id);
    }

    public function payment($id)
    {
        $activity = $this->getActivityById($id);
        $bookingData = session('activity_booking');

        if (!$bookingData || $bookingData['activity_id'] != $id) {
            return redirect()->route('activities.show', $id)
                           ->with('error', 'Booking session expired. Please try again.');
        }

        return view('activities.payment', compact('activity', 'bookingData'));
    }

    public function processPayment(Request $request, $id)
    {
        $bookingData = session('activity_booking');

        if (!$bookingData || $bookingData['activity_id'] != $id) {
            return redirect()->route('activities.show', $id)
                           ->with('error', 'Booking session expired. Please try again.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:mpesa,card,paypal',
            'phone_number' => 'required_if:payment_method,mpesa|string',
        ]);

        // Simulate payment processing
        $paymentSuccess = true; // In real implementation, integrate with payment gateway

        if ($paymentSuccess) {
            // Clear booking session
            session()->forget('activity_booking');

            return redirect()->route('activities.booking.success', [
                'id' => $id,
                'reference' => $bookingData['booking_reference']
            ]);
        } else {
            return back()->with('error', 'Payment failed. Please try again.');
        }
    }

    public function bookingSuccess($id, $reference)
    {
        $activity = $this->getActivityById($id);

        return view('activities.booking-success', compact('activity', 'reference'));
    }

    public function myBookings()
    {
        // In a real application, this would fetch from database
        // For now, we'll simulate user bookings
        $bookings = $this->generateUserBookings();

        return view('activities.my-bookings', compact('bookings'));
    }

    public function showBooking($reference)
    {
        // In a real application, this would fetch from database by reference
        $booking = $this->getBookingByReference($reference);

        if (!$booking) {
            abort(404, 'Booking not found');
        }

        return view('activities.booking-details', compact('booking'));
    }

    private function generateUserBookings()
    {
        // Simulate user bookings data
        return collect([
            (object)[
                'id' => 1,
                'reference' => 'ACT-' . strtoupper(uniqid('BWX')),
                'activity_id' => 1,
                'activity_title' => 'Family Safari Adventure',
                'activity_location' => 'Maasai Mara, Kenya',
                'activity_category' => 'outdoor',
                'date' => '2024-02-15',
                'time' => '09:00',
                'participants' => 4,
                'participant_details' => [
                    ['name' => 'John Doe', 'age' => 35, 'age_group' => 'adult'],
                    ['name' => 'Jane Doe', 'age' => 32, 'age_group' => 'adult'],
                    ['name' => 'Tommy Doe', 'age' => 8, 'age_group' => 'child'],
                    ['name' => 'Lucy Doe', 'age' => 5, 'age_group' => 'child'],
                ],
                'total_amount' => 1800,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_method' => 'mpesa',
                'booked_at' => '2024-01-10 14:30:00',
                'email' => 'john.doe@example.com',
                'phone' => '+254 700 123 456',
                'special_requirements' => 'Vegetarian meals for 2 participants'
            ],
            (object)[
                'id' => 2,
                'reference' => 'ACT-' . strtoupper(uniqid('CRT')),
                'activity_id' => 2,
                'activity_title' => 'Art & Craft Workshop',
                'activity_location' => 'Nairobi Cultural Center',
                'activity_category' => 'creative',
                'date' => '2024-01-25',
                'time' => '14:00',
                'participants' => 2,
                'participant_details' => [
                    ['name' => 'Sarah Johnson', 'age' => 28, 'age_group' => 'adult'],
                    ['name' => 'Emma Johnson', 'age' => 10, 'age_group' => 'child'],
                ],
                'total_amount' => 70,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'card',
                'booked_at' => '2024-01-05 10:15:00',
                'email' => 'sarah.j@example.com',
                'phone' => '+254 722 987 654',
                'special_requirements' => null
            ],
            (object)[
                'id' => 3,
                'reference' => 'ACT-' . strtoupper(uniqid('SPT')),
                'activity_id' => 3,
                'activity_title' => 'Swimming & Water Sports',
                'activity_location' => 'Diani Beach, Mombasa',
                'activity_category' => 'sports',
                'date' => '2024-02-20',
                'time' => '10:00',
                'participants' => 3,
                'participant_details' => [
                    ['name' => 'Mike Wilson', 'age' => 40, 'age_group' => 'adult'],
                    ['name' => 'Lisa Wilson', 'age' => 15, 'age_group' => 'teen'],
                    ['name' => 'Alex Wilson', 'age' => 12, 'age_group' => 'child'],
                ],
                'total_amount' => 225,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'paypal',
                'booked_at' => '2024-01-18 16:45:00',
                'email' => 'mike.wilson@example.com',
                'phone' => '+254 733 456 789',
                'special_requirements' => 'Need life jackets for all participants'
            ]
        ]);
    }

    private function getBookingByReference($reference)
    {
        return $this->generateUserBookings()->firstWhere('reference', $reference);
    }

    private function generateActivities()
    {
        $activities = collect([]);

        $sampleActivities = [
            [
                'id' => 1,
                'title' => 'Family Safari Adventure',
                'description' => 'Explore the wildlife of Kenya with guided family-friendly safari tours in Maasai Mara. Experience the Big Five up close with professional guides.',
                'category' => 'outdoor',
                'age_groups' => ['toddler', 'child', 'teen', 'adult'],
                'duration' => '3 days',
                'price' => 450,
                'location' => 'Maasai Mara, Kenya',
                'rating' => 4.8,
                'reviews' => 156,
                'image_id' => '1441974485743-cf17211c2174',
                'max_participants' => 8,
                'includes' => ['Transport', 'Meals', 'Guide', 'Equipment'],
                'requirements' => ['Valid ID', 'Comfortable shoes', 'Sun protection']
            ],
            [
                'id' => 2,
                'title' => 'Art & Craft Workshop',
                'description' => 'Creative art sessions where families can learn traditional African crafts and painting techniques.',
                'category' => 'creative',
                'age_groups' => ['child', 'teen', 'adult'],
                'duration' => '2 hours',
                'price' => 35,
                'location' => 'Nairobi Cultural Center',
                'rating' => 4.6,
                'reviews' => 89,
                'image_id' => '1513475382772-0e1c5687e07b',
                'max_participants' => 12,
                'includes' => ['Materials', 'Instructor', 'Refreshments'],
                'requirements' => ['No experience needed', 'Comfortable clothing']
            ],
            [
                'id' => 3,
                'title' => 'Swimming & Water Sports',
                'description' => 'Fun water activities for the whole family at Diani Beach with certified instructors.',
                'category' => 'sports',
                'age_groups' => ['child', 'teen', 'adult'],
                'duration' => '4 hours',
                'price' => 75,
                'location' => 'Diani Beach, Mombasa',
                'rating' => 4.7,
                'reviews' => 234,
                'image_id' => '1530549387789-4c5f4e5166d5',
                'max_participants' => 15,
                'includes' => ['Equipment', 'Instructor', 'Safety gear', 'Refreshments'],
                'requirements' => ['Swimming ability', 'Health declaration', 'Sun protection']
            ],
            [
                'id' => 4,
                'title' => 'Cooking Class: African Cuisine',
                'description' => 'Learn to prepare traditional East African dishes together as a family.',
                'category' => 'educational',
                'age_groups' => ['teen', 'adult'],
                'duration' => '3 hours',
                'price' => 65,
                'location' => 'Kampala Cooking School',
                'rating' => 4.9,
                'reviews' => 78,
                'image_id' => '1556909114-4416a4b81e63',
                'max_participants' => 10,
                'includes' => ['Ingredients', 'Chef instruction', 'Recipe cards', 'Lunch'],
                'requirements' => ['Apron provided', 'Closed shoes', 'No allergies declared']
            ],
            [
                'id' => 5,
                'title' => 'Nature Trail & Bird Watching',
                'description' => 'Peaceful nature walks in Aberdare National Park with bird watching opportunities.',
                'category' => 'outdoor',
                'age_groups' => ['child', 'teen', 'adult'],
                'duration' => '5 hours',
                'price' => 85,
                'location' => 'Aberdare National Park',
                'rating' => 4.5,
                'reviews' => 112,
                'image_id' => '1441974485743-cf17211c2174',
                'max_participants' => 20,
                'includes' => ['Guide', 'Binoculars', 'Field guide', 'Snacks'],
                'requirements' => ['Walking shoes', 'Weather-appropriate clothing', 'Water bottle']
            ],
            [
                'id' => 6,
                'title' => 'Music & Dance Workshop',
                'description' => 'Interactive sessions learning traditional African music and dance movements.',
                'category' => 'creative',
                'age_groups' => ['toddler', 'child', 'teen'],
                'duration' => '90 minutes',
                'price' => 45,
                'location' => 'Lagos Cultural Hub',
                'rating' => 4.4,
                'reviews' => 67,
                'image_id' => '1493225457124-12f6fcf1a9f5',
                'max_participants' => 25,
                'includes' => ['Instruments', 'Instructor', 'Costumes', 'Refreshments'],
                'requirements' => ['Comfortable clothes', 'Enthusiasm', 'Dance shoes optional']
            ],
            [
                'id' => 7,
                'title' => 'Football Training Camp',
                'description' => 'Professional football training sessions for kids and families in Johannesburg.',
                'category' => 'sports',
                'age_groups' => ['child', 'teen'],
                'duration' => '2 hours',
                'price' => 40,
                'location' => 'Soccer City, Johannesburg',
                'rating' => 4.6,
                'reviews' => 193,
                'image_id' => '1574952042884-18e8d44e4fd4',
                'max_participants' => 30,
                'includes' => ['Professional coaching', 'Equipment', 'Water', 'Certificate'],
                'requirements' => ['Sports attire', 'Football boots', 'Medical clearance']
            ],
            [
                'id' => 8,
                'title' => 'Science Discovery Lab',
                'description' => 'Hands-on science experiments and discovery activities for curious young minds.',
                'category' => 'educational',
                'age_groups' => ['child', 'teen'],
                'duration' => '2.5 hours',
                'price' => 55,
                'location' => 'Cairo Science Museum',
                'rating' => 4.8,
                'reviews' => 145,
                'image_id' => '1532094349884-a6d3b5b2b7e5',
                'max_participants' => 16,
                'includes' => ['Lab materials', 'Safety equipment', 'Scientist guide', 'Take-home kit'],
                'requirements' => ['Closed shoes', 'Long pants', 'Curiosity']
            ],
            [
                'id' => 5,
                'title' => 'Nature Trail & Bird Watching',
                'description' => 'Peaceful nature walks in Aberdare National Park with bird watching opportunities.',
                'category' => 'outdoor',
                'age_groups' => ['child', 'teen', 'adult'],
                'duration' => '5 hours',
                'price' => 85,
                'location' => 'Aberdare National Park',
                'rating' => 4.5,
                'reviews' => 112,
                'image_id' => '1441974485743-cf17211c2174'
            ],
            [
                'id' => 6,
                'title' => 'Music & Dance Workshop',
                'description' => 'Interactive sessions learning traditional African music and dance movements.',
                'category' => 'creative',
                'age_groups' => ['toddler', 'child', 'teen'],
                'duration' => '90 minutes',
                'price' => 45,
                'location' => 'Lagos Cultural Hub',
                'rating' => 4.4,
                'reviews' => 67,
                'image_id' => '1493225457124-12f6fcf1a9f5'
            ],
            [
                'id' => 7,
                'title' => 'Football Training Camp',
                'description' => 'Professional football training sessions for kids and families in Johannesburg.',
                'category' => 'sports',
                'age_groups' => ['child', 'teen'],
                'duration' => '2 hours',
                'price' => 40,
                'location' => 'Soccer City, Johannesburg',
                'rating' => 4.6,
                'reviews' => 193,
                'image_id' => '1574952042884-18e8d44e4fd4'
            ],
            [
                'id' => 8,
                'title' => 'Science Discovery Lab',
                'description' => 'Hands-on science experiments and discovery activities for curious young minds.',
                'category' => 'educational',
                'age_groups' => ['child', 'teen'],
                'duration' => '2.5 hours',
                'price' => 55,
                'location' => 'Cairo Science Museum',
                'rating' => 4.8,
                'reviews' => 145,
                'image_id' => '1532094349884-a6d3b5b2b7e5'
            ]
        ];

        foreach ($sampleActivities as $activityData) {
            $activities->push((object) $activityData);
        }

        return $activities;
    }

    private function getActivityById($id)
    {
        $activity = $this->generateActivities()->firstWhere('id', $id);

        if (!$activity) {
            abort(404);
        }

        return $activity;
    }

    private function getRelatedActivities($category, $excludeId)
    {
        return $this->generateActivities()
            ->filter(function($activity) use ($category, $excludeId) {
                return $activity->category === $category && $activity->id != $excludeId;
            })
            ->take(3);
    }

    private function getCategories()
    {
        return [
            'outdoor' => 'Outdoor Adventures',
            'creative' => 'Arts & Crafts',
            'sports' => 'Sports & Fitness',
            'educational' => 'Learning & Education'
        ];
    }

    private function getAgeGroups()
    {
        return [
            'toddler' => 'Toddlers (2-4 years)',
            'child' => 'Children (5-12 years)',
            'teen' => 'Teenagers (13-17 years)',
            'adult' => 'Adults (18+ years)'
        ];
    }

    private function getLocations()
    {
        return [
            'Nairobi, Kenya',
            'Mombasa, Kenya',
            'Lagos, Nigeria',
            'Cape Town, South Africa',
            'Cairo, Egypt',
            'Accra, Ghana',
            'Addis Ababa, Ethiopia',
            'Kampala, Uganda'
        ];
    }
}
