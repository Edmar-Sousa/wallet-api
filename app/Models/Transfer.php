<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $table = 'transfer';
    protected $primaryKey = 'id';
    public $timestamps = false;


    protected $fillable = [
        'payer',
        'payee',
        'value',
    ];


    public function walletPayee()
    {
        return $this->hasOne(Wallet::class, 'id', 'payee');
    }

    public function walletPayer()
    {
        return $this->hasOne(Wallet::class, 'id', 'payer');
    }

}
