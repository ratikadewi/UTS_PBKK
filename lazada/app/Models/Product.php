<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Diskon;  // dan ini


class Product extends Model
{
    
    use HasUlids;
    
    protected $fillable =[
        'name',
        'categories_id',
        'description',
        'price',
        'quantity_product'
    ];

    protected $table = 'product';

    protected function casts(): array
    {
        return [
            'name' => 'string',
            'categories_id' => 'string',
        ];
    }

    
    public function categories():BelongsTo{
        return $this->belongsTo(Categories::class, 'categories_id');
    }

    public function orderitems():HasMany{
        return $this->hasMany(OrderItems::class,'product_id');
    }

    public function diskon(): HasOne
    {
        return $this->hasOne(Diskon::class, 'product_id', 'id')->where('is_active', true)
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now());
    }
}
