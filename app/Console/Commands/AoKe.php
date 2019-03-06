<?php

namespace App\Console\Commands;

use App\Models\AokeHalfGround;
use App\Models\AokeMatchScore;
use App\Models\AokeTotalScore;
use App\Models\AokeWinAndFail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use QL\Dom\Query;
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

    //日期 例：2019-02-01 morder : 6176
    private $more_url = 'http://www.okooo.com/jingcai/?action=more&LotteryNo=date&MatchOrder=morder';

    protected $success_num = 0;
    protected $fail_num = 0;
    protected $total_num = 0;

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
        $this->info('总计:'.$this->total_num);
        $this->info('成功:'.$this->success_num . '条');
        $this->info('失败:'. $this->fail_num . "条");
    }

    /**
     * 比赛数据抓取
     */
    public function crawler(){
        $url = $this->url;
        $this->info($url);
        $ql = QueryList::get($url)->encoding('UTF-8','GB2312');

        $htmls = $ql->find('.cont')->htmls();

        $this->info('开始,共'.count($htmls) .'天的数据');
        $max_mod = \App\Models\Aoke::orderBy('id','desc')->first();
        $max_match_time = $max_mod ?  $max_mod->match_time :'2000-01-01 00:00:00';

        $max_match_num = $max_mod ? $max_mod->num :0;

        $rules = $this->getOneRules();
        foreach($htmls as $index => $html){
            $match_dom = QueryList::html($html);
            $time = $match_dom->find('.riqi .time')->attr('data-time');
            $spec_time = $match_dom->find('.riqi .time > a span')->text();

            $spec_time = strtotime(substr($spec_time,0,10)); //时间过滤转换


            $datetime = date('Y-m-d H:i:s',$spec_time);//投注日期

            $date = date('Y-m-d',$time);
            $range = '.cont';//这个字段非常必要
            $data = $match_dom->rules($rules)->query()->getData(function($item){
                $host_rank =  QueryList::html($item['host_team_rank'])->find('p')->eq(0)->text();
                $guest_rank = QueryList::html($item['guest_team_rank'])->find('p')->eq(0)->text();
                $item['host_team_rank'] = $host_rank ? $host_rank : 0;
                $item['guest_team_rank'] = $guest_rank ? $guest_rank : 0;
                return $item;
            });

          foreach($data as $key => $item ){
              if(isset( $item['data_end']) && $item['data_end'] == 0){
                  //总数居统计
                  $this->total_num++;


                  if( count($item) < 16){
                      $this->info('字段不全'.$item['match_code']);
                      Log::info("字段不全".$spec_time .$item['match_code'] . $item['match_time']);
                  } else {
                        $item['match_time'] = str_replace('比赛时间:','',$item['match_time']);
                        $item['betting_date'] = $datetime;
                        if( strtotime($max_match_time) > strtotime($item['match_time'])){
                            continue;
                        }else if( $max_match_time == $item['match_time']){
                            if( $max_match_num >= $item['num']){
                                continue;
                            }
                        }

                        $detail_url = str_replace(['date','morder'],[$date,$item['num']],$this->more_url);
                        $rate_detail = QueryList::get($detail_url);

                        $total_html = $rate_detail->find('.jnm:eq(0) .zk_1')->html();
                        $half_html = $rate_detail->find('.jnm:eq(1) .zk_1')->html();
                        //比分赔率
                        $score_rules = $this->getScoreRules();
                        //总进球赔率
                        $total_score_rules = $this->getTotalScoreRules();
                        //半场
                        $half_ground_rules = $this->getHalfRules();
                        //比分数据
                        $score_res = $rate_detail->rules($score_rules)->query()->getData();

                        //总进球数
                        $total_res = QueryList::html($total_html)->rules($total_score_rules)->query()->getData();

                        //半场数据
                        $half_res = QueryList::html($half_html)->rules($half_ground_rules)->query()->getData();





                        if(isset($item['host_team_rank']) && !preg_match('/-/',$item['host_team_rank'])){
                            $item['host_team_rank'] = str_replace(['[',']'],'',$item['host_team_rank']);
                        }else{
                            $item['host_team_rank'] = 0;
                        }
                        if( isset($item['guest_team_rank']) && !preg_match('/-/',$item['guest_team_rank'])){
                            $item['guest_team_rank'] = str_replace(['[',']'],'',$item['guest_team_rank']);
                        }else {
                            $item['guest_team_rank'] = 0;
                        }
                        if(isset( $item['give_score'])){
                            $item['give_score'] = str_replace('+','',$item['give_score']);
                        }
                        $create_time = Carbon::now()->toDateTimeString();
                      //胜平负的数据
                        $comm_data = $item;
                        $comm_data['created_at'] = $create_time;
                        unset($comm_data['data_end']);
                        unset($comm_data['match_code']);
                        //插入主表数据
                        $aoke_id = \App\Models\Aoke::insertGetId($comm_data);
                        if( $aoke_id){

                            //胜平负数据
                            $win_and_fail_data = $this->getWinAndRate($item,$aoke_id,$create_time);
                            //插入胜平负数据
                            $win_and_fail_res = AokeWinAndFail::insert($win_and_fail_data);

                            //总进球的数据
                            $total_score_data = $this->getTotalScoreData($total_res,$aoke_id,$item['match_id'],$create_time);
                            //插入总进球数数据
                            $total_score_res = AokeTotalScore::insert($total_score_data);
                            //比分数据
                            $match_score_data = $this->getMatchScoreData($score_res,$aoke_id,$item['match_id'],$create_time);
                            //插入比分数据
                            $match_score_res = AokeMatchScore::insert($match_score_data);
                            //半场数据
                            $half_ground_data = $this->getHalfGroundData($half_res,$aoke_id,$item['match_id'],$create_time);
                            //插入半场数据
                            $half_ground_res = AokeHalfGround::insert($half_ground_data);

                            if( $win_and_fail_res && $total_score_res && $match_score_res && $half_ground_res){
                                $this->info('数据插入成功，match_id=' . $item['match_id'] . '比赛日期为:' . $item['match_time']);
                                $this->success_num++;
                            }else {
                                $this->info('插入失败,比赛id为'.$item['match_id']);
                                $this->fail_num++;
                            }

                        }
                  }
              }
          }
        }
    }

    //胜平负规则
    public function getOneRules(){
        return $rules = [
            //比赛停止投注状态
            'data_end' =>[
                '.touzhu_1','data-end'
            ],
            //赛事编号
            'num' => [
                '.touzhu_1','data-morder'
            ],
            //matc_id
            'match_id' => [
                '.touzhu_1','data-mid'
            ],
            //赛事当天编号
            'match_code' => [
                'span.xulie','text'
            ],
            //赛事名称
            'competition_name' => [
                'a.saiming','text'
            ],
            'match_time' => [
                '.shijian','title'
            ],
            'host_team_name'=>[
                '.shenpf  .zhu .zhum','text'
            ],
            'host_team_rank' => [
                '.shenpf .zhu .paim','html'
            ],
            'win_rate' => [
                '.shenpf .zhu .peilv','text'
            ],
            'draw_rate' => [
                '.shenpf .ping .peilv','text'
            ],
            'fail_rate' => [
                '.shenpf .fu .peilv','text'
            ],
            'guest_team_name' => [
                '.shenpf  .fu .zhum','text'
            ],
            'guest_team_rank' => [
                '.shenpf .fu .paim','html'
            ],
            //让球
            "give_score" => [
                ".rangqiuspf .zhu .zhud>span","html"
            ],
            'give_score_win_rate' => [
                '.rangqiuspf .zhu .peilv','html'
            ],
            'give_score_draw_rate' => [
                '.rangqiuspf  .ping .peilv','text'
            ],
            'give_score_fail_rate' => [
                '.rangqiuspf  .fu .peilv','text'
            ]
        ];
    }
    //比分规则
    public function getScoreRules(){
        return $score_rules = [
            //比分
            'team_score' =>[
                '.mrfg .float_l .zk_1>div .pingd .peilv','text'
            ],
            'rate' => [
                '.mrfg .float_l .zk_1>div .pingd .peilv_1','text'
            ]
        ];
    }
    //总进球规则
    public function getTotalScoreRules(){
        return $total_score_rules = [
            'total' =>[
                'div.ping','data-wz'
            ],
            'total_rate' => [
                'div.ping','data-sp'
            ]
        ];
    }
    //半场规则
    public function getHalfRules(){
        return $half_ground_rules = [
            'half_res' =>[
                'div.ping .peilv','text'
            ],
            'half_ground_rate' =>[
                'div.ping','data-sp'
            ]
        ];
    }
    //获取胜平负和让球胜平负数据
    public function getWinAndRate($item,$aoke_id,$crate_time){
        return [
            [
                'aoke_id'=> $aoke_id,
                'match_id' => $item['match_id'],
                'give_score' => 0,
                'match_result' => 3,
                'rate' => $item['win_rate'],
                'created_at' => $crate_time
            ],
            [
                'aoke_id'=> $aoke_id,
                'match_id' => $item['match_id'],
                'give_score' => 0,
                'match_result' => 1,
                'rate' => $item['draw_rate'],
                'created_at' => $crate_time
            ],
            [
                'aoke_id'=> $aoke_id,
                'match_id' => $item['match_id'],
                'give_score' => 0,
                'match_result' => 0,
                'rate' => $item['fail_rate'],
                'created_at' => $crate_time
            ],
            [
                'aoke_id'=> $aoke_id,
                'match_id' => $item['match_id'],
                'give_score' => $item['give_score'],
                'match_result' => 3,
                'rate' => $item['give_score_win_rate'],
                'created_at' => $crate_time
            ],
            [
                'aoke_id'=> $aoke_id,
                'match_id' => $item['match_id'],
                'give_score' => $item['give_score'],
                'match_result' => 1,
                'rate' => $item['give_score_draw_rate'],
                'created_at' => $crate_time
            ],
            [
                'aoke_id'=> $aoke_id,
                'match_id' => $item['match_id'],
                'give_score' => $item['give_score'],
                'match_result' => 0,
                'rate' => $item['give_score_fail_rate'],
                'created_at' => $crate_time
            ]
        ];
    }
    //获取总进球的数据
    public function getTotalScoreData($total_res,$aoke_id,$match_id,$create_time){
        $data = [];
        foreach($total_res as $k => $item){
            array_push($data,[
                'score' => $item['total'],
                'rate' => $item['total_rate'],
                'match_id' => $match_id,
                'aoke_id' => $aoke_id,
                'created_at' => $create_time
            ]);
        }
        return $data;
    }
    //比分数据
    public function getMatchScoreData($score_res,$aoke_id,$match_id,$create_time){

        $data = [];
        if(empty($score_res)){
            return [];
        }
        foreach($score_res as $index => $item){
            array_push($data,[
                'score_type'=>$item['team_score'],
                'score_type_id' => $index +1,
                'rate' => $item['rate'],
                'match_id' => $match_id,
                'aoke_id' => $aoke_id,
                'created_at' => $create_time
            ]);
        }
        return $data;

    }
    //获取半场数据
    public function getHalfGroundData($half_res,$aoke_id,$match_id,$create_time){
        $data = [];
        if(empty($half_res)){
            return [];
        }
        foreach($half_res as $index => $item) {
            array_push($data, [
                'half_result_id' => $index + 1,
                'half_result_name' => $item['half_res'],
                'rate' => $item['half_ground_rate'],
                'match_id' => $match_id,
                'aoke_id' => $aoke_id,
                'created_at' => $create_time
            ]);
        }
        return $data;
    }
}
