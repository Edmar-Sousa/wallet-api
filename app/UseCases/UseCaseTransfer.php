<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Clients\ClientAuthorization;
use App\Enums\WalletType;
use App\Exceptions\PicPayAuthorizationException;
use App\Exceptions\TransferException;
use App\Exceptions\WalletBalanceInsufficientException;
use App\Exceptions\WalletMerchantException;
use App\Exceptions\WalletNotFoundException;
use App\Models\Transfer;
use App\Models\Wallet;
use App\Repositories\Transfer\TransferRepository;
use App\Repositories\Wallet\WalletRepository;
use RuntimeException;
use Illuminate\Database\Capsule\Manager as Capsule;

class UseCaseTransfer
{
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


    public function cancelTransfer(int $transferId): void
    {
        $walletRepository = new WalletRepository();
        $transferRepository = new TransferRepository();


        $transfer = $transferRepository->getTransferWithId($transferId);

        $amountTransfer = $transfer->value;

        $walletPayer = $transfer->walletPayee;
        $walletPayee = $transfer->walletPayer;

        $this->checksWalletsExists($walletPayer, $walletPayee);
        $this->checkWalletHasBalanceToTransfer($walletPayer, $amountTransfer);

        try {
            Capsule::beginTransaction();

            $client = new ClientAuthorization();

            if (!$client->isAuthorized()) {
                throw new PicPayAuthorizationException(
                    'Transaction not authorized',
                    [ 'authorization' => 'A transferência não foi autorizada, tente novamente' ]
                );
            }

            $walletRepository->debtWallet($walletPayer, $amountTransfer);
            $walletRepository->creditWallet($walletPayee, $amountTransfer);

            $transferRepository->deleteTransferWithId($transferId);
            Capsule::commit();

        } catch (RuntimeException $e) {
            Capsule::rollBack();

            throw $e;
        }
    }

    private function checkWalletAllowedToTransfer(Wallet $walletPayer)
    {
        if ($walletPayer->type == WalletType::MERCHANT) {
            throw new WalletMerchantException(
                'Merchant user not allowed to transfer',
                [ 'payer_wallet' => 'Lojistas não podem fazer transferencias' ]
            );
        }
    }

    private function checkWalletHasBalanceToTransfer(Wallet $walletPayer, int $amount): void
    {
        if ($walletPayer->balance - $amount < 0) {
            throw new WalletBalanceInsufficientException(
                'Wallet not has balance to complete transfer',
                [ 'payer_wallet' => 'Saldo insuficiente para completar transferencia' ]
            );
        }
    }


    public function transferBetweenWallets(array $transferData): Transfer
    {
        $walletRepository = new WalletRepository();
        $amountTransfer = intval(floatval($transferData['value']) * 100);

        $walletPayer = $walletRepository->getWalletForUpdate($transferData['payer']);
        $walletPayee = $walletRepository->getWalletForUpdate($transferData['payee']);


        $this->checkWalletAllowedToTransfer($walletPayer);
        $this->checksWalletsExists($walletPayer, $walletPayee);
        $this->checkWalletHasBalanceToTransfer($walletPayer, $amountTransfer);

        try {
            Capsule::beginTransaction();

            $client = new ClientAuthorization();

            if (!$client->isAuthorized()) {
                throw new PicPayAuthorizationException(
                    'Transaction not authorized',
                    [ 'authorization' => 'A transferência não foi autorizada, tente novamente' ]
                );
            }

            $transferRepository = new TransferRepository();
            $transfer = $transferRepository->createTransfer([
                'payer' => $walletPayer,
                'payee' => $walletPayee,
                'value' => $amountTransfer,
            ]);

            $walletRepository->debtWallet($walletPayer, $amountTransfer);
            $walletRepository->creditWallet($walletPayee, $amountTransfer);

            $transfer->value = floatval($transfer->value / 100);

            Capsule::commit();
            return $transfer;
        } catch (RuntimeException $e) {
            Capsule::rollBack();

            throw $e;
        }

    }

}
