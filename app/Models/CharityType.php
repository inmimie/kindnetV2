<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharityType extends Model
{
    protected $fillable = ['name', 'description'];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
