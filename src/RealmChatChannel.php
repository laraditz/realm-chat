<?php

namespace Laraditz\RealmChat;

use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;

class RealmChatChannel
{
    /**
     * @var RealmChat
     */
    protected $realmChat;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * RealmChatChannel constructor.
     *
     * @param RealmChat $realmChat
     * @param Dispatcher $events
     */
    public function __construct(RealmChat $realmChat, Dispatcher $events)
    {
        $this->realmChat = $realmChat;
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('realmChat')) {
            return;
        }

        $message = $notification->toRealmChat($notifiable);

        if (!$message instanceof RealmChatMessage) {
            return;
        }

        try {
            return $this->realmChat->sendMessage($message, $to);
        } catch (\Throwable $th) {

            $event = new NotificationFailed(
                $notifiable,
                $notification,
                'realmChat',
                ['message' => $th->getMessage(), 'exception' => $th]
            );

            $this->events->dispatch($event);

            throw $th;
        }
    }
}
