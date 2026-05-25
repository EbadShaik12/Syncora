<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            ['name' => 'First Connection', 'slug' => 'first-connection', 'description' => 'You made your first successful match!', 'icon' => 'handshake', 'color' => 'blue', 'criteria_type' => 'connections', 'criteria_value' => 1],
            ['name' => 'Power Networker', 'slug' => 'power-networker', 'description' => 'Connected with 5+ partners', 'icon' => 'bolt', 'color' => 'purple', 'criteria_type' => 'connections', 'criteria_value' => 5],
            ['name' => 'Super Connector', 'slug' => 'super-connector', 'description' => 'Connected with 10+ partners', 'icon' => 'star', 'color' => 'yellow', 'criteria_type' => 'connections', 'criteria_value' => 10],
            ['name' => 'Conversation Starter', 'slug' => 'conversation-starter', 'description' => 'Sent your first message', 'icon' => 'message', 'color' => 'green', 'criteria_type' => 'messages', 'criteria_value' => 1],
            ['name' => 'Active Communicator', 'slug' => 'active-communicator', 'description' => 'Sent 20+ messages', 'icon' => 'chat', 'color' => 'blue', 'criteria_type' => 'messages', 'criteria_value' => 20],
            ['name' => 'Storyteller', 'slug' => 'storyteller', 'description' => 'Added 3+ milestones to your journey', 'icon' => 'book', 'color' => 'orange', 'criteria_type' => 'milestones', 'criteria_value' => 3],
            ['name' => 'Profile Pro', 'slug' => 'profile-pro', 'description' => 'Completed your profile fully', 'icon' => 'check', 'color' => 'green', 'criteria_type' => 'profile_complete', 'criteria_value' => 1],
        ];

        foreach ($badges as $b) {
            Badge::firstOrCreate(['slug' => $b['slug']], $b);
        }
    }
}
