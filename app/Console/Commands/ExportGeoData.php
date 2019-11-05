<?php

namespace App\Console\Commands;

use App\FireDepartment;
use App\Models\Hydrant;
use App\Models\Polygon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ExportGeoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:geodata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $guzzle = new Client();
        $jsons = [];

        $fireDepartments = FireDepartment::all();
        $hydrants = Hydrant::with('fireDepartment')->get();
        $hydrants = $hydrants->map(function ($hydrant) {
            return [
                'operator_name' => $hydrant->operator_name,
                'correction_date' => $hydrant->correction_date,
                'address' => $hydrant->address,
                'specification' => $hydrant->specification,
                'fire_department_title' => $hydrant->fireDepartment->title,
                'fire_department_id' => $hydrant->fire_department_id,
                'lat' => $hydrant->lat,
                'long' => $hydrant->long,
                'number' => $hydrant->number,
                'active' => $hydrant->active,
            ];
        });
        $polygons = Polygon::where('title', '<>', '')->get(['points', 'title', 'line_color', 'fill_color', 'opacity']);

        $fireDepartmentsJson = [];
        $jsons['hydrants'] = json_encode($hydrants->jsonSerialize());
        $jsons['polygons'] = json_encode($polygons);

        foreach ($fireDepartments as $fireDepartment) {
            $address = 'Казахстан Алматы, ';

            try {
                $address .= $fireDepartment->address;
                $url = "https://geocode-maps.yandex.ru/1.x/?geocode={$address}&apikey=031fe896-c942-4583-beee-a12ed15e7075&lang=ru_RU&format=json&results=1";
                $response = $guzzle->request('GET', $url);
                $response = $response->getBody() ? $response->getBody()->getContents() : null;
                $response = $response ? json_decode($response, true) : null;

                $finalString = Arr::get($response, 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos');
                $fireDepartmentsJson[] = [
                    'long' => explode(' ', $finalString)[0],
                    'lat' => explode(' ', $finalString)[1],
                    'title' => $fireDepartment->title,
                    'address' => $fireDepartment->address
                ];
            } catch (\Exception $exception) {

                $this->line('*****************');
                $this->error($exception->getMessage());
                $this->error($fireDepartment->title);
                $this->line('*****************');

                continue;
            }

        }

        $jsons['fire_departments'] = json_encode($fireDepartmentsJson);

        foreach ($jsons as $title => $json) {
            $filePath = public_path($title . '.json');
            file_put_contents($filePath, $json);
        }
    }
}
