<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Realisation extends Model
{
    protected $fillable = ['title', 'category', 'location', 'description', 'image_path', 'before_image_path', 'is_published', 'is_featured', 'sort'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'is_featured' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::deleting(function (Realisation $r) {
            foreach ([$r->image_path, $r->before_image_path] as $path) {
                if ($path && ! static::isBundled($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        });
    }

    /** Les photos livrées avec le site sont dans public/images, les autres sur le disque « public ». */
    public static function isBundled(string $path): bool
    {
        return str_starts_with($path, 'images/');
    }

    public static function urlFor(string $path): string
    {
        return static::isBundled($path) ? asset($path) : Storage::disk('public')->url($path);
    }

    public function isUploaded(): bool
    {
        return ! static::isBundled($this->image_path);
    }

    public function imageUrl(): string
    {
        return static::urlFor($this->image_path);
    }

    public function hasBefore(): bool
    {
        return (bool) $this->before_image_path;
    }

    public function beforeImageUrl(): ?string
    {
        return $this->before_image_path ? static::urlFor($this->before_image_path) : null;
    }

    /**
     * Paires avant / après à afficher sur le site : d'abord vos vrais chantiers
     * (réalisations avec photo « avant »), sinon les exemples fournis avec le site.
     *
     * @return array<int, array{title:string, before:string, after:string, caption:?string, example:bool}>
     */
    public static function beforeAfterPairs(int $limit = 6): array
    {
        $real = static::where('is_published', true)->whereNotNull('before_image_path')
            ->orderByDesc('is_featured')->orderBy('sort')->latest()->limit($limit)->get()
            ->map(fn (Realisation $r) => [
                'title' => $r->title,
                'before' => $r->beforeImageUrl(),
                'after' => $r->imageUrl(),
                'caption' => collect([$r->location, $r->description])->filter()->implode(' · ') ?: null,
                'example' => false,
            ])->all();

        if ($real) {
            return $real;
        }

        return collect(config('fabara.renovation_examples'))->take($limit)->map(fn ($e) => [
            'title' => $e['title'],
            'before' => asset($e['before']),
            'after' => asset($e['after']),
            'caption' => $e['caption'],
            'example' => true,
        ])->all();
    }
}
