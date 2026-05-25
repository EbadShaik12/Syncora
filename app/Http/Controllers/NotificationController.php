<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);

        // Mark all as read when viewing the page
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return view('notifications', compact('notifications'));
    }

    public function unreadCount()
    {
        $count = auth()->user()->notifications()->whereNull('read_at')->count();

        // Also check for new match notifications to trigger celebration
        $latestMatch = auth()->user()->notifications()
            ->where('type', 'match')
            ->whereNull('read_at')
            ->latest()
            ->first();

        return response()->json([
            'count'       => $count,
            'latest_match' => $latestMatch ? [
                'id'    => $latestMatch->id,
                'title' => $latestMatch->title,
                'body'  => $latestMatch->body,
                'link'  => $latestMatch->link,
            ] : null,
        ]);
    }

    public function dropdown()
    {
        $notifications = auth()->user()->notifications()->latest()->take(10)->get();

        return response()->json([
            'notifications' => $notifications->map(fn($n) => [
                'id'         => $n->id,
                'type'       => $n->type,
                'title'      => $n->title,
                'body'       => $n->body,
                'link'       => $n->link,
                'icon'       => $n->icon,
                'icon_color' => $n->iconColor(),
                'icon_svg'   => $n->iconSvg(),
                'is_unread'  => $n->isUnread(),
                'time_ago'   => $n->created_at->diffForHumans(),
            ]),
        ]);
    }

    public function markRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) abort(403);
        $notification->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
}
