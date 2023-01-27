<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasUlids, HasFactory;

    protected $primaryKey = 'uuid';

    public function mockups(): HasMany
    {
        return $this->hasMany(Mockup::class);
    }

    public function credits(): HasMany
    {
        return $this->hasMany(TeamCredit::class);
    }

    public function mockupOutputs(): HasMany
    {
        return $this->hasMany(MockupOutput::class);
    }

    public function canGenerateMockup(): bool
    {
        return $this->availableCredits() > $this->usage();
    }

    public function availableCredits(): int
    {
        return $this->credits()->sum('limit');
    }

    public function usage(): int
    {
        return $this->mockupOutputs()->count();
    }
}
