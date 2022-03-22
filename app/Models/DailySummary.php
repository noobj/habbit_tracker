<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySummary extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'date',
        'project_id',
        'duration'
    ];

    public function project()
    {
        return $this->belongsTo('App\Models\Project','project_id');
    }
}
