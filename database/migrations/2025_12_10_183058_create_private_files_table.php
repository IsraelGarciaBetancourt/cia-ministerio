<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('private_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_category_id')->constrained()->cascadeOnDelete();
            $table->string('title');                  // Ej: Bautizo - Juan Pérez
            $table->text('description')->nullable(); // Descripción del documento
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('private_files');
    }
};
