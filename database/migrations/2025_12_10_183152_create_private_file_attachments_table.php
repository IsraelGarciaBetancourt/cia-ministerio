<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('private_file_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('private_file_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');           // ruta en storage
            $table->string('original_filename');   // nombre original
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable(); // bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('private_file_attachments');
    }
};
