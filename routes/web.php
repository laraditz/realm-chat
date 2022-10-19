<?php

use Illuminate\Support\Facades\Route;
use Laraditz\RealmChat\Http\Controllers\WebhookController;

Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::match(['get', 'post'], '/receive', [WebhookController::class, 'receive'])->name('receive');
});
