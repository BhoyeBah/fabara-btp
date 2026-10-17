<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = ['devis_request_id', 'user_id', 'from_admin', 'body', 'read_at'];

    protected function casts(): array
    {
        return [
            'from_admin' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    public function devisRequest(): BelongsTo
    {
        return $this->belongsTo(DevisRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
