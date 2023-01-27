<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mockup extends Model
{
    use HasUlids;

    protected $primaryKey = 'uuid';

    public function layers(): HasMany
    {
        return $this->hasMany(MockupLayer::class);
    }
}
