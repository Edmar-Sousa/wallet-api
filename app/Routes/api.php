<?php

use App\Controllers\TransferController;
use App\Controllers\WalletController;

$app->post('/wallet/user', [WalletController::class, 'createWallet']);
$app->post('/wallet/merchant', [WalletController::class, 'createMerchantWallet']);
$app->post('/transfer', [TransferController::class, 'createTransfer']);
