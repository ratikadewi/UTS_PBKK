<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categori extends Model
{
    use HasUlids;
    
    protected $fillable =[
    'name',
    'description',
    ];

    protected $table = 'categories';

    protected function casts(): array
    {
        return [
            'name' => 'string',
        ];
    }

        public function categori():HasMany{
        return $this->hasMany(Categori::class, 'category_id');
    }
}