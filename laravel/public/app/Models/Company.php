<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    protected $table = 'companies';

    public function communities()
    {
        return $this->hasMany(Community::class);
    }



}
