<?php

namespace Database\Seeders;

use App\Models\Realisation;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Remplit la galerie avec les photos de présentation fournies avec le site.
     * Le compte administrateur se crée à part : php artisan fabara:create-admin
     */
    public function run(): void
    {
        // Services de départ (modifiables ensuite dans l'admin › Services).
        $services = config('fabara.services');
        $services[] = ['title' => 'Bâtiment et rénovation', 'icon' => 'brick', 'image' => null, 'alt' => null,
            'text' => 'Maçonnerie, extensions, reprise de fissures, étanchéité de toiture-terrasse et remise à neuf complète de maisons et bureaux.', 'items' => []];
        $services[] = ['title' => 'Travaux publics', 'icon' => 'road', 'image' => null, 'alt' => null,
            'text' => 'Voirie et réseaux divers, pavage de cours et d\'allées, caniveaux et clôtures pour particuliers, entreprises et projets immobiliers.', 'items' => []];
        foreach ($services as $i => $s) {
            Service::firstOrCreate(['title' => $s['title']], [
                'icon' => $s['icon'], 'summary' => $s['text'], 'items' => $s['items'],
                'image_path' => $s['image'], 'image_alt' => $s['alt'],
                'is_published' => true, 'in_quote_form' => false, 'sort' => $i,
            ]);
        }

        $items = [
            ['Villa contemporaine', 'Bâtiment', 'Façades enduites, habillage pierre, garde-corps en verre et éclairage extérieur.', 'images/villa.jpg'],
            ['Peinture intérieure', 'Peinture', 'Enduit, ponçage et deux couches de finition satinée.', 'images/peinture.jpg'],
            ['Carrelage grand format', 'Carrelage', 'Pose de carreaux 60×60 effet marbre sur chape.', 'images/carrelage.jpg'],
            ['Plafond à caissons LED', 'Plafonds', 'Faux plafond en plâtre, corniches et spots encastrés.', 'images/plafond.jpg'],
            ['Finition de façade', 'Façades', 'Enduit de façade et peinture extérieure anti-humidité.', 'images/facade.jpg'],
        ];

        foreach ($items as $i => [$title, $cat, $desc, $img]) {
            Realisation::firstOrCreate(
                ['image_path' => $img],
                ['title' => $title, 'category' => $cat, 'description' => $desc, 'location' => 'Conakry', 'is_published' => true, 'sort' => $i]
            );
        }
    }
}
