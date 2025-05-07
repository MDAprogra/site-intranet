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
        Schema::create('indicateur_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indicateur_id');
            $table->unsignedBigInteger('user_id');
            // Ajoutez d'autres champs si nécessaire (par exemple, une valeur pour l'indicateur par utilisateur)
            $table->timestamps();

            $table->foreign('indicateur_id')->references('id')->on('indicateurs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['indicateur_id', 'user_id']); // Assure l'unicité des combinaisons indicateur-utilisateur
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicateur_user');
    }
};
