<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use App\Models\ext_work_location;
use DB;
use Illuminate\Support\Facades\Log;

class SyncExternalTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncExt.cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronización de la tabla location con la tabla externa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            Log::info("Inicio sincronización");
            DB::beginTransaction();

            $new_locations = ext_work_location::leftJoin('locations', 'ext_work_location.ID', '=', 'locations.idExt')
            ->whereNull('locations.id')
            ->select('ext_work_location.*')
            ->get();

            $key = 'AIzaSyBsZphygAUL0KNMKoBxVQM3w1s60KVHC78';
            foreach ($new_locations as $new_loc) {
                $address = urlencode($new_loc->STD_WORK_LOCESP);
                $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$key}";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                $google = json_decode($response, true);

                //obtener lat/lng si la respuesta es OK
                $lat = 0;
                $lng = 0;
                if (isset($google['status']) && $google['status'] === 'OK') {
                    $lat = $google['results'][0]['geometry']['location']['lat'];
                    $lng = $google['results'][0]['geometry']['location']['lng'];
                }else{
                    Log::warning('Google Maps API error', [
                        'address' => $new_loc->STD_WORK_LOCESP,
                        'status' => $google['status'] ?? 'NO_RESPONSE',
                        'error_message' => $google['error_message'] ?? null,
                    ]);
                    continue;
                }

                Location::create([
                    'idExt'     => $new_loc->ID,
                    'name'      => $new_loc->STD_ID_WORK_LOCAT,
                    'address'   => $new_loc->STD_WORK_LOCESP,
                    'latitude'  => $lat,
                    'longitude' => $lng,
                    'radius'    => 3,
                    'time'      => 20,
                ]);
            }

            Log::info('Sincronización finalizada.');
            DB::commit();
        } catch (\Throwable $th) {
            Log::error('Error sincronización en las tablas location y ext_work_location.', ['error'=>$th]);
            DB::rollback();
        }


    }
}
