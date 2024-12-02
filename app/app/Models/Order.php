<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'send_to_kitchen_time',
        'total_cost',
    ];

    protected $dates = ['deleted_at'];

    public function concessions()
    {
        return $this->belongsToMany(Concession::class, 'order_concession', 'order_id', 'concession_id');
    }
}
