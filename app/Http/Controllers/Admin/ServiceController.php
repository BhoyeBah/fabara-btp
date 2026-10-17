<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Realisation;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index()
    {
        return view('admin.services', [
            'services' => Service::orderBy('sort')->orderBy('id')->get(),
            'icons' => Service::ICONS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);

        $service = new Service($this->fields($request, $data));
        $service->sort = (int) Service::max('sort') + 1;
        if ($request->hasFile('image')) {
            $service->image_path = $request->file('image')->store('services', 'public');
        }
        $service->save();

        return back()->with('success', "Service « {$service->title} » ajouté au site.");
    }

    public function update(Request $request, Service $service)
    {
        $data = $this->validated($request, false);

        $service->fill($this->fields($request, $data));

        if ($request->hasFile('image') || $request->boolean('remove_image')) {
            if ($service->image_path && ! Realisation::isBundled($service->image_path)) {
                Storage::disk('public')->delete($service->image_path);
            }
            $service->image_path = $request->hasFile('image')
                ? $request->file('image')->store('services', 'public')
                : null;
        }
        $service->save();

        return back()->with('success', "Service « {$service->title} » mis à jour.");
    }

    /** Monter ou descendre un service dans l'ordre d'affichage. */
    public function move(Service $service, string $direction)
    {
        $all = Service::orderBy('sort')->orderBy('id')->get()->values();
        $i = $all->search(fn ($s) => $s->id === $service->id);
        $j = $direction === 'haut' ? $i - 1 : $i + 1;

        if ($i !== false && $j >= 0 && $j < $all->count()) {
            $items = $all->all();
            [$items[$i], $items[$j]] = [$items[$j], $items[$i]];
            foreach ($items as $pos => $s) {
                $s->sort = $pos;
                $s->saveQuietly();
            }
        }

        return back();
    }

    public function destroy(Service $service)
    {
        $title = $service->title;
        $service->delete();

        return back()->with('success', "Service « {$title} » supprimé.");
    }

    private function validated(Request $request, bool $creating): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:80'],
            'icon' => ['required', Rule::in(array_keys(Service::ICONS))],
            'summary' => ['required', 'string', 'max:300'],
            'items' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'image_alt' => ['nullable', 'string', 'max:190'],
            'is_published' => ['nullable', 'boolean'],
            'in_quote_form' => ['nullable', 'boolean'],
            'remove_image' => ['nullable', 'boolean'],
        ], [], ['summary' => 'description', 'items' => 'points forts', 'icon' => 'icône']);
    }

    private function fields(Request $request, array $data): array
    {
        // Un point fort par ligne, lignes vides ignorées, 6 maximum.
        $items = collect(preg_split('/\r\n|\r|\n/', (string) ($data['items'] ?? '')))
            ->map(fn ($l) => trim(ltrim(trim($l), '-•*')))
            ->filter()->take(6)->values()->all();

        return [
            'title' => $data['title'],
            'icon' => $data['icon'],
            'summary' => $data['summary'],
            'items' => $items,
            'image_alt' => $data['image_alt'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'in_quote_form' => $request->boolean('in_quote_form'),
        ];
    }
}
