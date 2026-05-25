<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function send(User $user, string $type, string $title, string $body, ?string $link = null, ?string $icon = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'link' => $link,
            'icon' => $icon,
        ]);
    }

    public function notifyMatch(User $u1, User $u2): void
    {
        $name1 = $u1->companyName();
        $name2 = $u2->companyName();

        $this->send($u1, 'match', "It's a Match!", "You matched with {$name2}. Start the conversation now.", route('chat.index'), 'heart');
        $this->send($u2, 'match', "It's a Match!", "You matched with {$name1}. Start the conversation now.", route('chat.index'), 'heart');
    }

    public function notifyMessage(User $receiver, User $sender, $connectionId): void
    {
        $this->send($receiver, 'message', "New message from {$sender->companyName()}", "Tap to read and reply", route('chat.show', $connectionId), 'message');
    }

    public function notifyApplication(User $corporate, User $startup, $challengeTitle): void
    {
        $this->send($corporate, 'application', "New application received", "{$startup->companyName()} applied to: {$challengeTitle}", route('corporate.dashboard'), 'document');
    }
}
