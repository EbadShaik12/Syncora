<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Connection;
use App\Models\Challenge;
use App\Models\ChallengeApplication;
use App\Models\Message;
use App\Models\InterestSignal;
use Illuminate\Http\Response;

class AdminReportsController extends Controller
{
    public function index()
    {
        // System health metrics
        $health = [
            'db_users'       => User::count(),
            'db_connections' => Connection::count(),
            'db_messages'    => Message::count(),
            'db_signals'     => InterestSignal::count(),
            'pending_users'  => User::where('status', 'pending')->count(),
            'open_challenges'=> Challenge::where('status', 'open')->count(),
            'pending_apps'   => ChallengeApplication::where('status', 'pending')->count(),
        ];

        // Growth by month (last 6 months)
        $growth = [];
        for ($i = 5; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $label = $date->format('M Y');
            $growth[] = [
                'month'      => $label,
                'startups'   => User::where('role', 'startup')->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
                'corporates' => User::where('role', 'corporate')->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
                'connections'=> Connection::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
            ];
        }

        return view('admin.reports', compact('health', 'growth'));
    }

    public function exportUsers()
    {
        $users = User::where('role', '!=', 'admin')
            ->with('startupProfile', 'corporateProfile')
            ->get();

        $csv = "ID,Name,Email,Role,Status,Company,Joined\n";
        foreach ($users as $u) {
            $csv .= implode(',', [
                $u->id,
                '"' . $u->name . '"',
                $u->email,
                $u->role,
                $u->status,
                '"' . $u->companyName() . '"',
                $u->created_at->format('Y-m-d'),
            ]) . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="syncora_users_' . now()->format('Ymd') . '.csv"',
        ]);
    }

    public function exportConnections()
    {
        $connections = Connection::with('userOne', 'userTwo')->get();

        $csv = "ID,User One,User Two,Status,Matched At\n";
        foreach ($connections as $c) {
            $csv .= implode(',', [
                $c->id,
                '"' . ($c->userOne?->companyName() ?? 'N/A') . '"',
                '"' . ($c->userTwo?->companyName() ?? 'N/A') . '"',
                $c->status,
                $c->matched_at?->format('Y-m-d') ?? '',
            ]) . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="syncora_connections_' . now()->format('Ymd') . '.csv"',
        ]);
    }
}
