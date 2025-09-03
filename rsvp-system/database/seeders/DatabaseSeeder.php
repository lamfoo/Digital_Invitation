<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@rsvp.com',
            'email_verified_at' => now(),
        ]);

        // Create sample events
        $weddingEvent = Event::create([
            'title' => 'John & Sarah\'s Wedding',
            'location' => 'Grand Ballroom, Downtown Hotel',
            'event_date' => Carbon::now()->addDays(30),
            'event_time' => '18:00',
            'description' => 'Join us for a celebration of love as we exchange vows and begin our journey together. Dinner and dancing to follow.',
            'rsvp_expiration_at' => Carbon::now()->addDays(20),
        ]);

        $birthdayEvent = Event::create([
            'title' => 'Emma\'s 30th Birthday Bash',
            'location' => 'Rooftop Terrace, Sky Lounge',
            'event_date' => Carbon::now()->addDays(15),
            'event_time' => '19:30',
            'description' => 'Come celebrate three decades of awesomeness! Cocktails, music, and great company await.',
            'rsvp_expiration_at' => Carbon::now()->addDays(10),
        ]);

        $corporateEvent = Event::create([
            'title' => 'Annual Company Retreat',
            'location' => 'Mountain Resort Conference Center',
            'event_date' => Carbon::now()->addDays(45),
            'event_time' => '09:00',
            'description' => 'Team building activities, strategic planning sessions, and networking opportunities in a beautiful mountain setting.',
            'rsvp_expiration_at' => Carbon::now()->addDays(35),
        ]);

        // Create sample guests for wedding
        $weddingGuests = [
            'Michael Johnson', 'Emily Davis', 'David Wilson', 'Lisa Brown',
            'Robert Taylor', 'Jennifer Martinez', 'William Anderson', 'Jessica Thomas'
        ];

        foreach ($weddingGuests as $index => $guestName) {
            $guest = Guest::create([
                'event_id' => $weddingEvent->id,
                'name' => $guestName,
            ]);

            // Simulate some responses
            if ($index < 4) {
                $statuses = ['yes', 'yes', 'no', 'maybe'];
                $guest->update([
                    'rsvp_status' => $statuses[$index],
                    'rsvp_confirmed_at' => Carbon::now()->subDays(rand(1, 10)),
                ]);
            }
        }

        // Create sample guests for birthday
        $birthdayGuests = [
            'Alex Thompson', 'Maria Garcia', 'Chris Lee', 'Amanda White',
            'Daniel Rodriguez', 'Samantha Clark'
        ];

        foreach ($birthdayGuests as $index => $guestName) {
            $guest = Guest::create([
                'event_id' => $birthdayEvent->id,
                'name' => $guestName,
            ]);

            // Simulate some responses
            if ($index < 3) {
                $statuses = ['yes', 'yes', 'maybe'];
                $guest->update([
                    'rsvp_status' => $statuses[$index],
                    'rsvp_confirmed_at' => Carbon::now()->subDays(rand(1, 5)),
                ]);
            }
        }

        // Create sample guests for corporate event
        $corporateGuests = [
            'John Smith', 'Sarah Connor', 'Mike Ross', 'Rachel Green',
            'Harvey Specter', 'Monica Geller', 'Ross Geller', 'Chandler Bing',
            'Joey Tribbiani', 'Phoebe Buffay'
        ];

        foreach ($corporateGuests as $guestName) {
            Guest::create([
                'event_id' => $corporateEvent->id,
                'name' => $guestName,
            ]);
        }
    }
}
