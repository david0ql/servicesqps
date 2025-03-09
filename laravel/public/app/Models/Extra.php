<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function services()
    {
        return $this->belongsToMany(Service::class, 'extras_by_service');
    }
}