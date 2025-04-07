<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Transfer
 *
 * @property int $payer
 * @property int $payee
 * @property int $value
 *
 * @property-read Wallet $walletPayee
 * @property-read Wallet $walletPayer
 */
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


    /**
     * Return the wallet of payee relationship
     *
     * @return HasOne<Wallet, $this>
     */
    public function walletPayee(): HasOne
    {
        return $this->hasOne(Wallet::class, 'id', 'payee');
    }


    /**
     * Return the wallet of payer relationship
     *
     * @return HasOne<Wallet, $this>
     */
    public function walletPayer(): HasOne
    {
        return $this->hasOne(Wallet::class, 'id', 'payer');
    }
}
