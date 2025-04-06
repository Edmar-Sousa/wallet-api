<?php

declare(strict_types=1);

namespace App\Interfaces;


interface CacheInterface
{

    /**
     * This function will place an event in the notification to 
     * be executed
     * 
     * @param string $channel
     * @param array<string, mixed> $data
     * 
     * @return void
     */
    public function enqueueMessageToNotifier(string $channel, array $data): void;


    /**
     * This function will receive a notification event via a 
     * queue and send a request to the external API.
     * 
     * It will keep trying until it receives a 
     * successful response.
     * 
     * @param string $channel
     * 
     * @return array{'payee': int}|null
     */
    public function dequeueMessageToNotifier(string $channel): array|null;

}
