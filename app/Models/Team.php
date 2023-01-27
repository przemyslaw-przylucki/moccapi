<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasUlids;

    protected $primaryKey = 'uuid';

    public function mockups(): HasMany
    {
        return $this->hasMany(Mockup::class);
    }
}
