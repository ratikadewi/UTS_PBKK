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

    protected $table = 'order_item';

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function orders():BelongsTo
    {
        return $this->belongsTo(Orders::class,'order_id');
    }
}
