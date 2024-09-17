<?php

namespace App\Jobs;

use App\Models\KFA;
use App\Models\KFATemp;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Http\Controllers\KFA\KfaController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\TokenAccessContorller;

class KfaStaff implements ShouldQueue
{
    use Queueable;




    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $kfa = (new KfaController())->getAllProductPaginate();
    }
}
