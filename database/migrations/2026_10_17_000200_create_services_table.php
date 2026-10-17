<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Services affichés sur le site, gérés depuis l'administration. */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title', 80);
            $table->string('icon', 20)->default('check');
            $table->string('summary', 300);
            $table->json('items')->nullable();        // points forts, un par ligne dans l'admin
            $table->string('image_path')->nullable(); // avec photo : grande carte ; sans photo : ligne
            $table->string('image_alt')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('in_quote_form')->default(true); // proposé dans « Travaux souhaités »
            $table->integer('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
