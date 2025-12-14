<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // "Finanzas - Domingo 07/12/2025"
            $table->text('description')->nullable();
            $table->date('finance_date');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->boolean('is_closed')->default(false);
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index('finance_date');
            $table->index('is_closed');
        });
    }

    public function down()
    {
        Schema::dropIfExists('finances');
    }
};