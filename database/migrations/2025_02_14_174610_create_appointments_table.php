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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Client::class)->nullable();
            $table->string('contatto');
            $table->string('nome')->nullable();
            $table->string('cognome')->nullable();
            $table->string('fullname')->nullable();
            $table->string('tipo')->nullable();
            $table->date('previsto')->nullable();
            $table->string('esito')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
