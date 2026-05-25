<?php
// Demo Kanban seeder — seeds applications across all pipeline stages
$corporate = App\Models\User::where('email', 'rajesh@tatadigital.test')->first();

// Get or create a challenge
$challenge = App\Models\Challenge::where('corporate_id', $corporate->id)->first();
if (!$challenge) {
    $industry = App\Models\Industry::first();
    $challenge = App\Models\Challenge::create([
        'corporate_id' => $corporate->id,
        'title'        => 'AI-Powered Personalization Engine',
        'description'  => 'We are looking for startups building AI recommendation engines for e-commerce personalization at scale. Must support real-time inference with sub-100ms latency.',
        'requirements' => 'Experience with ML pipelines, REST APIs, and cloud deployment.',
        'industry_id'  => $industry->id,
        'budget_min'   => 500000,
        'budget_max'   => 2500000,
        'deadline'     => now()->addMonths(2),
        'required_tags'=> ['AI', 'Machine Learning', 'Python'],
        'status'       => 'open',
    ]);
    echo "Created challenge: {$challenge->id}\n";
} else {
    echo "Using existing challenge: {$challenge->id} - {$challenge->title}\n";
}

// Get startups to apply
$startups = App\Models\User::where('role', 'startup')->where('status', 'approved')->get();

$statuses = ['pending', 'reviewing', 'shortlisted', 'interview', 'rejected'];
$letters  = [
    "We have built a production-grade recommendation engine serving 2M+ users daily with 34% uplift in CTR.",
    "Our ML stack uses TensorFlow + FastAPI with real-time A/B testing. We can integrate within 4 weeks.",
    "NeuralCart's engine powers personalisation for 3 D2C brands. We are ready to pilot with your catalog.",
    "Our approach combines collaborative filtering with LLM embeddings. Demo available on request.",
    "Proven track record in e-commerce AI. We reduced cart abandonment by 28% for our last client.",
    "We specialise in privacy-preserving ML — no raw data leaves your servers.",
];

$created = 0;
foreach ($startups->take(5) as $i => $startup) {
    $existing = App\Models\ChallengeApplication::where('challenge_id', $challenge->id)
        ->where('startup_id', $startup->id)->first();
    if (!$existing) {
        App\Models\ChallengeApplication::create([
            'challenge_id' => $challenge->id,
            'startup_id'   => $startup->id,
            'cover_letter' => $letters[$i % count($letters)],
            'approach'     => 'Phase 1: Integration audit (1 week). Phase 2: Pilot deployment (3 weeks). Phase 3: Full rollout (ongoing).',
            'status'       => $statuses[$i % count($statuses)],
        ]);
        $created++;
    }
}

echo "Seeded {$created} applications for challenge '{$challenge->title}' (ID: {$challenge->id})";
