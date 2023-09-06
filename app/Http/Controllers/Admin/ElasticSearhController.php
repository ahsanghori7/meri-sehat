<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Elastic\Elasticsearch\ClientBuilder;
use App\Models\Ads as MainModel;

class ElasticSearhController extends Controller
{
    public function index(request $request){
        $client = ClientBuilder::create()
            ->setHosts(['localhost:5601'])
            ->build();

            return json_encode($client); // 8.0.0
        // Info API
        $response = $client->info();

    }
}
