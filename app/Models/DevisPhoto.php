<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevisPhoto extends Model
{
    protected $fillable = ['devis_request_id', 'path', 'original_name', 'size'];

    public function devisRequest(): BelongsTo
    {
        return $this->belongsTo(DevisRequest::class);
    }
}
