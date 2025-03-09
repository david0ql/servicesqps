<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraByService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'extra_id',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function extra()
    {
        return $this->belongsTo(Extra::class);
    }
}