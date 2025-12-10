<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            
            $table->string('type'); // image | video
            $table->string('file_path')->nullable(); // WebP o video local
            $table->string('original_filename')->nullable();
            $table->string('mime_type')->nullable();

            $table->boolean('is_external')->default(false);
            $table->string('external_url')->nullable(); // YouTube, Vimeo, etc
            
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
