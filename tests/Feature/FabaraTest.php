<?php

namespace Tests\Feature;

use App\Models\DevisRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FabaraTest extends TestCase
{
    use RefreshDatabase;

    private function adminPath(string $suffix = ''): string
    {
        return '/'.config('fabara.admin_path').$suffix;
    }

    public function test_home_page_shows_slogan_and_hides_admin(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('goûter au luxe')
            ->assertSee('Connexion')
            ->assertSee('Inscription')
            ->assertDontSee(config('fabara.admin_path'))
            ->assertDontSee('Administration');
    }

    public function test_registration_always_creates_a_client(): void
    {
        $this->post('/inscription', [
            'name' => 'Aïssatou Bah',
            'email' => 'aissatou@example.com',
            'phone' => '+224 620 00 00 00',
            'password' => 'motdepasse1',
            'password_confirmation' => 'motdepasse1',
            'terms' => '1',
            'role' => 'admin', // tentative d'élévation : doit être ignorée
        ])->assertRedirect(route('client.dashboard'));

        $user = User::where('email', 'aissatou@example.com')->firstOrFail();
        $this->assertSame(User::ROLE_CLIENT, $user->role);
        $this->assertNotNull($user->terms_accepted_at);
    }

    public function test_registration_requires_consent(): void
    {
        $this->post('/inscription', [
            'name' => 'Test', 'email' => 't@example.com', 'phone' => '+224 620 00 00 00',
            'password' => 'motdepasse1', 'password_confirmation' => 'motdepasse1',
        ])->assertSessionHasErrors('terms');
    }

    public function test_admin_area_is_invisible_to_guests_and_clients(): void
    {
        $this->get($this->adminPath())->assertNotFound();

        $client = User::factory()->create();
        $this->actingAs($client)->get($this->adminPath())->assertNotFound();
        $this->actingAs($client)->get($this->adminPath('/demandes'))->assertNotFound();
    }

    public function test_same_login_sends_admin_to_admin_and_client_to_client_space(): void
    {
        $admin = User::factory()->admin()->create(['email' => 'admin@example.com']);
        $this->post('/connexion', ['email' => 'admin@example.com', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->get($this->adminPath())->assertOk();
        $this->post('/deconnexion');

        User::factory()->create(['email' => 'client@example.com']);
        $this->post('/connexion', ['email' => 'client@example.com', 'password' => 'password'])
            ->assertRedirect(route('client.dashboard'));
    }

    public function test_guest_must_create_an_account_to_request_a_quote(): void
    {
        $payload = [
            'name' => 'Mamadou Diallo', 'phone' => '+224 621 11 22 33', 'commune' => 'Ratoma',
            'property_type' => 'Vieille maison à rénover', 'works' => ['Façades'], 'consent' => '1',
        ];

        // un visiteur ne voit pas le formulaire mais l'invitation à créer un compte
        $this->get('/')->assertSee('Créez votre compte pour demander un devis')->assertDontSee('id="devisForm"', false);

        // et ne peut pas envoyer de demande directement
        $this->post('/demande-de-devis', $payload)->assertRedirect(route('login'));
        $this->assertDatabaseCount('devis_requests', 0);

        // inscription depuis le bouton « devis » de la page Rénovation → formulaire pré-rempli
        $this->get('/inscription?suite=renovation')->assertOk();
        $this->post('/inscription', [
            'name' => 'Mamadou Diallo', 'email' => 'mamadou@example.com', 'phone' => '+224 621 11 22 33',
            'password' => 'motdepasse1', 'password_confirmation' => 'motdepasse1', 'terms' => '1',
        ])->assertRedirect(route('client.devis.create', ['type' => 'renovation']));

        // connecté, le client voit le formulaire et peut envoyer sa demande
        $this->get('/')->assertSee('id="devisForm"', false);
        $this->post('/demande-de-devis', $payload)->assertRedirect();
        $this->assertDatabaseHas('devis_requests', ['name' => 'Mamadou Diallo', 'status' => 'nouveau']);
        $this->assertNotNull(\App\Models\DevisRequest::first()->user_id);
    }

    public function test_client_cannot_see_another_clients_request(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $devis = DevisRequest::create([
            'user_id' => $owner->id, 'name' => 'A', 'phone' => '620000000', 'commune' => 'Kaloum',
            'property_type' => 'Appartement', 'works' => ['Peinture'], 'consent_at' => now(),
        ]);

        $this->actingAs($other)->get(route('client.devis.show', $devis->reference))->assertNotFound();
        $this->actingAs($owner)->get(route('client.devis.show', $devis->reference))->assertOk();
    }

    public function test_client_can_export_and_delete_account(): void
    {
        $user = User::factory()->create();
        DevisRequest::create([
            'user_id' => $user->id, 'name' => 'A', 'phone' => '620000000', 'commune' => 'Kaloum',
            'property_type' => 'Appartement', 'works' => ['Peinture'], 'consent_at' => now(),
        ]);

        $this->actingAs($user)->get(route('client.account.export'))->assertOk();

        $this->actingAs($user)->delete(route('client.account.destroy'), ['password' => 'password'])
            ->assertRedirect(route('home'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseCount('devis_requests', 0);
    }

    public function test_uploaded_photos_are_private(): void
    {
        Storage::fake('local');
        $owner = User::factory()->create();
        $this->actingAs($owner)->post(route('client.devis.store'), [
            'name' => 'A', 'phone' => '+224 620 00 00 00', 'commune' => 'Matam',
            'property_type' => 'Villa / maison', 'works' => ['Peinture'],
            'photos' => [UploadedFile::fake()->image('salon.jpg', 800, 600)],
        ])->assertRedirect();

        $photo = DevisRequest::firstOrFail()->photos()->firstOrFail();
        Storage::disk('local')->assertExists($photo->path);

        $this->actingAs($owner)->get(route('photos.show', $photo))->assertOk();
        $this->actingAs(User::factory()->create())->get(route('photos.show', $photo))->assertNotFound();
        $this->actingAs(User::factory()->admin()->create())->get(route('photos.show', $photo))->assertOk();
    }

    public function test_admin_can_update_status_and_reply(): void
    {
        $admin = User::factory()->admin()->create();
        $client = User::factory()->create();
        $devis = DevisRequest::create([
            'user_id' => $client->id, 'name' => 'B', 'phone' => '620000000', 'commune' => 'Dixinn',
            'property_type' => 'Appartement', 'works' => ['Carrelage'], 'consent_at' => now(),
        ]);

        $this->actingAs($admin)->put(route('admin.devis.update', $devis), [
            'status' => 'devis', 'quoted_amount' => '4 500 000', 'admin_notes' => 'Visite faite',
        ])->assertRedirect();
        $this->assertSame(4500000, $devis->fresh()->quoted_amount);

        $this->actingAs($admin)->post(route('admin.devis.message', $devis), ['body' => 'Bonjour, devis envoyé.'])->assertRedirect();
        $this->actingAs($client)->get(route('client.devis.show', $devis->reference))
            ->assertSee('Bonjour, devis envoyé.')
            ->assertDontSee('Visite faite'); // notes internes jamais visibles du client
    }

    public function test_admin_pages_render(): void
    {
        $admin = User::factory()->admin()->create();
        $client = User::factory()->create();
        \App\Models\DeletionRequest::create(['name' => 'X', 'contact' => $client->email, 'status' => 'en_attente']);
        $this->seed();

        foreach (['', '/demandes', '/services', '/realisations', '/clients', '/clients/'.$client->id, '/suppressions', '/parametres'] as $path) {
            $this->actingAs($admin)->get($this->adminPath($path))->assertOk();
        }
        foreach (['/mon-espace', '/mon-espace/demandes/nouvelle', '/mon-espace/compte'] as $path) {
            $this->actingAs($client)->get($path)->assertOk();
        }
    }

    public function test_renovation_page_shows_before_after_examples(): void
    {
        $this->get('/renovation-maison')
            ->assertOk()
            ->assertSee('Modernisez votre')
            ->assertSee('images/renovation/avant-1.jpg', false)
            ->assertSee('Exemple de transformation');
        $this->get('/')->assertSee(route('renovation'), false);
    }

    public function test_admin_before_after_replaces_examples(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.realisations.store'), [
            'title' => 'Villa rénovée à Kipé',
            'category' => 'Rénovation',
            'before_image' => UploadedFile::fake()->image('avant.jpg', 800, 600),
            'image' => UploadedFile::fake()->image('apres.jpg', 800, 600),
            'is_featured' => '1',
        ])->assertRedirect();

        $r = \App\Models\Realisation::where('title', 'Villa rénovée à Kipé')->firstOrFail();
        $this->assertTrue($r->hasBefore());
        Storage::disk('public')->assertExists($r->before_image_path);

        $this->get('/renovation-maison')
            ->assertSee('Villa rénovée à Kipé')
            ->assertDontSee('images/renovation/avant-1.jpg', false);
    }

    public function test_admin_can_create_edit_move_and_delete_services(): void
    {
        Storage::fake('public');
        $this->seed();
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.services.store'), [
            'title' => 'Électricité bâtiment',
            'icon' => 'gear',
            'summary' => 'Installation et mise aux normes.',
            'items' => "Tableaux électriques\nPrises et éclairage\n\n",
            'image' => UploadedFile::fake()->image('elec.jpg', 800, 600),
            'is_published' => '1',
            'in_quote_form' => '1',
        ])->assertRedirect();

        $svc = \App\Models\Service::where('title', 'Électricité bâtiment')->firstOrFail();
        $this->assertSame(['Tableaux électriques', 'Prises et éclairage'], $svc->items);
        Storage::disk('public')->assertExists($svc->image_path);

        // visible sur l'accueil et proposé dans le formulaire de devis
        $this->get('/')->assertSee('Électricité bâtiment');
        $this->actingAs(User::factory()->create())->get('/')->assertSee('value="Électricité bâtiment"', false);

        // un client peut choisir ce nouveau service dans sa demande
        $this->actingAs(User::factory()->create())->post('/demande-de-devis', [
            'name' => 'C', 'phone' => '+224 620 00 00 00', 'commune' => 'Matam',
            'property_type' => 'Villa / maison', 'works' => ['Électricité bâtiment'], 'consent' => '1',
        ])->assertSessionHasNoErrors();

        // modification : masqué → disparaît du site
        $this->actingAs($admin)->put(route('admin.services.update', $svc), [
            'title' => 'Électricité', 'icon' => 'gear', 'summary' => 'Mise aux normes.', 'items' => '',
        ])->assertRedirect();
        $this->assertFalse($svc->fresh()->is_published);
        $this->get('/')->assertDontSee('Mise aux normes.');

        // déplacement vers le haut
        $before = $svc->fresh()->sort;
        $this->actingAs($admin)->post(route('admin.services.move', [$svc, 'haut']))->assertRedirect();
        $this->assertLessThan($before, $svc->fresh()->sort);

        // suppression (et de la photo)
        $path = $svc->fresh()->image_path;
        $this->actingAs($admin)->delete(route('admin.services.destroy', $svc))->assertRedirect();
        $this->assertDatabaseMissing('services', ['id' => $svc->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_public_legal_pages_are_reachable(): void
    {
        $this->get('/politique-de-confidentialite')->assertOk();
        $this->get('/conditions-utilisation')->assertOk();
        $this->get('/suppression-compte')->assertOk();
    }
}
