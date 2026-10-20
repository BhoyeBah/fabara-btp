<?php

/*
|--------------------------------------------------------------------------
| FABARA Bâti – Finition – TP
|--------------------------------------------------------------------------
| Valeurs par défaut du site. Les informations de contact et les textes
| principaux peuvent ensuite être modifiés depuis l'administration
| (menu « Paramètres »), sans toucher au code.
*/

return [

    'company' => [
        'name' => env('FABARA_NAME', 'FABARA Bâti – Finition – TP'),
        'short' => 'FABARA',
        'tagline' => 'Bâti – Finition – TP',
        'slogan' => 'Tout le monde a le droit de goûter au luxe',
        'promise' => 'Luxe à petit prix',
        'phone_display' => env('FABARA_PHONE', '+224 626 43 22 76'),
        'whatsapp' => env('FABARA_WHATSAPP', '224626432276'),
        'email' => env('FABARA_EMAIL', 'fabara224@gmail.com'),
        'address' => env('FABARA_ADDRESS', 'Conakry, République de Guinée'),
        'hours' => 'Du lundi au samedi, 8 h – 18 h',
    ],

    /*
    | Chemin secret de l'administration. Il n'apparaît nulle part sur le
    | site public : l'administrateur se connecte avec la page « Connexion »
    | normale et il est redirigé automatiquement ici. Pour tout autre
    | visiteur, cette adresse répond « Page introuvable » (404).
    */
    'admin_path' => env('ADMIN_PATH', 'gestion-fb'),

    // Adresse qui reçoit un e-mail à chaque nouvelle demande de devis.
    'admin_notify_email' => env('ADMIN_NOTIFY_EMAIL'),

    // Identifiants utilisés par le seeder principal pour créer l'administrateur.
    'admin_seed' => [
        'email' => env('FABARA_ADMIN_EMAIL'),
        'password' => env('FABARA_ADMIN_PASSWORD'),
    ],

    // Version de la politique de confidentialité acceptée à l'inscription.
    'privacy_version' => '2026-10',
    'privacy_updated_at' => '16 octobre 2026',

    // Durées de conservation des données (voir la politique de confidentialité).
    'retention' => [
        'guest_devis_months' => 24,      // demandes sans compte, une fois clôturées
        'deletion_requests_days' => 180, // demandes de suppression traitées
        'inactive_accounts_years' => 3,  // comptes clients sans aucune connexion
    ],

    'communes' => [
        'Kaloum', 'Dixinn', 'Matam', 'Ratoma', 'Matoto',
        'Dubréka', 'Coyah', 'Autre ville de Guinée',
    ],

    'property_types' => [
        'Villa / maison', 'Vieille maison à rénover', 'Appartement',
        'Bureau / commerce', 'Immeuble', 'Chantier neuf', 'Voirie / extérieur',
    ],

    'works' => [
        'Peinture', 'Carrelage', 'Plafonds et décoration', 'Façades',
        'Rénovation / modernisation', 'Bâtiment / gros œuvre', 'Travaux publics',
    ],

    'start_options' => [
        'Dès que possible', 'Dans le mois', 'Dans 1 à 3 mois', 'Je me renseigne',
    ],

    // Étapes de suivi d'une demande (clé => [libellé, couleur]).
    'statuses' => [
        'nouveau' => ['Nouvelle demande', 'info'],
        'visite' => ['Visite planifiée', 'warn'],
        'devis' => ['Devis envoyé', 'gold'],
        'accepte' => ['Devis accepté', 'ok'],
        'chantier' => ['Chantier en cours', 'ok'],
        'termine' => ['Terminé', 'muted'],
        'perdu' => ['Sans suite', 'bad'],
    ],

    'realisation_categories' => [
        'Peinture', 'Carrelage', 'Plafonds', 'Façades', 'Rénovation', 'Bâtiment', 'Travaux publics',
    ],

    /*
    | Exemples de transformation affichés tant que vous n'avez pas ajouté vos
    | propres photos avant / après dans l'admin (Réalisations › « Photo avant »).
    | Ils sont présentés comme des exemples, pas comme des chantiers FABARA.
    */
    'renovation_examples' => [
        [
            'title' => 'Maison ancienne transformée en villa moderne',
            'before' => 'images/renovation/avant-1.jpg',
            'after' => 'images/renovation/apres-1.jpg',
            'caption' => 'Murs repris, façade enduite et habillée de pierre, grandes baies vitrées, garde-corps en verre.',
        ],
        [
            'title' => 'Vieille maison de plain-pied remise au goût du jour',
            'before' => 'images/renovation/avant-2.jpg',
            'after' => 'images/renovation/apres-2.jpg',
            'caption' => 'Façade enduite en blanc, bardage, menuiseries alu, escalier carrelé et terrasse.',
        ],
    ],

    'services' => [
        [
            'title' => 'Peinture',
            'icon' => 'roll',
            'image' => 'images/peinture.jpg',
            'alt' => 'Peintre FABARA appliquant de la peinture blanche au rouleau sur un mur intérieur',
            'text' => 'Peinture intérieure et extérieure avec des finitions propres et durables.',
            'items' => ['Enduit et ponçage des murs', 'Peinture mate, satinée, laquée', 'Effets décoratifs'],
        ],
        [
            'title' => 'Carrelage',
            'icon' => 'tile',
            'image' => 'images/carrelage.jpg',
            'alt' => 'Carreleur FABARA posant de grands carreaux au sol',
            'text' => 'Pose de carrelage et revêtements modernes pour un intérieur élégant et résistant.',
            'items' => ['Sols et murs, grands formats', 'Salles de bain et cuisines', 'Plinthes et terrasses'],
        ],
        [
            'title' => 'Plafonds',
            'icon' => 'lamp',
            'image' => 'images/plafond.jpg',
            'alt' => 'Technicien FABARA vérifiant un faux plafond éclairé par des bandes LED',
            'text' => 'Plafonds en plâtre, éclairage et décoration intérieure pour un rendu moderne et raffiné.',
            'items' => ['Faux plafonds en plaques BA13', 'Corniches et caissons LED', 'Spots encastrés'],
        ],
        [
            'title' => 'Façades',
            'icon' => 'house',
            'image' => 'images/facade.jpg',
            'alt' => 'Ouvrier FABARA en casque peignant une façade de villa',
            'text' => 'Enduits, peintures et finitions de façades modernes et résistantes au climat.',
            'items' => ['Enduits et crépis', 'Habillage pierre', 'Peinture anti-humidité'],
        ],
    ],
];
