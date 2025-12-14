<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('finance_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_id')->constrained()->onDelete('cascade');
            $table->foreignId('finance_type_id')->constrained();
            $table->foreignId('brother_id')->nullable()->constrained(); // Solo para diezmos
            $table->decimal('amount', 10, 2);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index(['finance_id', 'finance_type_id']);
            $table->index('brother_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_entries');
    }
};