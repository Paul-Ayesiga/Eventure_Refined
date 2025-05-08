<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Organisation;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Get the test organization
        $organisation = Organisation::where('name', 'Test Organization')->firstOrFail();

        // Common timezone and currency for all events
        $timezone = 'UTC';
        $currency = 'USD';

        // 1. Music Concert (Venue Event)
        Event::create([
            'organisation_id' => $organisation->id,
            'event_type' => 'venue',
            'name' => 'Summer Music Festival 2025',
            'venue' => 'Central Park Arena',
            'event_repeat' => 'Does not repeat',
            'start_date' => '2025-07-15',
            'start_datetime' => '2025-07-15 18:00:00',
            'end_datetime' => '2025-07-15 23:00:00',
            'timezone' => $timezone,
            'currency' => $currency,
            'status' => 'Published',
            'category' => 'Music',
            'description' => 'Join us for the biggest summer music festival featuring top artists and bands.',
            'tags' => ['music', 'festival', 'concert'],
            'auto_convert_timezone' => true
        ]);

        // 2. Tech Conference (Online Event)
        Event::create([
            'organisation_id' => $organisation->id,
            'event_type' => 'online',
            'name' => 'Future Tech Summit 2025',
            'event_repeat' => 'Does not repeat',
            'start_date' => '2025-09-20',
            'start_datetime' => '2025-09-20 09:00:00',
            'end_datetime' => '2025-09-20 17:00:00',
            'timezone' => $timezone,
            'currency' => $currency,
            'status' => 'Published',
            'category' => 'Technology',
            'description' => 'Virtual conference exploring the latest trends in technology and innovation.',
            'tags' => ['technology', 'conference', 'networking'],
            'auto_convert_timezone' => true
        ]);

        // 3. Weekly Workshop Series (Repeating Venue Event)
        Event::create([
            'organisation_id' => $organisation->id,
            'event_type' => 'venue',
            'name' => 'Art & Craft Workshop Series',
            'venue' => 'Creative Studio Downtown',
            'event_repeat' => 'Weekly',
            'repeat_days' => 8,
            'start_date' => '2025-06-01',
            'start_datetime' => '2025-06-01 14:00:00',
            'end_datetime' => '2025-06-01 16:00:00',
            'timezone' => $timezone,
            'currency' => $currency,
            'status' => 'Published',
            'category' => 'Art',
            'description' => 'Weekly hands-on workshops exploring different art and craft techniques.',
            'tags' => ['art', 'workshop', 'culture'],
            'auto_convert_timezone' => true
        ]);

        // 4. Business Networking (Hybrid Event)
        Event::create([
            'organisation_id' => $organisation->id,
            'event_type' => 'venue',
            'name' => 'Global Business Networking 2025',
            'venue' => 'Business Convention Center',
            'event_repeat' => 'Does not repeat',
            'start_date' => '2025-08-10',
            'start_datetime' => '2025-08-10 10:00:00',
            'end_datetime' => '2025-08-10 18:00:00',
            'timezone' => $timezone,
            'currency' => $currency,
            'status' => 'Published',
            'category' => 'Business',
            'description' => 'Connect with business leaders both in-person and virtually in this hybrid networking event.',
            'tags' => ['business', 'networking', 'conference'],
            'auto_convert_timezone' => true
        ]);

        // 5. Charity Run (Venue Event)
        Event::create([
            'organisation_id' => $organisation->id,
            'event_type' => 'venue',
            'name' => 'Charity Marathon 2025',
            'venue' => 'City Marathon Route',
            'event_repeat' => 'Does not repeat',
            'start_date' => '2025-10-05',
            'start_datetime' => '2025-10-05 07:00:00',
            'end_datetime' => '2025-10-05 14:00:00',
            'timezone' => $timezone,
            'currency' => $currency,
            'status' => 'Published',
            'category' => 'Sports',
            'description' => 'Annual charity marathon supporting local community initiatives.',
            'tags' => ['sports', 'charity'],
            'auto_convert_timezone' => true
        ]);

        // 6. Educational Seminar (Monthly Online Event)
        Event::create([
            'organisation_id' => $organisation->id,
            'event_type' => 'online',
            'name' => 'Professional Development Series',
            'event_repeat' => 'Monthly',
            'repeat_days' => 6,
            'start_date' => '2025-05-15',
            'start_datetime' => '2025-05-15 15:00:00',
            'end_datetime' => '2025-05-15 17:00:00',
            'timezone' => $timezone,
            'currency' => $currency,
            'status' => 'Published',
            'category' => 'Education',
            'description' => 'Monthly online seminars focusing on professional skills development.',
            'tags' => ['education', 'seminar', 'technology'],
            'auto_convert_timezone' => true
        ]);
    }
}
