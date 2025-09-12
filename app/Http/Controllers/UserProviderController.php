<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserProviderController extends Controller
{
    public function index()
    {
        // Generate sample provider data for demo
        $providers = collect([]);

        $africanLocations = [
            'Nairobi, Kenya',
            'Lagos, Nigeria',
            'Cape Town, South Africa',
            'Cairo, Egypt',
            'Accra, Ghana',
            'Addis Ababa, Ethiopia',
            'Kampala, Uganda',
            'Dar es Salaam, Tanzania'
        ];

        for ($i = 1; $i <= 8; $i++) {
            $providers->push((object) [
                'id' => $i,
                'name' => $this->getProviderName($i),
                'title' => 'Certified Family Therapist & Bonding Specialist',
                'rating' => round(4.2 + (rand(0, 8) / 10), 1),
                'reviews' => rand(15, 120),
                'price' => 100 + (($i - 1) * 15),
                'specialties' => ['Family Therapy', 'Child Development', 'Parenting Support'],
                'availability' => 'Available Today',
                'location' => $africanLocations[($i - 1) % count($africanLocations)],
                'experience' => rand(5, 15) . ' years'
            ]);
        }

        return view('providers.index', compact('providers'));
    }    public function show($id)
    {
        // Generate sample provider data for demo
        $provider = (object) [
            'id' => $id,
            'name' => $this->getProviderName($id),
            'title' => 'Certified Family Therapist & Bonding Specialist',
            'rating' => 4.8,
            'reviews' => 127,
            'price' => 100 + (($id - 1) * 15),
            'bio' => 'With over 12 years of experience in family therapy and child development, I specialize in helping families build stronger bonds and navigate challenges together. My approach combines evidence-based techniques with compassionate understanding.',
            'specialties' => [
                'Family Therapy & Counseling',
                'Child Development & Behavior',
                'Parenting Support & Guidance',
                'Adolescent Mental Health',
                'Family Communication',
                'Trauma-Informed Care'
            ],
            'services' => [
                'Family Therapy & Counseling',
                'Child Development & Behavior',
                'Parenting Support & Guidance',
                'Adolescent Mental Health',
                'Family Communication',
                'Trauma-Informed Care'
            ],
            'education' => [
                'Ph.D. in Clinical Psychology - Columbia University',
                'M.A. in Family Therapy - NYU',
                'B.A. in Psychology - Harvard University'
            ],
            'certifications' => [
                'Licensed Clinical Social Worker (LCSW)',
                'Certified Family Therapist (CFT)',
                'Trauma-Informed Care Specialist'
            ],
            'languages' => ['English', 'Swahili', 'French'],
            'availability' => 'Available Today',
            'location' => $this->getProviderLocation($id),
            'experience' => '12+ years',
            'session_types' => ['Individual', 'Family', 'Couples'],
            'formats' => ['Online Video', 'In-Person', 'Phone']
        ];

        return view('providers.show', compact('provider'));
    }

    public function book($id)
    {
        // Generate sample provider data for booking
        $provider = (object) [
            'id' => $id,
            'name' => $this->getProviderName($id),
            'title' => 'Certified Family Therapist & Bonding Specialist',
            'price' => 100 + (($id - 1) * 15)
        ];

        return view('providers.book', compact('provider'));
    }

    public function storeBooking(Request $request, $id)
    {
        // Validate the booking form data
        $validatedData = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string',
            'session_type' => 'required|in:individual,family,couple',
            'session_format' => 'required|in:online,in-person',
            'notes' => 'nullable|string|max:500',
            'email' => 'required|email',
            'phone' => 'required|string'
        ]);

        // For demo purposes, create sample provider data
        $provider = (object) [
            'id' => $id,
            'name' => $this->getProviderName($id),
            'title' => 'Certified Family Therapist & Bonding Specialist',
            'price' => 100 + (($id - 1) * 15),
            'email' => 'provider@infanect.com',
            'phone' => '+1 (555) 123-4567'
        ];

        // Create booking data for payment processing
        $booking = (object) array_merge($validatedData, [
            'id' => uniqid('book_'),
            'provider' => $provider,
            'status' => 'pending_payment',
            'booking_date' => now(),
            'session_datetime' => $validatedData['date'] . ' ' . $validatedData['time']
        ]);

        // Store booking data in session for payment processing
        session(['pending_booking' => $booking]);

        // Redirect to payment page
        return redirect()->route('providers.payment', $provider->id);
    }

    public function payment($id)
    {
        $booking = session('pending_booking');
        if (!$booking) {
            return redirect()->route('providers.index')->with('error', 'Booking session expired.');
        }

        return view('providers.payment', compact('booking'));
    }

    public function processPayment(Request $request, $id)
    {
        $booking = session('pending_booking');
        if (!$booking) {
            return redirect()->route('providers.index')->with('error', 'Booking session expired.');
        }

        // Validate payment information
        $paymentData = $request->validate([
            'card_number' => 'required|string',
            'expiry_month' => 'required|string',
            'expiry_year' => 'required|string',
            'cvv' => 'required|string|min:3|max:4',
            'card_name' => 'required|string',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_zip' => 'required|string'
        ]);

        // Process payment (demo - always successful)
        $paymentResult = $this->processPaymentDemo($paymentData, $booking);

        if ($paymentResult['success']) {
            // Update booking status
            $booking->status = 'confirmed';
            $booking->payment_id = $paymentResult['payment_id'];
            $booking->payment_date = now();

            // Send confirmations
            $this->sendEmailConfirmation($booking);
            $this->sendSMSConfirmation($booking);

            // Clear session
            session()->forget('pending_booking');

            return view('providers.booking-success', compact('booking'));
        }

        return back()->with('error', 'Payment failed. Please try again.');
    }

    public function downloadReceipt($bookingId)
    {
        // Generate PDF receipt
        $html = view('providers.receipt-pdf', compact('bookingId'))->render();

        // For demo purposes, return a simple download response
        // In a real application, you would use a PDF library like dompdf or wkhtmltopdf
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="booking-receipt-' . $bookingId . '.html"');
    }

    private function processPaymentDemo($paymentData, $booking)
    {
        // Demo payment processing - always successful
        return [
            'success' => true,
            'payment_id' => 'pay_' . uniqid(),
            'transaction_id' => 'txn_' . uniqid()
        ];
    }

    private function sendEmailConfirmation($booking)
    {
        // Demo email confirmation
        Log::info('Email confirmation sent to: ' . $booking->email);
        // In real app: Mail::to($booking->email)->send(new BookingConfirmation($booking));
    }

    private function sendSMSConfirmation($booking)
    {
        // Demo SMS confirmation
        Log::info('SMS confirmation sent to: ' . $booking->phone);
        // In real app: SMS service integration
    }

    private function getProviderName($id)
    {
        $names = [
            1 => 'Dr. Sarah Johnson',
            2 => 'Dr. Michael Chen',
            3 => 'Dr. Emily Rodriguez',
            4 => 'Dr. David Wilson',
            5 => 'Dr. Lisa Thompson',
            6 => 'Dr. James Martinez',
            7 => 'Dr. Anna Kowalski',
            8 => 'Dr. Robert Kim'
        ];

        return $names[$id] ?? 'Dr. Sarah Johnson';
    }

    private function getProviderLocation($id)
    {
        $locations = [
            1 => 'Nairobi, Kenya',
            2 => 'Lagos, Nigeria',
            3 => 'Cape Town, South Africa',
            4 => 'Cairo, Egypt',
            5 => 'Accra, Ghana',
            6 => 'Addis Ababa, Ethiopia',
            7 => 'Kampala, Uganda',
            8 => 'Dar es Salaam, Tanzania'
        ];

        return $locations[$id] ?? 'Nairobi, Kenya';
    }
}
