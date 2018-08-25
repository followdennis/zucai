<?php

namespace App\Console\Commands;

use App\Models\SourceWangyiCaipiao;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '一般用来处理数据';

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
        $this->info('测试');
//        $this->calculateScoreData();
        $data = $this->getCompetition();
        foreach($data as $k => $v){
            $this->info($k .'-'.$v->competition_name );
        }
        $this->info('结束');

    }

    public function crawler(){

    }
    /**
     * 计算大比分
     */
    public function calculateScoreData(){
        $data = SourceWangyiCaipiao::where('status',2)->where('big_score',0);
        foreach($data->cursor() as $k => $v){
            $v->big_score = $v->host_team_score-$v->guest_team_score;
            $v->save();
            echo $k."\n";
        }
    }
    /**
     * 获取比赛名称分组数据
     */
    public function getCompetition(){
        $data = SourceWangyiCaipiao::groupBy('competition_name')->select('competition_name')->get();
        return $data;
    }
}
