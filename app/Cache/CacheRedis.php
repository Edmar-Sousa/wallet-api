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
     * @param array<string, mixed> $data
     * 
     * @return void
     */
    public function enqueueMessageToNotifier(string $channel, array $data): void
    {
        $this->redisClient->rpush($channel, $data);
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
     * @return array<string, mixed>|null
     */
    public function dequeueMessageToNotifier(string $channel): array|null
    {
       return $this->redisClient->blpop($channel, 2000);
    }
}
