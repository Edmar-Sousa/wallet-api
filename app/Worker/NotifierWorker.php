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


    private function getWalletToNotifier(): array
    {
        return $this->cacheClient->dequeueMessageToNotifier('notifier_transfer');
    }


    private function notifierWallet(string $walletId)
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

        if ($tries >= $this->maxRetries)
            echo '[WORKER] Maximum number of attempts exceeded' . PHP_EOL;
    }

    public function run(): never
    {
        while (true) {
            echo '[WORKER] Waiting by notifier events' . PHP_EOL;
            $notifierData = $this->getWalletToNotifier();

            $this->notifierWallet($notifierData[1]);
        }
    }
}


$worker = new NotifierWorker();
$worker->run();
