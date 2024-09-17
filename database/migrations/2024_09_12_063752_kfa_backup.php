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
        Schema::create('kfa_backup', function (Blueprint $table) {
            $table->id();
            $table->string('kfa_code')->nullable();
            $table->string('name')->nullable();
            $table->string('state')->nullable();
            $table->string('farmalkes_type_code')->nullable();
            $table->string('farmalkes_type_name')->nullable();
            $table->string('farmalkes_type_group')->nullable();
            $table->string('dosage_form_code')->nullable();
            $table->string('dosage_form_name')->nullable();
            $table->string('produksi_buatan')->nullable();
            $table->string('nie')->nullable();
            $table->string('nama_dagang')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('registrar')->nullable();
            $table->string('generik')->nullable();
            $table->string('rxterm')->nullable();
            $table->string('dose_per_unit')->nullable();
            $table->string('fix_price')->nullable();
            $table->string('het_price')->nullable();
            $table->string('farmalkes_hscode')->nullable();
            $table->string('tayang_lkpp')->nullable();
            $table->string('net_weight')->nullable();
            $table->string('net_weight_uom_name')->nullable();
            $table->string('volume')->nullable();
            $table->string('volume_uom_name')->nullable();
            $table->string('uom_name')->nullable();
            $table->string('product_template_name')->nullable();
            $table->string('product_template_state')->nullable();
            $table->string('product_template_active')->nullable();
            $table->string('product_template_kfa_code')->nullable();
            $table->string('product_template_display_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
