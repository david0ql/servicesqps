<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'cleaning_type',
        'price',
        'community_id',
        'commission'
    ];

    protected $guarded = [];

    public $timestamps = false;

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}
