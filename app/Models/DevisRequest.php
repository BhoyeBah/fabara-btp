<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DevisRequest extends Model
{
    protected $fillable = [
        'user_id', 'name', 'phone', 'email', 'commune', 'property_type', 'works',
        'surface', 'start_when', 'message', 'consent_at',
    ];

    protected function casts(): array
    {
        return [
            'works' => 'array',
            'surface' => 'decimal:2',
            'consent_at' => 'datetime',
            'closed_at' => 'datetime',
            'quoted_amount' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (DevisRequest $devis) {
            if (! $devis->reference) {
                do {
                    $ref = 'FB-'.now()->format('ym').'-'.strtoupper(Str::random(4));
                } while (static::where('reference', $ref)->exists());
                $devis->reference = $ref;
            }
        });

        // Les photos sont des fichiers : on les efface du disque avec la demande.
        static::deleting(function (DevisRequest $devis) {
            foreach ($devis->photos as $photo) {
                Storage::disk('local')->delete($photo->path);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(DevisPhoto::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function statusLabel(): string
    {
        return config('fabara.statuses.'.$this->status.'.0', $this->status);
    }

    public function statusColor(): string
    {
        return config('fabara.statuses.'.$this->status.'.1', 'muted');
    }

    public function isClosed(): bool
    {
        return in_array($this->status, ['termine', 'perdu'], true);
    }

    public function worksList(): string
    {
        return implode(', ', $this->works ?? []) ?: 'À préciser';
    }

    /** Lien WhatsApp vers le client (côté admin). */
    public function clientWhatsappUrl(?string $text = null): string
    {
        $digits = preg_replace('/\D/', '', $this->phone);
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }
        if (strlen($digits) === 9) {
            $digits = '224'.$digits;
        }

        return 'https://wa.me/'.$digits.($text ? '?text='.rawurlencode($text) : '');
    }

    /** Message prêt à envoyer à FABARA sur WhatsApp. */
    public function whatsappSummary(): string
    {
        $lines = [
            'Bonjour FABARA, je souhaite un devis.',
            'Référence : '.$this->reference,
            'Nom : '.$this->name,
            'Téléphone : '.$this->phone,
            'Lieu : '.$this->commune,
            'Bien : '.$this->property_type,
            'Travaux : '.$this->worksList(),
        ];
        if ($this->surface) {
            $lines[] = 'Surface : '.rtrim(rtrim((string) $this->surface, '0'), '.').' m²';
        }
        if ($this->start_when) {
            $lines[] = 'Démarrage : '.$this->start_when;
        }
        if ($this->message) {
            $lines[] = 'Projet : '.$this->message;
        }

        return implode("\n", $lines);
    }
}
