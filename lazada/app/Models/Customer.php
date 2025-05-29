<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasUlids;
    
    protected $table = 'customer';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address'
    ];

    protected function casts(): array
    {
        return [
            'name' => 'string',
            'email' => 'string',
        ];
    }


    public function order():HasMany{
        return $this->hasMany(Orders::class,'customer_id');
    }
}