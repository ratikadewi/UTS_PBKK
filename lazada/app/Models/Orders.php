<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orders extends Model
{
    use HasUlids;
    
    protected $table = 'orders';
    
    protected $fillable = [
        'customer_id',
        'order_date',
        'jumlah_barang',
        'total_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'customer_id' => 'string',
        ];
    }

    public function customer():BelongsTo{
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function orderitems():HasMany{
        return $this->hasMany(OrderItems::class,'order_id');
    }
}
