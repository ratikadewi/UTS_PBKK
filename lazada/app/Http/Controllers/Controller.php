<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

abstract class Controller
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
            'name' => 'string',
            'password' => 'string',
            'email' => 'string',
            'no_hp' => 'string',
            'customer_name' => 'string',
            'no_hp' => 'string',
        ];
    }

}
