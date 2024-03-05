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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();// llave primaria y es autoincremental automatico controla laravel;
            $table->string('title',60)->unique();// unicos
            $table->string('alias',60)->unique();
            $table->smallInteger('position');
            $table->boolean('published');
            $table->timestamps();
            $table->string('created_by',60);
            $table->string('updated_by',60)->nullable();// permite nulos
            // se crea una tabla con essa definicion cuando se lea las migraciones
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
