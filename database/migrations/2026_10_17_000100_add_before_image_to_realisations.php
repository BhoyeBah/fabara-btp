<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Photo « avant » facultative : une réalisation qui en a une s'affiche en comparateur avant / après. */
    public function up(): void
    {
        Schema::table('realisations', function (Blueprint $table) {
            $table->string('before_image_path')->nullable()->after('image_path');
            $table->boolean('is_featured')->default(false)->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('realisations', function (Blueprint $table) {
            $table->dropColumn(['before_image_path', 'is_featured']);
        });
    }
};
