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
        Schema::create('provas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->nullable();
            $table->foreignIdFor(\App\Models\Client::class)->nullable();
            $table->string('contatto')->nullable();
            $table->string('stato')->nullable();
            $table->float('totale')->nullable();
            $table->date('dataDocumento')->nullable();
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
        Schema::dropIfExists('provas');
    }
};
