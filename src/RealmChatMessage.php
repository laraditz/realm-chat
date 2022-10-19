<?php

namespace Laraditz\RealmChat;

use Illuminate\Support\Str;

class RealmChatMessage
{
    /** @var string|null */
    public $deviceId = null;

    /** @var string|null */
    public $content = null;

    /** @var string */
    public $mediaUrl;

    /** @var string */
    public $mediaName;

    public function __construct(string $content = null)
    {
        $this->deviceId = config('realm-chat.device_id');
        $this->content = $content;
    }

    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function deviceId(string $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function media(string $mediaUrl, string $mediaName): self
    {
        $this->mediaUrl($mediaUrl)->mediaName($mediaName);

        return $this;
    }

    public function mediaUrl(string $mediaUrl): self
    {
        $this->mediaUrl = $mediaUrl;

        return $this;
    }

    public function mediaName(string $mediaName): self
    {
        $this->mediaName = $mediaName;

        return $this;
    }
}
