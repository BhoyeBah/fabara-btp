<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Throwable;

class Service extends Model
{
    /** Icônes disponibles (dessinées dans partials/icons.blade.php). */
    public const ICONS = [
        'roll' => 'Rouleau (peinture)',
        'tile' => 'Carreaux (carrelage)',
        'lamp' => 'Lampe (plafonds, éclairage)',
        'house' => 'Maison (façades)',
        'brick' => 'Briques (maçonnerie)',
        'road' => 'Route (travaux publics)',
        'check' => 'Coche (général)',
        'gear' => 'Engrenage (technique)',
        'people' => 'Personnes (accompagnement)',
        'pin' => 'Repère (terrain)',
    ];

    protected $fillable = ['title', 'icon', 'summary', 'items', 'image_path', 'image_alt', 'is_published', 'in_quote_form', 'sort'];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'is_published' => 'boolean',
            'in_quote_form' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Service $s) {
            if ($s->image_path && ! Realisation::isBundled($s->image_path)) {
                Storage::disk('public')->delete($s->image_path);
            }
        });
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Realisation::urlFor($this->image_path) : null;
    }

    /** Services visibles sur le site, dans l'ordre choisi par l'admin. */
    public static function published(): Collection
    {
        try {
            return static::where('is_published', true)->orderBy('sort')->orderBy('id')->get();
        } catch (Throwable) {
            return new Collection(); // base pas encore migrée
        }
    }

    /**
     * Choix « Travaux souhaités » du formulaire de devis :
     * les choix de base de config/fabara.php + les services cochés « dans le devis ».
     */
    public static function quoteChoices(): array
    {
        try {
            $fromServices = static::where('is_published', true)->where('in_quote_form', true)
                ->orderBy('sort')->pluck('title')->all();
        } catch (Throwable) {
            $fromServices = [];
        }

        return array_values(array_unique(array_merge(config('fabara.works'), $fromServices)));
    }
}
