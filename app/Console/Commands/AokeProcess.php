<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use QL\QueryList;

class AokeProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:aoke_process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '澳客网数据结果处理';

    protected $default_url = 'http://www.okooo.com/jingcai/kaijiang/';

    //带参数的url
    protected $param_url = 'http://www.okooo.com/jingcai/kaijiang/?LotteryType=SportteryWDL&StartDate=2019-02-02&EndDate=2019-02-03';
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
     * 2018-02-03 10:42:33
     * @return mixed
     */
    public function handle()
    {
        //奥克数比赛结果处理
        $this->process();
    }

    public function process(){
        $url = $this->default_url;
        $ql = QueryList::get($url)->encoding('UTF-8','GB2312');

        $range= 'tr.trClass';
        $rules = [
            'competition_name' => [
                'td:eq(1)','text'
            ],
            'time' => [
                'td:eq(2)','text'
            ],
            'host_team_name' => [
                'td:eq(3) a' , 'text'
            ],
            'guest_team_name' => [
                'td:eq(4) a','text'
            ],
            'match_id' => [
                'td:eq(3) a','href'
            ],
            'match_score' => [
                'td:eq(6)','text'
            ],
            'match_res' => [
                'td:eq(7) b','text'
            ],
            'match_res_rate' => [
                'td:eq(8)' ,'text'
            ],
            'give_score_num' => [
                'td:eq(9) span','text'
            ],
            'give_score_res' => [
                'td:eq(10) b','text'
            ],
            'give_score_rate' => [
                'td:eq(11)','text'
            ],
            'match_score_rate'=>[
                'td:eq(13)','text'
            ],
            'total_score' => [
                'td:eq(14) b','text'
            ],
            'total_score_rate' => [
                'td:eq(15)','text'
            ],
            'half_match' => [
                'td:eq(16) b','text'
            ],
            'half_match_rate' => [
                'td:eq(17)','text'
            ]
        ];
        $res = $ql->range($range)->rules($rules)->query()->getData();

        print_r($res);

    }
}
