<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}