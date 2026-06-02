<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharityType extends Model
{
    protected $fillable = ['name', 'description', 'image', 'start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function getStatusAttribute(): string
    {
        if ($this->end_date && now()->startOfDay()->gt($this->end_date->startOfDay())) {
            return 'closed';
        }
        return 'open';
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
