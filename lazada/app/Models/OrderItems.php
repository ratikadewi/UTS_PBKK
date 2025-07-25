<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItems extends Model
{
    use HasUlids;
    
    protected $table = 'order_items';
    
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];

    protected function casts(): array
    {
        return [
            'order_id' => 'string',
            'product_id' => 'string',
        ];
    }

    public function orders():BelongsTo{
        return $this->belongsTo(Orders::class);
    }

    public function product():BelongsTo{
        return $this->belongsTo(Product::class, 'product_id');
    }
}
