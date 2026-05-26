<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        if ($request->has('q') && $request->q != '') {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhereHas('startupProfile', function ($profile) use ($q) {
                        $profile->where('company_name', 'like', "%{$q}%");
                    })
                    ->orWhereHas('corporateProfile', function ($profile) use ($q) {
                        $profile->where('company_name', 'like', "%{$q}%");
                    });
            });
        }

        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function approve(User $user)
    {
        $user->update(['status' => 'approved']);

        Notification::create([
            'user_id' => $user->id,
            'title'   => 'Account Approved! 🎉',
            'body'    => 'Congratulations! Your account has been approved. You now have full access to matching, challenges, and swiping!',
            'type'    => 'milestone',
        ]);

        return redirect()->back()->with('success', "Account of {$user->name} has been successfully approved!");
    }

    public function reject(User $user)
    {
        $user->update(['status' => 'rejected']);

        Notification::create([
            'user_id' => $user->id,
            'title'   => 'Account Application Status',
            'body'    => 'We regret to inform you that your registration application has been rejected.',
            'type'    => 'milestone',
        ]);

        return redirect()->back()->with('warning', "Account of {$user->name} has been rejected.");
    }

    public function toggleSuspend(User $user)
    {
        if ($user->status === 'suspended') {
            $user->update(['status' => 'approved']);
            $msg = "Account of {$user->name} has been restored successfully.";
            $type = 'success';
        } else {
            $user->update(['status' => 'suspended']);
            $msg = "Account of {$user->name} has been suspended.";
            $type = 'warning';
        }

        return redirect()->back()->with($type, $msg);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', "User account has been deleted successfully.");
    }
}
