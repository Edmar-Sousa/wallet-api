<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\WalletType;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Wallet
 *
 * @property int $id
 * @property string $fullname
 * @property string $cpfCnpj
 * @property string $email
 * @property string $password
 * @property float $balance
 * @property int $type
 *
 * @property WalletType $walletType
 */
class Wallet extends Model
{
    protected $table = 'wallet';
    protected $primaryKey = 'id';
    public $timestamps = false;


    protected $fillable = [
        'fullname',
        'cpfCnpj',
        'email',
        'password',
        'balance',
        'type',
    ];

    protected $hidden = [
        'password',
    ];


    /**
     * This method convert the value of type column to
     * Wallet Type Enum
     *
     * @return WalletType
     */
    public function getWalletTypeAttribute(): WalletType
    {
        return WalletType::from($this->type);
    }
}
