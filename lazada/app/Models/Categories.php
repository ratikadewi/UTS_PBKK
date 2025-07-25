<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Categories extends Model
{
 use HasUlids;
    
    protected $table = 'categories';
    
    protected $fillable = [
        'name',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'string',
        ];
    }

    public function product():HasMany{
        return $this->hasMany(Categories::class,'product_id');
    }



}
