<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Clients\ClientAuthorization;
use App\Enums\WalletType;
use App\Exceptions\PicPayAuthorizationException;
use App\Exceptions\WalletBalanceInsufficientException;
use App\Exceptions\WalletMerchantException;
use App\Exceptions\WalletNotFoundException;
use App\Interfaces\TransferRepositoryInterface;
use App\Interfaces\UseCaseTransferInterface;
use App\Interfaces\WalletRepositoryInterface;
use App\Models\Transfer;
use App\Models\Wallet;
use RuntimeException;
use Illuminate\Database\Capsule\Manager as Capsule;

class UseCaseTransfer implements UseCaseTransferInterface
{
    private WalletRepositoryInterface $walletRepository;
    private TransferRepositoryInterface $transferRepository;


    public function __construct(WalletRepositoryInterface $walletRepository, TransferRepositoryInterface $transferRepository)
    {
        $this->walletRepository = $walletRepository;
        $this->transferRepository = $transferRepository;
    }


    /**
     * This method check if the wallets exists to 
     * continue transfer
     * 
     * @param \App\Models\Wallet|null $payer
     * @param \App\Models\Wallet|null $payee
     * 
     * @throws \App\Exceptions\WalletNotFoundException
     * 
     * @return void
     */
    private function checksWalletsExists(Wallet|null $payer, Wallet|null $payee): void
    {
        if (is_null($payer)) {
            throw new WalletNotFoundException(
                'Wallet payer not found',
                ['payer_wallet' => 'Carteira do usuario pagador não encontrada']
            );
        }


        if (is_null($payee)) {
            throw new WalletNotFoundException(
                'Wallet payee not found',
                ['payee_wallet' => 'Carteira do usuario beneficiario não encontrada']
            );
        }
    }


    /**
     * Business rules to cancel a transfer between two wallets
     * 
     * @param int $transferId
     * @return void
     */
    public function cancelTransfer(int $transferId): void
    {
        $transfer = $this->transferRepository->getTransferWithId($transferId);
        $amountTransfer = intval($transfer->value);

        $walletPayer = $transfer->walletPayee;
        $walletPayee = $transfer->walletPayer;

        $this->checksWalletsExists($walletPayer, $walletPayee);
        $this->checkWalletHasBalanceToTransfer($walletPayer, $amountTransfer);

        try {
            /** @phpstan-ignore-next-line */
            Capsule::beginTransaction();

            $client = new ClientAuthorization();

            if (!$client->isAuthorized()) {
                throw new PicPayAuthorizationException(
                    'Transaction not authorized',
                    [ 'authorization' => 'A transferência não foi autorizada, tente novamente' ]
                );
            }

            $this->walletRepository->debtWallet($walletPayer, $amountTransfer);
            $this->walletRepository->creditWallet($walletPayee, $amountTransfer);

            $this->transferRepository->deleteTransferWithId($transferId);

            /** @phpstan-ignore-next-line */
            Capsule::commit();
        } catch (RuntimeException $e) {
            /** @phpstan-ignore-next-line */
            Capsule::rollBack();

            throw $e;
        }
    }

    /**
     * This function check if wallet is alowed to transfer 
     * balance
     * 
     * @param \App\Models\Wallet $walletPayer
     * @throws \App\Exceptions\WalletMerchantException
     * 
     * @return void
     */
    private function checkWalletAllowedToTransfer(Wallet $walletPayer)
    {
        if ($walletPayer->walletType == WalletType::MERCHANT) {
            throw new WalletMerchantException(
                'Merchant user not allowed to transfer',
                [ 'payer_wallet' => 'Lojistas não podem fazer transferencias' ]
            );
        }
    }


    /**
     * This function check if the wallet has balance to complete 
     * the transfer
     * 
     * @param \App\Models\Wallet $walletPayer
     * @param int $amount
     * 
     * @throws \App\Exceptions\WalletBalanceInsufficientException
     * @return void
     */
    private function checkWalletHasBalanceToTransfer(Wallet $walletPayer, int $amount): void
    {
        if ($walletPayer->balance - $amount < 0) {
            throw new WalletBalanceInsufficientException(
                'Wallet not has balance to complete transfer',
                [ 'payer_wallet' => 'Saldo insuficiente para completar transferencia' ]
            );
        }
    }


    /**
     * Business rules to create a transfer between two wallets
     * 
     * @param array{'payer': int, 'payee':int, 'value':float} $transferData
     * @return array{'payer':int, 'payee': int, 'value': float}
     */
    public function transferBetweenWallets(array $transferData): array
    {
        $amountTransfer = intval(floatval($transferData['value']) * 100);

        $walletPayer = $this->walletRepository->getWalletForUpdate(intval($transferData['payer']));
        $walletPayee = $this->walletRepository->getWalletForUpdate(intval($transferData['payee']));


        $this->checksWalletsExists($walletPayer, $walletPayee);
        
        /** 
         * In this point the method checksWalletsExists already validate the
         * wallet payer and wallet payee exists.
         * 
         * @var \App\Models\Wallet $walletPayer
         * @var \App\Models\Wallet $walletPayee
         */
        $this->checkWalletAllowedToTransfer($walletPayer);
        $this->checkWalletHasBalanceToTransfer($walletPayer, $amountTransfer);

        try {
            /** @phpstan-ignore-next-line */
            Capsule::beginTransaction();

            $client = new ClientAuthorization();

            if (!$client->isAuthorized()) {
                throw new PicPayAuthorizationException(
                    'Transaction not authorized',
                    [ 'authorization' => 'A transferência não foi autorizada, tente novamente' ]
                );
            }

            $transfer = $this->transferRepository->createTransfer([
                'payer' => $walletPayer,
                'payee' => $walletPayee,
                'value' => $amountTransfer,
            ]);

            $this->walletRepository->debtWallet($walletPayer, $amountTransfer);
            $this->walletRepository->creditWallet($walletPayee, $amountTransfer);

            /** @phpstan-ignore-next-line */
            Capsule::commit();
            return [
                'payer' => $walletPayer->id,
                'payee' => $walletPayee->id,
                'value' => floatval($transfer->value / 100),
            ];
        } catch (RuntimeException $e) {
            /** @phpstan-ignore-next-line */
            Capsule::rollBack();

            throw $e;
        }

    }

}
