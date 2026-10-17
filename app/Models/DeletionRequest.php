<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletionRequest extends Model
{
    protected $fillable = ['name', 'contact', 'message', 'status', 'processed_at'];

    protected function casts(): array
    {
        return ['processed_at' => 'datetime'];
    }
}
