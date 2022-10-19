# Laravel Realm Chat

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laraditz/realm-chat.svg?style=flat-square)](https://packagist.org/packages/laraditz/realm-chat)
[![Total Downloads](https://img.shields.io/packagist/dt/laraditz/realm-chat.svg?style=flat-square)](https://packagist.org/packages/laraditz/realm-chat)
![GitHub Actions](https://github.com/laraditz/realm-chat/actions/workflows/main.yml/badge.svg)

Laravel wrapper for Realm Chat SDK. Includes event for receiving messages.

## Installation

You can install the package via composer:

```bash
composer require laraditz/realm-chat
```

## Before Start

Configure your variables in your `.env` (recommended) or you can publish the config file and change it there.
```
REALM_CHAT_API_KEY=<your_api_key>
REALM_CHAT_DEVICE_ID=<your_device_id>
```

(Optional) You can publish the config file via this command:
```bash
php artisan vendor:publish --provider="Laraditz\RealmChat\RealmChatServiceProvider" --tag="config"
```

Run the migration command to create the necessary database table.
```bash
php artisan migrate
```

Add `routeNotificationForRealmChat` method to your Notifiable model.
```php
public function routeNotificationForRealmChat($notification)
{
    return $this->mobile_no;
}
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Laraditz\RealmChat\RealmChatChannel;
use Laraditz\RealmChat\RealmChatMessage;

class TestNotification extends Notification
{
    use Queueable;  
  
    public function via($notifiable)
    {
        return [RealmChatChannel::class];
    }
    
    public function toRealmChat($notifiable)
    {
        return (new RealmChatMessage())
            ->content("Test send message!")
            ->media("https://news.tokunation.com/wp-content/uploads/sites/5/2022/07/Kamen-Rider-Geats-Teaser.jpeg", "Kamen-Rider-Geats-Teaser.jpeg");
    }
}
```

## Event

This package also provide an event to allow your application to listen for Realm Chat message receive. You can create your listener and register it under event below.

| Event                                        |  Description  
|----------------------------------------------|--------------------------|
| Laraditz\RealmChat\Events\MessageReceived    | When a message comes in.

## Webhook URL

You may setup the URLs below on Realm Chat dashboard so that Realm Chat will push new messages to it and it will then trigger the `MessageReceived` event above.

```
https://your-app-url/realm-chat/webhooks/receive //for message receive
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email raditzfarhan@gmail.com instead of using the issue tracker.

## Credits

-   [Raditz Farhan](https://github.com/laraditz)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Dependency

- [Realm Chat SDK](https://github.com/raditzfarhan/realm-chat).
