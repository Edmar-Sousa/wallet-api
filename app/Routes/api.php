<?php

use App\Controllers\TransferController;
use App\Controllers\WalletController;

global $app;

$app->get('/wallet/{id}', [WalletController::class, 'findWallet']);
$app->post('/wallet/user', [WalletController::class, 'createWallet']);
$app->post('/wallet/merchant', [WalletController::class, 'createMerchantWallet']);

$app->delete('/transfers/{id}/cancellation', [TransferController::class, 'cancelTransfer']);
$app->post('/transfer', [TransferController::class, 'createTransfer']);
