<?php

namespace App\Http\Controllers\KFA;

use App\Models\KFA;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;
use App\Models\KFATemp;

class KfaController extends Controller
{
    protected $url;
    public function __construct()
    {
        $this->url =  env('URL_API');
    }

    public function getAllProductPaginate()
    {
        //get jumlah terahkir
        $lastesJumlah = $this->getLastest();

        $indexs = $this->createIndexs($jumlah = 0, $size = 1000);

        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();

        $product_type = 'farmasi';
        $response = $this->Api($this->url, $tokenAccess->data->token, $indexs->id, $size, $product_type);
        $data = ($response->getData())->items->data;

        // Proses data dalam chunk untuk efisiensi
        collect($data)->chunk(100)->each(function ($chunk) {
            foreach ($chunk as $obat) {
                $this->CreateKFA($obat);
            }
        });

        // Tambahkan data baru ke createIndexs dan perbarui jumlah kumulatif
        $jumlah = count($data);
        $total = $lastesJumlah ? $lastesJumlah->jumlah : 0 + $jumlah;

        $this->updateIndeks($total, $indexs->id);
    }
    public function Api($url, $bearer_token,  $page, $size, $product_type)
    {
        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Bearer ' . $bearer_token,
        ])->get($url . '/kfa-v2/products/all', [
            'page' => $page, // Add page input
            'size' => $size, // Add size input
            'product_type' => $product_type, // Add product_type input
        ]);



        $response = json_decode($response->body());
        return response()->json($response);
    }

    protected function CreateKFA($obat)
    {
        // Cek apakah entri sudah ada berdasarkan kfa_code
        $existingKFA = KFA::where('kfa_code', $obat->kfa_code)->first();

        // Jika entri tidak ada, buat entri baru
        if (!$existingKFA) {
            KFA::create([
                'kfa_code' => $obat->kfa_code,
                'name' => $obat->name,
                'active' => $obat->active,
                'state' => $obat->state,
                'farmalkes_type_code' => $obat->farmalkes_type->code,
                'farmalkes_type_name' => $obat->farmalkes_type->name,
                'farmalkes_type_group' => $obat->farmalkes_type->group,
                'dosage_form_code' => $obat->dosage_form->code,
                'dosage_form_name' => $obat->dosage_form->name,
                'produksi_buatan' => $obat->produksi_buatan,
                'nie' => $obat->nie,
                'nama_dagang' => $obat->nama_dagang,
                'manufacturer' => $obat->manufacturer,
                'registrar' => $obat->registrar,
                'generik' => $obat->generik,
                'rxterm' => $obat->rxterm,
                'dose_per_unit' => $obat->dose_per_unit,
                'fix_price' => $obat->fix_price,
                'het_price' => $obat->het_price,
                'farmalkes_hscode' => $obat->farmalkes_hscode,
                'tayang_lkpp' => $obat->tayang_lkpp,
                'net_weight' => $obat->net_weight,
                'net_weight_uom_name' => $obat->net_weight_uom_name,
                'volume' => $obat->volume,
                'volume_uom_name' => $obat->volume_uom_name,
                'uom_name' => $obat->kode_lkpp,
                'product_template_name' => $obat->product_template->name,
                'product_template_state' => $obat->product_template->state,
                'product_template_active' => $obat->product_template->active,
                'product_template_kfa_code' => $obat->product_template->kfa_code,
                'product_template_display_name' => $obat->product_template->display_name,
            ]);
        }
    }

    public function createIndexs($jumlah =  0, $size = 1000)
    {
        $indexs =  KFATemp::create([
            'jumlah'  => $jumlah,
            'size' => $size
        ]);

        return $indexs;


        // Hitung jumlah kumulatif baru

    }

    public function updateIndeks($jumlah, $id)
    {
        KFATemp::where('id', $id)->update([
            'jumlah' => $jumlah
        ]);
    }
    public function getLastest()
    {
        $latest = KFATemp::latest()->first();

        return $latest;
    }

    public function getAllProductPaginateTest()
    {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();

        $product_type = 'farmasi';
        $response = $this->Api($this->url, $tokenAccess->data->token, 2, 1000, $product_type);
        $data = ($response->getData())->items->data;

        // Proses data dalam chunk untuk efisiensi
        return response()->json($data);
    }
}
