<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devis_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            // Supprimer le compte supprime aussi ses demandes (droit à l'effacement).
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('commune', 60);
            $table->string('property_type', 60);
            $table->json('works');
            $table->decimal('surface', 10, 2)->nullable();
            $table->string('start_when', 40)->nullable();
            $table->text('message')->nullable();
            $table->string('status', 20)->default('nouveau')->index();
            $table->unsignedBigInteger('quoted_amount')->nullable(); // en GNF
            $table->text('admin_notes')->nullable();                // visible par l'admin uniquement
            $table->timestamp('consent_at');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('devis_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devis_request_id')->constrained()->cascadeOnDelete();
            $table->string('path');            // disque privé « local »
            $table->string('original_name')->nullable();
            $table->unsignedInteger('size')->default(0);
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devis_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('from_admin')->default(false);
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('realisations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category', 40);
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path');
            $table->boolean('is_published')->default(true);
            $table->integer('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('deletion_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact');   // e-mail ou téléphone du compte
            $table->text('message')->nullable();
            $table->string('status', 20)->default('en_attente');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deletion_requests');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('realisations');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('devis_photos');
        Schema::dropIfExists('devis_requests');
    }
};
