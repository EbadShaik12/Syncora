<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\Message;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $connections = $user->getAllConnections()->load([
            'userOne.startupProfile',
            'userOne.corporateProfile',
            'userTwo.startupProfile',
            'userTwo.corporateProfile',
            'latestMessage',
        ]);

        // Sort by latest message or matched_at
        $connections = $connections->sortByDesc(function ($c) {
            return $c->latestMessage?->created_at ?? $c->matched_at;
        })->values();

        // Attach unread count per connection
        $connections->each(function ($c) use ($user) {
            $c->unread_count = Message::where('connection_id', $c->id)
                ->where('sender_id', '!=', $user->id)
                ->whereNull('read_at')
                ->count();
        });

        return view('chat.index', compact('connections'));
    }

    public function show(Connection $connection)
    {
        $user = auth()->user();
        if ($connection->user_one_id !== $user->id && $connection->user_two_id !== $user->id) {
            abort(403);
        }

        // Mark messages as read
        Message::where('connection_id', $connection->id)
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $other = $connection->otherUser($user->id);
        $other->load('startupProfile.industry', 'corporateProfile.industry');
        $messages = $connection->messages()->with('sender')->orderBy('id')->get();
        $connections = $user->getAllConnections()->load([
            'userOne', 'userTwo', 'latestMessage',
        ])->sortByDesc(fn($c) => $c->latestMessage?->created_at ?? $c->matched_at)->values();

        // Attach unread counts for sidebar
        $connections->each(function ($c) use ($user) {
            $c->unread_count = Message::where('connection_id', $c->id)
                ->where('sender_id', '!=', $user->id)
                ->whereNull('read_at')
                ->count();
        });

        return view('chat.show', compact('connection', 'other', 'messages', 'connections'));
    }

    public function sendMessage(Request $request, Connection $connection, NotificationService $notificationService)
    {
        $user = auth()->user();
        if ($connection->user_one_id !== $user->id && $connection->user_two_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $message = Message::create([
            'connection_id' => $connection->id,
            'sender_id'     => $user->id,
            'content'       => $validated['content'],
        ]);

        // Clear typing indicator when message is sent
        Cache::forget("typing:{$connection->id}:{$user->id}");

        $receiver = $connection->otherUser($user->id);
        $notificationService->notifyMessage($receiver, $user, $connection->id);

        // Dynamic Badge Award Trigger
        app(\App\Services\BadgeService::class)->checkAndAward($user);

        return response()->json([
            'success' => true,
            'message' => [
                'id'         => $message->id,
                'content'    => $message->content,
                'sender_id'  => $message->sender_id,
                'created_at' => $message->created_at->format('h:i A'),
                'date_label' => $message->created_at->isToday()
                    ? 'Today'
                    : ($message->created_at->isYesterday() ? 'Yesterday' : $message->created_at->format('M d, Y')),
                'is_mine'    => true,
                'read_at'    => null,
            ],
        ]);
    }

    public function fetchMessages(Request $request, Connection $connection)
    {
        $user = auth()->user();
        if ($connection->user_one_id !== $user->id && $connection->user_two_id !== $user->id) {
            abort(403);
        }

        $afterId = $request->get('after', 0);
        $messages = Message::where('connection_id', $connection->id)
            ->where('id', '>', $afterId)
            ->where('sender_id', '!=', $user->id)
            ->orderBy('id')
            ->get();

        // Mark as read
        Message::where('connection_id', $connection->id)
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Check if current user's sent messages have been read
        $readUpdate = Message::where('connection_id', $connection->id)
            ->where('sender_id', $user->id)
            ->whereNotNull('read_at')
            ->max('id');

        return response()->json([
            'messages' => $messages->map(fn($m) => [
                'id'         => $m->id,
                'content'    => $m->content,
                'sender_id'  => $m->sender_id,
                'created_at' => $m->created_at->format('h:i A'),
                'date_label' => $m->created_at->isToday()
                    ? 'Today'
                    : ($m->created_at->isYesterday() ? 'Yesterday' : $m->created_at->format('M d, Y')),
                'is_mine'    => false,
                'read_at'    => $m->read_at?->format('h:i A'),
            ]),
            'last_read_sent_id' => $readUpdate,
        ]);
    }

    /**
     * Store a "currently typing" flag in cache for 4 seconds.
     */
    public function setTyping(Request $request, Connection $connection)
    {
        $user = auth()->user();
        if ($connection->user_one_id !== $user->id && $connection->user_two_id !== $user->id) {
            abort(403);
        }

        Cache::put("typing:{$connection->id}:{$user->id}", true, now()->addSeconds(4));

        return response()->json(['ok' => true]);
    }

    /**
     * Check whether the OTHER user in this connection is typing.
     */
    public function getTyping(Connection $connection)
    {
        $user    = auth()->user();
        if ($connection->user_one_id !== $user->id && $connection->user_two_id !== $user->id) {
            abort(403);
        }

        $otherId  = $connection->user_one_id === $user->id
            ? $connection->user_two_id
            : $connection->user_one_id;

        $isTyping = Cache::get("typing:{$connection->id}:{$otherId}", false);

        return response()->json(['typing' => (bool) $isTyping]);
    }

    public function rateConnection(Request $request, Connection $connection)
    {
        $user = auth()->user();
        if ($connection->user_one_id !== $user->id && $connection->user_two_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($connection->user_one_id == $user->id) {
            $connection->update([
                'rating_by_user_one' => $validated['rating'],
                'review_by_user_one' => $validated['review'],
            ]);
        } else {
            $connection->update([
                'rating_by_user_two' => $validated['rating'],
                'review_by_user_two' => $validated['review'],
            ]);
        }

        // Award badge checking dynamically after rating!
        app(\App\Services\BadgeService::class)->checkAndAward($user);

        return back()->with('success', 'Partner rated successfully!');
    }
}
