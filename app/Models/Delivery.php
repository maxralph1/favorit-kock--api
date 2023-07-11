<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'order_id',
        'user_id',
        'delivered',
        'time_delivered',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
