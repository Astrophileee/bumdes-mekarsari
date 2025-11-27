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
        Schema::create('log_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->decimal('initial_stock',10,2);
            $table->decimal('change_amount',10,2);
            $table->decimal('final_stock',10,2);
            $table->text('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_stocks');
    }
};
