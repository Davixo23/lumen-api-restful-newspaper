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
        Schema::create('contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pre_title',180);// siempre poner restricion y limite de caracteres
            $table->string('title',180);
            $table->string('alias',180);
            $table->string('author',60);
            $table->string('image_url',255);
            $table->string('introduction',300);
            $table->text('body');
            $table->string('tags',300);//etiquetas de la noticia
            $table->enum('format',['ONLY_TEXT','WITH_IMAGE', 'WITH_GALLERY','WITH_VIDEO']);
            $table->boolean('featured');//destacada
            $table->enum('status',['WRITING','PUBLISHED', 'NOT_PUBLISHED','ARCHIVED']);// campos establecidos una listade valores preestablecidos
            $table->unsignedSmallInteger('edition_date');// ENTERO POSITIVO FECHA 20240217
            $table->string('category_title',60);
            $table->string('category_alias',60);// CUESTION DE DISEÑO POR TEMA DE CAMBIOS DE NOMBRES ETC
            $table->timestamps();
            $table->string('created_by',60);
            $table->string('updated_by',60)->nullable();
            $table->unique(['edition_date','category_alias','alias']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
