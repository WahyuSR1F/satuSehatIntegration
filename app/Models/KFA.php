<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KFA extends Model
{
    use HasFactory;
    protected $table = 'master_kfa';

    protected $fillable = [
        'kfa_code',
        'name',
        'state',
        'farmalkes_type_code',
        'farmalkes_type_name',
        'farmalkes_type_group',
        'dosage_form_code',
        'dosage_form_name',
        'dosage_form_system',
        'produksi_buatan',
        'nie',
        'nama_dagang',
        'manufacturer',
        'registrar',
        'generik',
        'rxterm',
        'dose_per_unit',
        'fix_price',
        'het_price',
        'farmalkes_hscode',
        'tayang_lkpp',
        'net_weight',
        'net_weight_uom_name',
        'volume',
        'volume_uom_name',
        'uom_name',
        'product_template_name',
        'product_template_state',
        'product_template_active',
        'product_template_kfa_code',
        'product_template_display_name',
        'active_ingredients'
    ];
}
