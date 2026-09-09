<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

class Fcm
{
    /**
     * Server key that matches the mobile app Firebase project.
     */
    const APP_SERVER_KEY = 'AAAAyNv4XM4:APA91bG2LLYhnWhlCeyruuWk2JANSzG2O8h1NpqD2zDv68Da5zTgQfc4UgPjdwEbK_JDOdkbf8uFpgWtnWHyzjq484P4_2ntc0vaqqLa_Hegu2Lhlxz6JQZCM2pU-nTFBy6WdLPDcAug';

    /**
     * Send an FCM notification, trying the configured key then the app key.
     *
     * @param  string|array  $tokens
     * @param  string  $title
     * @param  string  $body
     * @param  array  $data
     * @param  string|null  $platform
     * @return array
     */
    public static function send($tokens, $title, $body, array $data = [], $platform = null)
    {
        $tokens = is_array($tokens) ? $tokens : [$tokens];
        $tokens = array_values(array_unique(array_filter($tokens, function ($token) {
            return !empty($token) && $token !== 'logout';
        })));

        if (!$tokens) {
            Log::warning('FCM skipped: no device tokens');

            return ['success' => 0, 'failure' => 0, 'skipped' => true];
        }

        $data = array_map(function ($value) {
            return $value === null ? '' : (string) $value;
        }, $data);

        $title = (string) $title;
        $body = (string) $body;
        $isIos = strtolower((string) $platform) === 'ios';

        $notification = [
            'title' => $title,
            'body' => $body,
            'sound' => 'default',
            'badge' => 1,
        ];

        if (! $isIos) {
            $notification['text'] = $body;
            $notification['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
            $channelId = config('services.fcm.android_channel_id');
            if ($channelId) {
                $notification['android_channel_id'] = $channelId;
            }
        }

        $payload = [
            'priority' => 'high',
            'time_to_live' => 86400,
            'mutable_content' => true,
            'content_available' => true,
            'notification' => $notification,
            'data' => array_merge($data, [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ]),
        ];

        $last = ['success' => 0, 'failure' => 0];

        foreach (array_chunk($tokens, 1000) as $chunk) {
            $payload['registration_ids'] = $chunk;
            $chunkResult = static::sendPayload($payload);
            $last['success'] += $chunkResult['success'] ?? 0;
            $last['failure'] += $chunkResult['failure'] ?? 0;
            $last['raw'] = $chunkResult['raw'] ?? null;
            $last['results'] = $chunkResult['results'] ?? [];
        }

        return $last;
    }

    /**
     * @param  array  $payload
     * @return array
     */
    protected static function sendPayload(array $payload)
    {
        $lastResponse = null;
        $lastDecoded = ['success' => 0, 'failure' => count($payload['registration_ids'] ?? [])];

        foreach (static::serverKeys() as $serverKey) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: key='.$serverKey,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                Log::error('FCM curl error', ['error' => $error]);
                continue;
            }

            $decoded = json_decode($response, true);
            $lastResponse = $response;
            if (is_array($decoded)) {
                $lastDecoded = $decoded;
                $lastDecoded['raw'] = $response;
            } else {
                $lastDecoded = ['success' => 0, 'failure' => 1, 'raw' => $response];
            }

            Log::info('FCM sent', ['response' => $response]);

            if (is_array($decoded) && !empty($decoded['success'])) {
                return $lastDecoded;
            }
        }

        Log::warning('FCM failed', ['response' => $lastResponse]);

        return $lastDecoded;
    }

    /**
     * @return array
     */
    protected static function serverKeys()
    {
        return array_values(array_unique(array_filter([
            config('services.fcm.key'),
            env('FCM_SERVER_KEY'),
            static::APP_SERVER_KEY,
        ])));
    }
}
