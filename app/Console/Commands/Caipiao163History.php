<?php

namespace App\Console\Commands;

use App\Models\SourceWangyiCaipiao;
use App\Models\WangyiGuestHistoryScore;
use Illuminate\Console\Command;

class Caipiao163History extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:163history';

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
        //
        $this->info('history');
        $this->process();
    }

    /**
     * 获取历史数据
     */
    public function process(){
        $list = SourceWangyiCaipiao::where('has_history_score',0)->select('detail_url','id as match_id','host_team_name','guest_team_name');
        foreach($list->cursor() as $k => $item){
            if(strlen($item->detail_url)){
                $this->info($item->detail_url);

                $this->info($item->match_id);
            }
        }

    }

    public function getParams(){

    }
}
