<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Receipt - {{ $bookingId }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2563eb; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #2563eb; }
        .receipt-title { font-size: 18px; margin-top: 10px; color: #374151; }
        .content { margin: 20px 0; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 16px; font-weight: bold; color: #1f2937; margin-bottom: 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .label { font-weight: bold; color: #6b7280; }
        .value { color: #1f2937; }
        .total { background-color: #f3f4f6; padding: 15px; border-radius: 8px; margin-top: 20px; }
        .total-amount { font-size: 20px; font-weight: bold; color: #059669; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">INFANECT</div>
        <div class="receipt-title">Booking Receipt</div>
        <div style="font-size: 14px; color: #6b7280;">Receipt #{{ $bookingId }}</div>
    </div>

    <div class="content">
        <div class="section">
            <div class="section-title">Booking Information</div>
            <div class="info-row">
                <span class="label">Booking ID:</span>
                <span class="value">{{ $bookingId }}</span>
            </div>
            <div class="info-row">
                <span class="label">Date Booked:</span>
                <span class="value">{{ date('F j, Y g:i A') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="value" style="color: #059669; font-weight: bold;">CONFIRMED</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Session Details</div>
            <div class="info-row">
                <span class="label">Provider:</span>
                <span class="value">Dr. Sarah Johnson</span>
            </div>
            <div class="info-row">
                <span class="label">Session Date:</span>
                <span class="value">{{ date('F j, Y', strtotime('+7 days')) }}</span>
            </div>
            <div class="info-row">
                <span class="label">Session Time:</span>
                <span class="value">2:00 PM - 3:00 PM</span>
            </div>
            <div class="info-row">
                <span class="label">Session Type:</span>
                <span class="value">Individual Session</span>
            </div>
            <div class="info-row">
                <span class="label">Format:</span>
                <span class="value">Online (Video Call)</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Payment Summary</div>
            <div class="info-row">
                <span class="label">Session Fee:</span>
                <span class="value">$100.00</span>
            </div>
            <div class="info-row">
                <span class="label">Platform Fee:</span>
                <span class="value">$5.00</span>
            </div>
            <div class="total">
                <div class="info-row">
                    <span style="font-size: 18px; font-weight: bold;">Total Paid:</span>
                    <span class="total-amount">$105.00</span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Contact Information</div>
            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value">support@infanect.com</span>
            </div>
            <div class="info-row">
                <span class="label">Phone:</span>
                <span class="value">+1 (555) 123-4567</span>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for choosing Infanect for your family therapy needs.</p>
        <p>This is an official receipt for your booking. Please keep it for your records.</p>
        <p>Generated on {{ date('F j, Y g:i A') }}</p>
    </div>
</body>
</html>
