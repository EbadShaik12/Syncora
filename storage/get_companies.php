<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::where('role', '!=', 'admin')->with('startupProfile.industry', 'corporateProfile.industry')->get();
foreach ($users as $u) {
    echo "ID: " . $u->id . "\n";
    echo "Role: " . $u->role . "\n";
    echo "Company: " . $u->companyName() . "\n";
    echo "Email: " . $u->email . "\n";
    if ($u->isStartup()) {
        $p = $u->startupProfile;
        echo "Industry: " . ($p->industry?->name ?? 'N/A') . "\n";
        echo "Stage: " . $p->stage . "\n";
        echo "Pitch: " . $p->elevator_pitch . "\n";
        echo "Tags: " . json_encode($p->tech_tags) . "\n";
    } else {
        $p = $u->corporateProfile;
        echo "Industry: " . ($p->industry?->name ?? 'N/A') . "\n";
        echo "Size: " . $p->company_size . "\n";
        echo "Problem: " . $p->problem_statement . "\n";
        echo "Tech Seeking: " . json_encode($p->seeking_technologies) . "\n";
    }
    echo "----------------------------------------\n";
}
