<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SMSMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_id',
        'recipient',
        'message',
        'status',
        'error',
    ];

    public function jobs()
    {
        return $this->belongsTo(SendJob::class, 'job_id', 'id');
    }
}
