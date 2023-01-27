<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class MockupLayer extends Model
{
    use HasUlids;

    protected $primaryKey = 'uuid';

    protected $guarded = [];
}
