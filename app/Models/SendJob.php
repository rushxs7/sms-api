<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SendJob extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'bulk',
        'message',
        'scheduled_at',
        'sent_at',
        'error',
    ];

    public function messages()
    {
        return $this->hasMany(SMSMessage::class, 'job_id', 'id');
    }
}
