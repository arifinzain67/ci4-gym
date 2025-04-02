<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'tb_payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'transaction_id',
        'order_id',
        'amount',
        'payment_type',
        'status',
        'payment_token',
        'payment_url'
    ];
}
