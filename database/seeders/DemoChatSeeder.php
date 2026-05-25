<?php

use App\Models\User;
use App\Models\Connection;
use App\Models\Message;
use App\Models\InterestSignal;

$startup = User::where('email', 'priya@neuralcart.test')->first();
$corp    = User::where('email', 'rajesh@tatadigital.test')->first();

// Mutual interest signals
InterestSignal::firstOrCreate(
    ['sender_id' => $startup->id, 'receiver_id' => $corp->id],
    ['status' => 'interested']
);
InterestSignal::firstOrCreate(
    ['sender_id' => $corp->id, 'receiver_id' => $startup->id],
    ['status' => 'interested']
);

// Create active connection
$conn = Connection::firstOrCreate(
    [
        'user_one_id' => min($startup->id, $corp->id),
        'user_two_id' => max($startup->id, $corp->id),
    ],
    ['status' => 'active', 'matched_at' => now()]
);

// Seed realistic messages
$messages = [
    ['sender_id' => $corp->id,    'content' => 'Hi Priya! We reviewed your NeuralCart AI pitch deck — the personalisation engine looks very promising for our retail division.', 'created_at' => now()->subMinutes(45), 'read_at' => now()->subMinutes(43)],
    ['sender_id' => $startup->id, 'content' => 'Thank you Rajesh! We have been following Tata Digital closely and see a strong synergy. Our recommendation engine achieves 34% uplift in conversion on average.', 'created_at' => now()->subMinutes(42), 'read_at' => now()->subMinutes(40)],
    ['sender_id' => $corp->id,    'content' => 'Those are impressive numbers. Can you walk us through the integration timeline and data requirements? We are planning a pilot for Q3.', 'created_at' => now()->subMinutes(35), 'read_at' => now()->subMinutes(33)],
    ['sender_id' => $startup->id, 'content' => 'Absolutely! Integration typically takes 4–6 weeks. We need read access to anonymised purchase history. Happy to schedule a technical deep-dive this week? 🚀', 'created_at' => now()->subMinutes(30), 'read_at' => now()->subMinutes(28)],
    ['sender_id' => $corp->id,    'content' => 'Thursday 3 PM IST works well. Could you send a brief technical specification document beforehand so our engineering team can review it?', 'created_at' => now()->subMinutes(10), 'read_at' => null],
];

foreach ($messages as $msg) {
    Message::create(array_merge($msg, ['connection_id' => $conn->id]));
}

echo "Done! Connection ID: {$conn->id}, Messages: " . count($messages);
