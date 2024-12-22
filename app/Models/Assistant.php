<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assistant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'instructions',
        'type',
        'model',
        'openai_assistant_id'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
