<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Orders extends Model
{
    use HasUlids;
    
    protected $table = 'Orders';
    
    protected $fillable = [
        'customer_id',
        'order_date',
        'total_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'total_amount' => 'integer',
        ];
    }
    public function customer():BelongsTo{
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function order():HasMany{
        return $this->hasMany(Orders::class, 'order_id');
    }
}