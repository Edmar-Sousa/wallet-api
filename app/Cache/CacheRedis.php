<?php

declare(strict_types=1);

namespace App\Cache;

use App\Interfaces\CacheInterface;
use Predis\Client;

class CacheRedis implements CacheInterface
{
    private Client $redisClient;


    public function __construct()
    {
        $this->redisClient = new Client([
            'scheme'   => 'tcp',
            'host'     => $_ENV['REDIS_HOST'],
            'port'     => $_ENV['REDIS_PORT'],
            'password' => $_ENV['REDIS_PASS'] ?? '',
            'database' => 0,
        ]);
    }

    /**
     * This function will place an event in the notification to
     * be executed
     *
     * @param string $channel
     * @param array{'payer': int} $data
     *
     * @return void
     */
    public function enqueueMessageToNotifier(string $channel, array $data): void
    {
        $this->redisClient->rpush($channel, [json_encode($data)]);
    }


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
    public function dequeueMessageToNotifier(string $channel): array|null
    {
        /** @var array<string> */
        $eventData = $this->redisClient->blpop($channel, 10000);

        if ($eventData) {
            $eventData = json_decode($eventData[1], true);
        }

        /** @var array{'payee': int} | null */
        return $eventData;
    }
}
