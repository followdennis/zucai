<?php

namespace App\Console\Commands;

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
        $ql = QueryList::get($url)->encoding('UTF-8','GB2312');

        $htmls = $ql->find('.cont')->htmls();

        $this->info('开始,共'.count($htmls) .'天的数据');

        $rules = $this->getOneRules();
        foreach($htmls as $index => $html){
            $match_dom = QueryList::html($html);
            $time = $match_dom->find('.riqi .time')->attr('data-time');
            $datetime = date('Y-m-m H:i:s',$time);//当天比赛的日期
            $date = date('Y-m-d',$time);
           //是否停止投注 1 表示停止
           $data = $match_dom->rules($rules)->query()->getData();

          foreach($data as $key => $item ){
              if(isset( $item['data_end']) && $item['data_end'] == 0){
//                  print_r($item);
                  if( count($item) < 16){
                      $this->info('字段不全');
                      Log::info("字段不全".date() .$item['match_code'] + $item['time']);
                  } else {
                        $detail_url = str_replace(['date','morder'],[$date,$item['data_morder']],$this->more_url);
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

                        print_r($score_res);
                        print_r($total_res);
                        print_r($half_res);
                      die;
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
            //查看更多状态
            'data_morder' => [
                '.touzhu_1','data-morder'
            ],
            //赛事当天编号
            'match_code' => [
                'span.xulie','text'
            ],
            'match_name' => [
                'a.saiming','text'
            ],
            'time' => [
                '.shijian','title'
            ],
            'host_team'=>[
                '.shenpf  .zhu .zhum','text'
            ],
            'host_rank' => [
                '.shenpf .zhu .paim p','text'
            ],
            'sheng' => [
                '.shenpf .zhu .peilv','text'
            ],
            'ping' => [
                '.shenpf .ping .peilv','text'
            ],
            'fu' => [
                '.shenpf .fu .peilv','text'
            ],
            'guest_team' => [
                '.shenpf  .fu .zhum','text'
            ],
            'guest_rank' => [
                '.shenpf .fu .paim p','text'
            ],
            //让球
            'give_score' => [
                '.rangqiuspf .zhu .zhud span','text'
            ],
            'rang_sheng' => [
                '.rangqiuspf .zhu .peilv','text'
            ],
            'rang_ping' => [
                '.rangqiuspf  .ping .peilv','text'
            ],
            'rang_fu' => [
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
            'total_score' =>[
                'div.ping','data-wz'
            ],
            'total_score_rate' => [
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
}
