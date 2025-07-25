<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Customers extends Model
{
    use HasUlids;
    
    protected $table = 'customers';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
    ];
    

    protected function casts(): array
    {
        return [
        'name'=> 'string',
        'password' => 'hashed',
        'phone' => 'string',
        'address' => 'string',
        ];
    }

    public function orders():HasMany{
        return $this->hasMany(Orders::class,'customer_id');
    }
}
