# FABARA Bâti – Finition – TP

Site web Laravel de FABARA (Conakry, Guinée) : site vitrine, demandes de devis, espace client et administration.

**Slogan :** Tout le monde a le droit de goûter au luxe — *Luxe à petit prix*
**Contact :** +224 626 43 22 76 (WhatsApp)

---

## Ce que contient le projet

| Partie | Adresse | Qui y a accès |
|---|---|---|
| Site public | `/` | Tout le monde |
| Page Rénovation (avant / après) | `/renovation-maison` | Tout le monde (lien à partager sur WhatsApp et Facebook) |
| Connexion / Inscription | `/connexion`, `/inscription` | Tout le monde |
| Espace client | `/mon-espace` | Clients connectés |
| Administration | `/gestion-fb` (modifiable) | Administrateur uniquement |
| Politique de confidentialité | `/politique-de-confidentialite` | Tout le monde |
| Conditions d'utilisation | `/conditions-utilisation` | Tout le monde |
| Suppression de compte | `/suppression-compte` | Tout le monde (adresse à donner à Google Play) |

### Une seule page de connexion, une administration invisible

- Le site public ne montre que **Connexion** et **Inscription**. Aucun lien, texte ou bouton ne parle d'administration.
- L'administrateur se connecte sur la même page `/connexion` que les clients. Le site le reconnaît et l'envoie directement dans l'administration.
- Pour un client ou un visiteur, l'adresse de l'administration répond **« Page introuvable » (404)** : il ne peut même pas savoir qu'elle existe.
- Personne ne peut devenir administrateur depuis le site : l'inscription crée **toujours** un compte client. Le compte admin se crée uniquement en ligne de commande (voir plus bas).

### Site public
Accueil avec le slogan, promesse « Luxe à petit prix », section **« Modernisez votre vieille maison »** avec comparateur **avant / après** à faire glisser, page dédiée **Rénovation**, services, méthode, galerie de réalisations (gérée depuis l'admin), calculateur de quantités, formulaire de devis avec photos, FAQ, boutons WhatsApp.

### Espace client

**Pour demander un devis, le client doit créer un compte** (ou se connecter). Les visiteurs voient à la place du formulaire une invitation « Créez votre compte pour demander un devis » ; après l'inscription, ils arrivent directement sur le formulaire (pré-rempli « rénovation » s'ils viennent de la page Rénovation). WhatsApp reste disponible pour les simples questions.

- Suivre ses demandes de devis et leur statut (nouvelle, visite planifiée, devis envoyé, accepté, chantier en cours, terminé).
- Faire une nouvelle demande avec jusqu'à 5 photos.
- Échanger des messages avec FABARA sur chaque demande.
- Modifier ses informations et son mot de passe.
- **Télécharger toutes ses données** (fichier JSON).
- **Supprimer son compte** et toutes ses données.

### Administration
- Tableau de bord : nouvelles demandes, messages non lus, chiffre des devis acceptés.
- Demandes : recherche, filtre par statut, changement de statut, montant du devis, notes internes (jamais visibles par le client), réponses au client, bouton WhatsApp vers le client, photos.
- Services : créer, modifier, masquer, réordonner (↑ ↓) et supprimer les services de l'accueil (nom, icône, description, points forts, photo). Un nouveau service peut aussi être proposé dans le formulaire de devis.
- Réalisations : ajouter, modifier, masquer ou supprimer les photos de la galerie. Avec une **photo « avant »**, la réalisation devient un comparateur avant / après sur l'accueil et la page Rénovation (elle remplace alors les exemples fournis).
- Clients : liste, fiche, suppression complète d'un compte.
- Suppressions : demandes reçues par la page publique « Suppression de compte ».
- Paramètres : slogan, promesse, texte d'accueil, téléphone, WhatsApp, e-mail, adresse, horaires, bandeau d'annonce, mot de passe admin.

---

## Installation sur votre ordinateur

Prérequis : **PHP 8.3 ou plus** et **Composer** (https://getcomposer.org). Aucune installation de Node.js n'est nécessaire.

```bash
cd fabara-btp
composer run setup        # installe, crée la base SQLite, ajoute la galerie de départ
php artisan fabara:create-admin   # crée VOTRE compte administrateur
php artisan serve
```

Ouvrez http://localhost:8000, cliquez sur **Connexion** et entrez l'e-mail et le mot de passe admin que vous venez de choisir.

Pour créer le compte fourni par le seeder, configurez `FABARA_ADMIN_EMAIL` et `FABARA_ADMIN_PASSWORD` dans `.env`, puis lancez `php artisan db:seed --class=UserSeeder`. Ces identifiants ne sont pas inclus dans le code ni dans le seeder par défaut.

Lancer les tests automatiques : `php artisan test` (17 tests : inscription, admin caché, confidentialité des photos, suppression de compte…).

---

## Mise en ligne (hébergement)

Fonctionne sur un hébergement mutualisé avec cPanel (PHP 8.3 + MySQL) ou sur un serveur VPS.

1. **Envoyez les fichiers** sur le serveur. Le dossier public du domaine doit pointer vers `fabara-btp/public` (et pas vers la racine du projet).
2. **Créez une base MySQL** dans cPanel, puis copiez `.env.example` en `.env` et réglez :
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://votre-domaine.com

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_DATABASE=nom_de_la_base
   DB_USERNAME=utilisateur
   DB_PASSWORD=mot_de_passe

   ADMIN_PATH=un-chemin-secret-a-vous   # changez « gestion-fb »
   ADMIN_NOTIFY_EMAIL=fabara224@gmail.com   # alerte à chaque nouvelle demande
   ```
3. **Dans le terminal du serveur :**
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --force --seed
   php artisan storage:link
   php artisan fabara:create-admin
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
4. **Activez HTTPS** (certificat SSL gratuit Let's Encrypt dans cPanel). C'est obligatoire pour la sécurité des données et pour Google Play.
5. **Ajoutez la tâche planifiée (cron)** dans cPanel, toutes les minutes :
   ```
   * * * * * cd /chemin/vers/fabara-btp && php artisan schedule:run >> /dev/null 2>&1
   ```
   Elle supprime automatiquement chaque nuit les données arrivées au bout de leur durée de conservation (voir plus bas).
6. **E-mails** (mot de passe oublié, alertes) : remplissez les lignes `MAIL_*` du `.env` avec les paramètres SMTP de votre hébergeur. Tant que `MAIL_MAILER=log`, les e-mails sont seulement écrits dans `storage/logs`.
7. **Photos** : vérifiez dans cPanel › « Sélectionner une version de PHP » › Options que `upload_max_filesize` vaut au moins `8M` et `post_max_size` au moins `30M`.

## Déploiement avec Coolify sur Hetzner

Le `Dockerfile` à la racine construit l'application Laravel avec Apache et PHP 8.3. Dans Coolify, connectez le dépôt Git, choisissez **Dockerfile** comme méthode de build et indiquez le port du conteneur **80**. N'ajoutez pas de fichier `.env` à l'image : configurez les variables suivantes dans l'environnement de l'application Coolify :

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
APP_KEY=base64:...                  # Générée une seule fois avec php artisan key:generate --show

DB_CONNECTION=mysql
DB_HOST=...                         # Nom d'hôte interne du service MySQL/MariaDB Coolify
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

SESSION_DRIVER=database
SESSION_ENCRYPT=true
```

Créez une base MySQL ou MariaDB dans Coolify et utilisez son nom d'hôte interne pour `DB_HOST`. Ajoutez deux stockages persistants dans Coolify pour conserver les photos et documents lors des redéploiements :

| Chemin dans le conteneur | Contenu |
|---|---|
| `/var/www/html/storage/app/private` | Photos privées des demandes de devis |
| `/var/www/html/storage/app/public` | Photos publiques des réalisations et services |

Configurez la commande de pré-déploiement Coolify `php artisan migrate --force` pour appliquer les migrations à la base configurée. Après le premier déploiement, créez le compte administrateur avec `php artisan fabara:create-admin` depuis le terminal du conteneur. Configurez également les variables `MAIL_*` pour activer l'envoi des e-mails.

---

## Protection des données clients (Google Play, App Store, loi guinéenne)

Le site est construit pour qu'une future application Android ou iPhone utilisant les mêmes comptes ne soit pas bloquée pour des raisons de données personnelles.

| Exigence | Où c'est fait dans le projet |
|---|---|
| Politique de confidentialité publique, en français, accessible sans compte | `/politique-de-confidentialite` (liens dans le pied de page, l'inscription et le formulaire de devis) |
| Consentement explicite à l'inscription, avec preuve (date + version) | Case obligatoire à l'inscription. Champs `terms_accepted_at` et `privacy_version` |
| Demande de devis réservée aux clients inscrits | Le consentement est donné une fois à l'inscription ; chaque demande garde sa date (`consent_at`) |
| Offres commerciales seulement avec accord séparé et facultatif | Case `marketing` non cochée par défaut, modifiable dans « Mon compte » |
| **Suppression du compte depuis l'application** (obligatoire Google Play) | « Mon compte » › « Supprimer mon compte » : efface le compte, les demandes, les photos, les messages et les sessions |
| **Lien web de suppression de compte** (à déclarer dans Play Console) | `/suppression-compte` : explique la démarche et propose un formulaire. Les demandes arrivent dans l'admin › Suppressions |
| Droit d'accès et de portabilité | « Mon compte » › « Télécharger mes données » (JSON) |
| Droit de rectification | « Mon compte » › « Mes informations » |
| Durées de conservation annoncées et appliquées | `config/fabara.php` › `retention` + commande `fabara:purge-data` lancée chaque nuit |
| Données chiffrées en transit | HTTPS forcé en production, en-tête HSTS |
| Mots de passe protégés | Hachage bcrypt, 8 caractères minimum avec lettres et chiffres |
| Photos privées | Stockées hors du dossier public. Visibles seulement par leur auteur et l'admin (`PhotoController`) |
| Sessions chiffrées, pas de cache des pages privées | `SESSION_ENCRYPT=true`, en-tête `Cache-Control: no-store` |
| Protection contre les abus | Limite de tentatives de connexion, limite d'envois des formulaires, protection CSRF |
| Aucun pistage publicitaire | Pas de Google Analytics, pas de pixel Facebook, pas de Google Fonts (polices Bunny Fonts, sans pistage) |
| Cookies | Uniquement les cookies indispensables (session, sécurité, « rester connecté ») |
| Réservé aux adultes | Indiqué dans les conditions et la politique |
| Administration non indexée | `noindex, nofollow` sur toutes les pages admin |

### Durées de conservation (modifiables dans `config/fabara.php`)
- Compte client : jusqu'à sa suppression, ou **3 ans sans connexion**.
- Demande de suppression traitée : **180 jours**, pour prouver qu'elle a été traitée.
- Journaux techniques : **14 jours**.

### Réponses pour le formulaire « Sécurité des données » de Google Play

À remplir le jour où vous publiez l'application (si elle utilise ce site et ces comptes) :

| Question | Réponse |
|---|---|
| L'application collecte-t-elle des données ? | Oui |
| Les données sont-elles chiffrées en transit ? | Oui (HTTPS) |
| L'utilisateur peut-il demander la suppression ? | Oui, dans l'application et via `https://votre-domaine.com/suppression-compte` |
| Données partagées avec des tiers ? | Non (l'hébergeur et le service e-mail sont des sous-traitants, ce n'est pas un « partage » au sens de Google) |
| **Informations personnelles** : nom, adresse e-mail, numéro de téléphone | Collectées · obligatoires · fonctionnalité de l'application, gestion du compte |
| **Informations personnelles** : adresse (commune du chantier) | Collectée · obligatoire pour un devis · fonctionnalité de l'application |
| **Photos** | Collectées · facultatives · fonctionnalité de l'application |
| **Messages** (messages dans l'application) | Collectés · facultatifs · fonctionnalité de l'application |
| Position, contacts, fichiers, santé, finances, identifiants publicitaires | Non collectés |

URL de la politique de confidentialité à déclarer : `https://votre-domaine.com/politique-de-confidentialite`

### À faire de votre côté avant la mise en ligne
- Relire la politique de confidentialité et les conditions (pages `resources/views/pages/`) et les faire valider par un juriste si possible.
- Choisir un hébergeur sérieux et mettre son nom dans la politique si vous le souhaitez.
- Changer `ADMIN_PATH` dans le `.env` et utiliser un mot de passe admin long.
- Demander l'accord écrit d'un client avant de publier des photos de sa maison dans la galerie.
- Traiter les demandes de suppression reçues dans l'admin sous 30 jours.

---

## Préparer une application mobile plus tard

Les comptes, les demandes et les messages sont déjà dans la base de données. Pour une application Android/iOS :
1. Installer Laravel Sanctum : `php artisan install:api`.
2. Créer des routes API (`routes/api.php`) qui réutilisent les mêmes modèles (`DevisRequest`, `Message`, `User`).
3. Dans l'application, garder l'écran « Supprimer mon compte » (même logique que `Client\AccountController@destroy`) et un lien vers la politique de confidentialité.
4. Ne demander l'accès à l'appareil photo qu'au moment où l'utilisateur ajoute une photo.

---

## Structure du code

```
app/
  Http/Controllers/
    PageController.php          accueil, pages légales, suppression de compte
    PublicDevisController.php   formulaire de devis de l'accueil
    PhotoController.php         affichage sécurisé des photos privées
    Auth/                       connexion, inscription, mot de passe oublié
    Client/                     espace client
    Admin/                      administration
  Http/Middleware/
    EnsureAdmin.php             404 pour tout non-admin
    EnsureClient.php            renvoie l'admin vers son tableau de bord
    SecurityHeaders.php         en-têtes de sécurité
  Models/                       User, DevisRequest, DevisPhoto, Message, Realisation, Setting, DeletionRequest
config/fabara.php               coordonnées, chemin admin, statuts, services, durées de conservation
routes/web.php                  toutes les adresses du site
routes/console.php              commandes fabara:create-admin et fabara:purge-data
resources/views/                pages (Blade)
public/css/fabara.css           styles
public/js/fabara.js             menu, calculateur, galerie, WhatsApp
public/images/                  photos du site
```

---

## Photos d'exemple « avant / après »

Les photos de `public/images/renovation/` sont des **exemples** fournis pour démarrer : le site les présente comme « Exemple de transformation (illustration) ». Dès que vous ajoutez une réalisation avec une photo « avant » dans l'admin, ce sont vos chantiers qui s'affichent à leur place. Remplacez-les vite par vos propres chantiers : vérifiez que vous avez le droit d'utiliser toute photo trouvée sur Internet.

# fabara-btp
