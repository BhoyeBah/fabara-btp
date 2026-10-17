<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Throwable;

class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['key', 'value'];

    /** Paramètres modifiables dans l'admin => valeur par défaut. */
    public static function defaults(): array
    {
        $c = config('fabara.company');

        return [
            'slogan' => $c['slogan'],
            'promise' => $c['promise'],
            'hero_text' => "Peinture, carrelage, plafonds, façades et gros œuvre : des finitions dignes d'une villa haut de gamme, à un prix adapté à votre budget. Une équipe FABARA vient sur place, prend les mesures et vous remet un devis détaillé.",
            'phone_display' => $c['phone_display'],
            'whatsapp' => $c['whatsapp'],
            'email' => $c['email'],
            'address' => $c['address'],
            'hours' => $c['hours'],
            'announcement' => '',
        ];
    }

    public static function allValues(): array
    {
        try {
            $stored = Cache::rememberForever('fabara.settings', fn () => static::query()->pluck('value', 'key')->all());
        } catch (Throwable) {
            $stored = []; // base pas encore migrée
        }

        return array_merge(static::defaults(), array_filter($stored, fn ($v) => $v !== null));
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::allValues()[$key] ?? $default;
    }

    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget('fabara.settings');
    }
}
