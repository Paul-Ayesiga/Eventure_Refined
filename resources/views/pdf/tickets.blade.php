<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Tickets</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        .page-break {
            page-break-after: always;
        }
        .ticket-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto 30px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .ticket-header {
            background: linear-gradient(to right, #3b82f6, #2dd4bf);
            color: white;
            padding: 16px;
        }
        .ticket-header-content {
            display: flex;
            justify-content: space-between;
        }
        .event-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        .org-name {
            font-size: 14px;
            opacity: 0.9;
            margin: 4px 0 0;
        }
        .event-type {
            background-color: rgba(20, 184, 166, 0.3);
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 14px;
        }
        .ticket-body {
            padding: 24px;
            display: flex;
        }
        .ticket-details {
            flex: 1;
            padding-right: 24px;
        }
        .venue-info {
            margin-bottom: 24px;
        }
        .venue-label {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }
        .venue-name {
            font-weight: 600;
            color: #9ca3af;
            margin: 4px 0;
        }
        .venue-date {
            font-size: 14px;
            font-weight: 500;
            color: #9ca3af;
            margin: 0;
        }
        .attendee-info {
            margin-bottom: 24px;
        }
        .info-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 500;
            margin: 0 0 4px;
        }
        .info-value {
            font-weight: 600;
            margin: 0;
        }
        .ticket-grid {
            display: flex;
            margin-top: 16px;
        }
        .grid-item {
            flex: 1;
            margin-right: 24px;
        }
        .grid-item:last-child {
            margin-right: 0;
        }
        .small-text {
            font-size: 12px;
            color: #6b7280;
            margin: 4px 0 0;
        }
        .qr-code {
            text-align: center;
        }
        .qr-code img {
            width: 160px;
            height: 160px;
            border: 1px solid #e2e8f0;
            padding: 8px;
            background: white;
        }
        .ticket-footer {
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 12px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    @foreach($attendees as $index => $attendee)
        <div class="ticket-container">
            <!-- Ticket Header -->
            <div class="ticket-header">
                <div class="ticket-header-content">
                    <div>
                        <h1 class="event-name">{{ $event->name }}</h1>
                        <p class="org-name">{{ $event->organisation->name }}</p>
                    </div>
                    <div>
                        <span class="event-type">{{ $event->event_type ?: 'online' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Ticket Body -->
            <div class="ticket-body">
                <!-- Left side: Event details -->
                <div class="ticket-details">
                    <div class="venue-info">
                        <p class="venue-label">Coming soon</p>
                        <p class="venue-name">{{ $event->venue ?: 'Online Event' }}, {{ $event->location->country ?? '' }}</p>
                        <p class="venue-date">
                            {{ $event->start_datetime->format('M d, Y, h:i A') }} ({{ $event->timezone }})
                        </p>
                    </div>
                    
                    <div class="attendee-info">
                        <p class="info-label">ISSUED TO</p>
                        <p class="info-value">{{ $attendee->first_name }} {{ $attendee->last_name }}</p>
                    </div>
                    
                    <div class="ticket-grid">
                        <div class="grid-item">
                            <p class="info-label">ORDER NUMBER</p>
                            <p class="info-value">{{ $booking->booking_reference }}</p>
                            <p class="small-text">
                                Registered<br>
                                {{ $booking->created_at->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="grid-item">
                            <p class="info-label">TICKET</p>
                            <p class="info-value">{{ $attendee->ticket->name }}</p>
                            <p class="small-text">
                                {{ $attendee->ticket->price > 0 ? number_format($attendee->ticket->price, 2) . ' ' . $event->currency : 'FREE' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Right side: QR code -->
                <div class="qr-code">
                    <img src="{{ $attendee->qrCode }}" alt="QR Code">
                </div>
            </div>
            
            <!-- Ticket Footer -->
            <div class="ticket-footer">
                <p>© {{ date('Y') }} {{ $event->organisation->name }} - All Rights Reserved</p>
            </div>
        </div>
        
        @if($index < count($attendees) - 1)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
