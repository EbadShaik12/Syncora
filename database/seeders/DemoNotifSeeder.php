<?php
$u    = App\Models\User::where('email', 'priya@neuralcart.test')->first();
$corp = App\Models\User::where('email', 'rajesh@tatadigital.test')->first();

$notifs = [
    ['type' => 'match',       'title' => "It's a Match!",              'body' => 'You matched with TATA Digital Innovations. Start the conversation now.',          'link' => '/chat/1',             'read_at' => null],
    ['type' => 'message',     'title' => 'New message from TATA Digital', 'body' => 'Thursday 3 PM IST works well. Could you send a brief technical specification?', 'link' => '/chat/1',             'read_at' => null],
    ['type' => 'badge',       'title' => 'Badge Earned: First Connection', 'body' => 'You earned your first connection badge! Keep growing your network.',           'link' => null,                  'read_at' => null],
    ['type' => 'challenge',   'title' => 'New Challenge Posted',          'body' => 'HDFC Labs posted: Fraud Detection AI Solution. Deadline: Jun 30.',             'link' => '/startup/challenges', 'read_at' => now()],
    ['type' => 'application', 'title' => 'Application Update',            'body' => 'Your application to Apollo Healthcare has been shortlisted!',                   'link' => '/startup/applications', 'read_at' => null],
];

foreach ($notifs as $n) {
    App\Models\Notification::create(array_merge($n, ['user_id' => $u->id]));
}

echo 'Seeded ' . count($notifs) . ' notifications for ' . $u->name;
