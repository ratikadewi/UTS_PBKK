<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasUlids;
    
    protected $table = 'stock';
    
    protected $fillable = [
        'product_id',
        'limit',
    ];

    protected function casts(): array
    {
        return [
            'limit' => 'integer',
        ];
    }

    public function product():BelongsTo{
        return $this->belongsTo(Product::class, 'product_id');
    }
}
