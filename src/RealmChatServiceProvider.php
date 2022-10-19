<?php

namespace Laraditz\RealmChat;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use RaditzFarhan\RealmChat\RealmChat as RealmChatClient;

class RealmChatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'realm-chat');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'realm-chat');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('realm-chat.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/realm-chat'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/realm-chat'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/realm-chat'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'realm-chat');

        $this->app->singleton(RealmChatClient::class, function () {
            return new RealmChatClient(config('realm-chat.api_key'));
        });

        // Register the main class to use with the facade
        $this->app->singleton(RealmChat::class, function (Application $app) {
            return new RealmChat(
                $app->make(RealmChatClient::class)
            );
        });

        $this->app->singleton(RealmChatChannel::class, function (Application $app) {
            return new RealmChatChannel(
                $app->make(RealmChat::class),
                $app->make(Dispatcher::class)
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            RealmChatClient::class,
            RealmChatChannel::class,
        ];
    }
}
