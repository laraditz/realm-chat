<?php

namespace Laraditz\RealmChat;

use Illuminate\Support\Facades\DB;
use RaditzFarhan\RealmChat\RealmChat as RealmChatClient;
use Laraditz\RealmChat\Models\RealmChatLog;
use Laraditz\RealmChat\Models\RealmChatMessage as RealmChatMessageModel;
use Laraditz\RealmChat\Enums\MessageType;
use Laraditz\RealmChat\Enums\MessageDirection;

class RealmChat
{
    /** @var client */
    protected $client;

    public function __construct(RealmChatClient $client)
    {
        $this->client = $client;
    }

    public function sendMessage(RealmChatMessage $message, string $to)
    {
        if ($message instanceof RealmChatMessage) {
            return $this->createMessage($message, $to);
        }
    }

    public function createMessage(RealmChatMessage $message, string $to)
    {
        try {
            $this->client->setDeviceId($message->deviceId);

            if ($message->mediaUrl) {
                $result = $this->client->sendMessage(
                    number: $to,
                    message: trim($message->content),
                    fileUrl: $message->mediaUrl,
                    fileName: $message->mediaName
                );
            } else {
                $result = $this->client->sendMessage(
                    number: $to,
                    message: trim($message->content)
                );
            }

            if (isset($result['id_message'])) {
                $source = __METHOD__;
                DB::transaction(function () use ($source, $to, $message, $result) {

                    $requestData = $this->getRequestData($message, $to);

                    RealmChatLog::create([
                        'source' => $source,
                        'request' => $requestData,
                        'response' => $result,
                    ]);

                    $this->saveMessage($requestData, $result);
                });
            }

            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function saveMessage(array $request, array $result)
    {
        RealmChatMessageModel::updateOrCreate([
            'message_id' => $result['id_message'],
        ], [
            'device_id' => $request['device_id'],
            'phone_number' => $request['phone_number'],
            'body' => $request['message'],
            'media_url' => data_get($request, 'media_url'),
            'direction' => MessageDirection::Outgoing,
            'type' => data_get($request, 'media_url') ? MessageType::Image : MessageType::Text,
        ]);
    }

    public function saveMessageFromWebhook($data)
    {
        $message_id = data_get($data, 'id_message');
        if ($message_id) {
            RealmChatMessageModel::updateOrCreate([
                'message_id' => $message_id,
            ], [
                'device_id' => config('realm-chat.device_id'),
                'phone_number' => $this->getPhoneNumber($data),
                'body' => data_get($data, 'message') ? trim(data_get($data, 'message')) : null,
                'media_url' => $this->getMediaUrl($data),
                'direction' => $this->getDirection($data),
                'type' => $this->getType($data),
            ]);
        }
    }

    private function getPhoneNumber($data)
    {
        return data_get($data, 'from') ?? data_get($data, 'to');
    }

    private function getDirection($data)
    {
        if (data_get($data, 'from')) {
            return MessageDirection::Incoming;
        }

        return MessageDirection::Outgoing;
    }

    private function getType($data)
    {
        $type = data_get($data, 'type_message');

        if ($type && $type == 'text') {
            return MessageType::Text;
        } elseif ($type && $type == 'image') {
            return MessageType::Image;
        } elseif (data_get($data, 'image')) {
            return MessageType::Image;
        }

        return MessageType::Text;
    }

    private function getMediaUrl($data)
    {
        if (data_get($data, 'image')) {
            return data_get($data, 'image');
        }

        return null;
    }

    private function getRequestData($message, $to)
    {
        $requestData = [
            'device_id' => $message->deviceId,
            'phone_number' => $to,
            'message' => trim($message->content)
        ];

        if ($message->mediaUrl) {
            $requestData = array_merge($requestData, [
                'media_url' => $message->mediaUrl,
                'media_name' => $message->mediaName,
            ]);
        }

        return $requestData;
    }
}
