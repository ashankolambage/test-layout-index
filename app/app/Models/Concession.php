<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
    ];

    protected $dates = ['deleted_at'];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_concession', 'concession_id', 'order_id');
    }
}
