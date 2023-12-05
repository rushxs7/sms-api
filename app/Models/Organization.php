<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'default_sender',
    ];

    public function users()
    {
        return $this->HasMany(User::class, 'organization_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'organization_id', 'id');
    }
}
