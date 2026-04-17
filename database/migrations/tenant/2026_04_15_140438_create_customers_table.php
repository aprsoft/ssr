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
        Schema::create('customers', function (Blueprint $table) {
           $table->id();
            $table->string('rut',10)->nullable();
            $table->string('name');            
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();   
            $table->string('email')->unique();
            $table->string('movil')->nullable();
            $table->enum('state',['VIGENTE','NO VIGENTE']);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
