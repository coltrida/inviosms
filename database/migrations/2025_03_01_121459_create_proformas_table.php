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
        Schema::create('proformas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Client::class, 'intermediario_id')->nullable();
            $table->foreignIdFor(\App\Models\Client::class, 'client_id')->nullable();
            $table->foreignIdFor(\App\Models\User::class);
            $table->string('cliente_finale')->nullable();
            $table->string('intermediario')->nullable();
            $table->string('stato')->nullable();
            $table->string('dataDocumento')->nullable();
            $table->float('totale')->nullable();
            $table->string('canalePrimario')->nullable();
            $table->string('canaleSecondario')->nullable();
            $table->integer('anno')->nullable();
            $table->integer('mese')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proformas');
    }
};
