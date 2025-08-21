<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'date',
        'bank',
        'mount',
        'voucher',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
    ];
}
