<?php

namespace Laraditz\RealmChat\Http\Controllers;

use Illuminate\Http\Request;
use Laraditz\RealmChat\Events\MessageReceived;
use Laraditz\RealmChat\RealmChat;

class WebhookController extends Controller
{
    public function receive(Request $request)
    {
        if ($request->all()) {
            logger()->info('Realm Chat message received', $request->all());

            event(new MessageReceived($request->all()));

            app(RealmChat::class)->saveMessageFromWebhook($request->toArray());
        }
    }
}
