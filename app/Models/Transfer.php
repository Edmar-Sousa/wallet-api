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

}
