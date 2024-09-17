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
        Schema::create('token_accesses', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('client_id')->unique();
            $table->string('token')->unique();
            $table->string('issued_at');
            $table->string('aplication_name');
            $table->time('interval_access');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_accesses');
    }
};