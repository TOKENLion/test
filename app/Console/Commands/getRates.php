<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class getRates extends Command
{
    private $endpoint = "https://www.bnm.md/en/official_exchange_rates";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'curl:getRate {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get exchange rates from API (argument not required - send date format "d.m.Y" for get data by this date)';

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
        $date = $this->argument('date');
        
        // Date format need to be in this format for API
        if (empty($date)) {
            $date = date('d.m.Y');
        } else {
            $validation = Validator::make(['date' => $date], [
                'date' => 'date_format:"d.m.Y"',
            ]);
    
            if ($validation->fails()) {
                exit($validation->errors());
            }
        }
        
        $client = new \GuzzleHttp\Client();

        // CURL request to API url for get data
        $response = $client->request('GET', $this->endpoint, ['query' => [
            'get_xml' => 1, 
            'date' => $date,
        ]]);
        
        // Get body of request
        $content = $response->getBody();

        // Init XML Object
        $xmlObject = simplexml_load_string($content);
                   
        // Convert XML Object to Array format
        $json = json_encode($xmlObject);
        $arrayData = json_decode($json, true);

        $data = array_map(function ($item) use ($date) {
            return [
                'currencyCode'  => $item['CharCode'],
                'ammount'       => $item['Nominal'],
                'value'         => $item['Value'],
                'date'          => date('Y-m-d', strtotime($date)) // Format for DB
            ];
        }, $arrayData['Valute']);

        // Call local endpoint for insert data
        $request = Request::create(route('exchangeRate.store'), 'POST', [], [], [], [], json_encode($data));
        $response = app()->handle($request);
        // dd($response->getContent());
        exit('Done!');
    }
}
