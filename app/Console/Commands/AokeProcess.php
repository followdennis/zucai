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
            'match_num' => [
              'td:eq(0)','text'
            ],
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
        $resData = $ql->range($range)->rules($rules)->query()->getData(function($item){
            preg_match('/\d+/',$item['match_id'],$id);
            $item['match_id'] = isset($id[0]) ? $id[0] : 0;  //处理比赛id
            preg_match('/\d+/',$item['match_num'],$num);
            $item['match_num'] = isset($num[0]) ? $num[0] : 0;

            if( strlen($item['match_score']) > 3){
                $score = explode('-',$item['match_score']);
            }

            if(empty($item['match_res'])){
                $item['match_res'] = 5;//网站未更新比赛及俄国
                $item['match_res_rate'] = 0;//未提供数据结果的比赛赔率设置为0
            }
            $item['host_score'] = isset($score[0]) ? $score[0] : 0;//主队进球数
            $item['guest_score'] = isset($score[1]) ? $score[1] : 0; //客队进球数
            return $item;
        });

        $max_match_info = \App\Models\Aoke::where(['status'=>2])->orderBy('id','desc')->select('id','num','match_time')->limit(1)->first();
        foreach($resData as $key => $item){
            //没有比分的比赛跳过
            if( !preg_match("/\d+/",$item['match_score'])){
                continue;
            }

            $year = date('Y',time());
            $real_match_time = $year. '-' .$item['time'];
            if( $max_match_info ){
                if( strtotime($real_match_time) > strtotime($max_match_info->match_time)){
                    //更新
                    $this->updateMatchRes($item);
                }elseif( strtotime($real_match_time) == strtotime($max_match_info->match_time) ){
                    if( $item['match_num'] > substr($max_match_info->num,1)){
                        //更新
                        $this->updateMatchRes($item);
                    }
                    continue;
                }
            } else {
                //直接更新
                $this->updateMatchRes($item);
            }

        }
    }
    //更新数据
    public function updateMatchRes($item){
        if(empty($item)){
            return false;
        }
        $res = \App\Models\Aoke::where(['status'=>0,'match_id' => $item['match_id']])->update([
            'host_score'=>$item['host_score'],
            'guest_score' => $item['guest_score'],
            'total' => $item['total_score'],
            'total_rate' => $item['total_score_rate'],
            'status' => 2,
            'score_rate' => $item['match_score_rate'], //比分赔率
            'result' => $item['match_res'],//比赛结果 0 负 1 平 3 胜
            'give_score_result' => $item['give_score_res'],
        ]);
        if( !$res ){
            $this->info('更新数据失败，matchid'.$item['match_id']);
        } else {
            $this->info('更新成功，比赛id'.$item['match_id'].',比赛队伍，'.$item['host_team_name'].':'.$item['guest_team_name']);
        }
    }
}
