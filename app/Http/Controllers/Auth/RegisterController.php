<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Models\User;
use App\Models\StartupProfile;
use App\Models\CorporateProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function showRoleSelection()
    {
        return view('auth.register');
    }

    public function showStartupForm()
    {
        $industries = Industry::orderBy('name')->get();
        return view('auth.register-startup', compact('industries'));
    }

    public function registerStartup(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', 'confirmed', Password::defaults()],
            'company_name'   => ['required', 'string', 'max:255'],
            'industry_id'    => ['required', 'exists:industries,id'],
            'stage'          => ['required', 'in:idea,mvp,growth,scale'],
            'elevator_pitch' => ['required', 'string', 'min:20', 'max:1000'],
            'city'           => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'startup',
                'status'   => 'pending',
            ]);

            StartupProfile::create([
                'user_id'        => $user->id,
                'company_name'   => $validated['company_name'],
                'industry_id'    => $validated['industry_id'],
                'stage'          => $validated['stage'],
                'elevator_pitch' => $validated['elevator_pitch'],
                'city'           => $validated['city'],
            ]);

            // Do NOT log in — account needs admin approval first
        });

        return redirect()->route('login')->with('success', '🎉 Account created! Your application is under review. We\'ll notify you once approved.');
    }

    public function showCorporateForm()
    {
        $industries = Industry::orderBy('name')->get();
        return view('auth.register-corporate', compact('industries'));
    }

    public function registerCorporate(Request $request)
    {
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'          => ['required', 'confirmed', Password::defaults()],
            'company_name'      => ['required', 'string', 'max:255'],
            'industry_id'       => ['required', 'exists:industries,id'],
            'company_size'      => ['required', 'in:small,medium,large,enterprise'],
            'problem_statement' => ['required', 'string', 'min:20', 'max:1000'],
            'city'              => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'corporate',
                'status'   => 'pending',
            ]);

            CorporateProfile::create([
                'user_id'           => $user->id,
                'company_name'      => $validated['company_name'],
                'industry_id'       => $validated['industry_id'],
                'company_size'      => $validated['company_size'],
                'problem_statement' => $validated['problem_statement'],
                'city'              => $validated['city'],
            ]);

            // Do NOT log in — account needs admin approval first
        });

        return redirect()->route('login')->with('success', '🎉 Account created! Your application is under review. We\'ll notify you once approved.');
    }

    // ── Admin registration ───────────────────────────────────────────────────

    public function showAdminForm()
    {
        return view('auth.admin-register');
    }

    public function registerAdmin(Request $request)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'         => ['required', 'confirmed', Password::defaults()],
            'admin_secret_key' => ['required', 'string'],
        ]);

        // Verify the admin secret key from .env
        $correctKey = env('ADMIN_SECRET_KEY', 'startupadmin2025');
        if ($validated['admin_secret_key'] !== $correctKey) {
            throw ValidationException::withMessages([
                'admin_secret_key' => 'Invalid admin secret key. Access denied.',
            ]);
        }

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'admin',
            'status'   => 'approved',
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Admin account created successfully. Welcome, ' . $user->name . '!');
    }
}
