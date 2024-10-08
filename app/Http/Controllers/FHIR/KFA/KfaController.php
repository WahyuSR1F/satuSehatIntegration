<?php

namespace App\Http\Controllers\FHIR\KFA;

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
       

        $indexs = $this->createIndexs($jumlah = 0, $size = 3000);

        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();

        $product_type = 'farmasi';
        $response = $this->Api($this->url, $tokenAccess->data->token, $indexs->id, $size, $product_type);
        $data = ($response->getData())->items->data;

        //Proses data dalam chunk untuk efisiensi
        collect($data)->chunk(100)->each(function ($chunk) {
            foreach ($chunk as $obat) {
                $this->CreateKFA($obat);
            }
        });

        // Tambahkan data baru ke createIndexs dan perbarui jumlah kumulatif
        $jumlah = count($data);
       
        $total =($lastesJumlah ? $lastesJumlah->jumlah : 0)+ $jumlah;

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


    public function ApiDetail($url, $bearer_token,  $code)
    {
        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Bearer ' . $bearer_token,
        ])->get($url . '/kfa-v2/products', [
            'identifier' => 'kfa',
            'code' => $code 
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
                'ing'


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
        $response = $this->Api($this->url, $tokenAccess->data->token, 2, 10, $product_type);
        dd($response);
        $data = ($response->getData())->items->data;

        return response()->json($data);
    }

    // public function getDetailProductPaginateTest()
    // {
    //     // Tambahkan waktu eksekusi menjadi 300 detik
    //     set_time_limit(10000);
    
    //     KFA::where('id', '>=', 2077)->chunk(500, function ($items) {
    //         foreach ($items as $item) {
    //             // Dapatkan token untuk setiap batch
    //             $tokenAccess = (new TokenAccessContorller())->getToken();
    //             $tokenAccess = $tokenAccess->getData();
        
    //             // Ambil detail dari API
    //             $response = $this->ApiDetail($this->url, $tokenAccess->data->token, $item->kfa_code);
                
    //             $result = ($response->getData())->result;
    //             if($result){
    //                 $result = json_encode($result->active_ingredients); // Konversi ke JSON
    //                 KFA::where('kfa_code', $item->kfa_code)->update(['active_ingredients' => $result]);
    //             }
              
        
    //             // Update database untuk setiap item
                
               
    //         }
    //     });
    
    //     return response()->json("finish");
    // }

    public function getDetailProductPaginateTestAplication()
    {
        // Tambahkan waktu eksekusi menjadi 300 detik
                $tokenAccess = (new TokenAccessContorller())->getToken();
                $tokenAccess = $tokenAccess->getData();
        
                // Ambil detail dari API
                $response = $this->ApiDetail($this->url, $tokenAccess->data->token, '93021994');
                $response = $response->getData();
                dd($response);  
                
        
                // Update database untuk setiap item
                
    
        return response()->json($response);
    }


    
}
