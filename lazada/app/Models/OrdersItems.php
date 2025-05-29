<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class OrdersItems extends Model
{
    use HasUlids;
    
    protected $fillable =[
    'product_id',
    'order_id',
    'quantity',
    'price',
    ];

    protected $table = '_order_items';

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    public function order():BelongsTo{
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function product():BelongsTo{
        return $this->belongsTo(Product::class, 'product_id');
    }
}