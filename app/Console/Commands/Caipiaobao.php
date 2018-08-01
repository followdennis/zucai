<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class Caipiaobao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:caipiaobao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'caipiaobao spider';

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
        //
        $this->info('彩票宝');
        $client = new Client();
        $url = 'http://caipiao.163.com/order/jczq-hunhe/#from=leftnav';
        $response = $client->request('get',$url);
        $html = $response->getBody();
        echo $html;
    }
}
