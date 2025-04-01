<?php

use App\Controllers\WalletController;


$app->post('/wallet/user', [WalletController::class, 'createWallet']);
