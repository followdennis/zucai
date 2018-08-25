<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use QL\QueryList;

class AoKe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:aoke';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $url = 'http://www.okooo.com/jingcai/';

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
        $this->info('澳客网');
        $this->crawler();
    }

    /**
     * 比赛数据抓取
     */
    public function crawler(){
        $url = $this->url;
        $this->info($url);
        $data = QueryList::get($url)->ruless([

        ])->getData();
        print_r($data);
    }
}
