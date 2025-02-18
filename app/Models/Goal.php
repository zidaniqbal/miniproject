<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'target_date',
        'priority',
        'status',
        'progress'
    ];

    protected $casts = [
        'target_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}