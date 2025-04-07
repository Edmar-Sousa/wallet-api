<?php

/**
 * ---------------------------------------------------------------------
 *   Este arquivo, por simplicidade, simula uma fila utilizando Redis.
 *   Para garantir a notificação ao usuário, a classe NotifierWorker
 *   tenta no máximo 10 vezes, com um intervalo de 1 segundo entre
 *   cada requisição.
 *
 *   Esse comportamento será executado até atingir o número máximo
 *   de tentativas ou até receber uma confirmação da API.
 * ---------------------------------------------------------------------
 */



declare(strict_types=1);

include __DIR__ . '/../../vendor/autoload.php';

use App\Clients\ClientNotifier;
use App\Enums\CacheType;
use App\Interfaces\CacheInterface;
use Dotenv\Dotenv;
use App\Cache\CacheFactory;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();



class NotifierWorker
{
    private CacheInterface $cacheClient;
    private ClientNotifier $notifierClient;

    private int $maxRetries = 10;


    public function __construct()
    {
        $this->cacheClient = CacheFactory::create(CacheType::REDIS);
        $this->notifierClient = new ClientNotifier();
    }

    /**
     * Waiting and get events from queue and return
     *
     * @return array{'payee': int}|null
     */
    private function getWalletToNotifier(): array|null
    {
        return $this->cacheClient->dequeueMessageToNotifier('notifier_transfer');
    }


    /**
     * Summary of notifierWallet
     *
     * @param int $walletId
     *
     * @return void
     */
    private function notifierWallet(int $walletId): void
    {
        $tries = 0;

        while ($tries++ < $this->maxRetries) {
            echo '[WORKER] Try notifier payee: ' . $walletId . PHP_EOL;
            $response = $this->notifierClient->notifierPayeeUser();

            if ($response['statusCode'] == 204) {
                echo '[WORKER] Notifier with success' . PHP_EOL;
                break;
            }

            echo '[WORKER] Error to notifier user. Wating 1 second to retry notifier' . PHP_EOL;
            sleep(1);
        }

        if ($tries >= $this->maxRetries) {
            echo '[WORKER] Maximum number of attempts exceeded' . PHP_EOL;
        }
    }

    public function run(): never
    {
        /** @phpstan-ignore-next-line */
        while (true) {
            echo '[WORKER] Waiting by notifier events' . PHP_EOL;
            $notifierData = $this->getWalletToNotifier();

            if (is_array($notifierData)) {
                $this->notifierWallet($notifierData['payee']);
            }
        }
    }
}


$worker = new NotifierWorker();
$worker->run();
