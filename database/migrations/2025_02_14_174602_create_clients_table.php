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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('tipo')->nullable();
            $table->string('nome');
            $table->string('cognome')->nullable();
            $table->string('fullname')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('telefono2')->nullable();
            $table->string('indirizzo')->nullable();
            $table->string('citta')->nullable();
            $table->string('cap')->nullable();
            $table->string('provincia')->nullable();
            $table->text('note')->nullable();
            $table->string('canalePrimario')->nullable();
            $table->string('canaleSecondario')->nullable();
            $table->foreignIdFor(\App\Models\Strutture::class)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
