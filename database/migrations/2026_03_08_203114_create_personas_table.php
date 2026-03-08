<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // ex: Dr André, Secrétaire Marie
            $table->string('role');                // ex: Orthodontiste, Secrétaire, Patient
            $table->text('description')->nullable();
            $table->text('goals')->nullable();     // Objectifs
            $table->text('pain_points')->nullable(); // Frustrations
            $table->text('needs')->nullable();     // Besoins
            $table->string('avatar_color')->default('#3b82f6'); // Couleur identitaire
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Ajouter persona_id aux user_stories
        Schema::table('user_stories', function (Blueprint $table) {
            $table->foreignId('persona_id')->nullable()->after('stream_id')
                  ->constrained('personas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_stories', function (Blueprint $table) {
            $table->dropForeign(['persona_id']);
            $table->dropColumn('persona_id');
        });
        Schema::dropIfExists('personas');
    }
};
