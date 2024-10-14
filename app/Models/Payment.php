<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id' , 'amount' , 'order_id' , 'status' , 'paypal_token'];

    public function order(){
        return $this->belongsTo(Order::class , 'order_id');
    }

}
