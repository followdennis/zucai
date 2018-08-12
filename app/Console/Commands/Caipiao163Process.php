<?php

namespace App\Console\Commands;

use App\Models\SourceWangyiCaipiao;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use QL\Dom\Query;
use QL\QueryList;
use Symfony\Component\DomCrawler\Crawler;

class Caipiao163Process extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:163process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $url = 'http://caipiao.163.com/award/jczqspfp.html?category=all&selectedDate=';

    protected $win = [
        '胜' => 1,
        '平' => 2,
        '负' => 3,
        '其他' => 0,
        '-' => 4   //表示 没有非让球的选项
    ];
    protected $give_win = [
        '让球胜' => 1,
        '让球平' => 2,
        '让球负' => 3,
        '其他' => 0 ,
        '-' => 4
    ];
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
        $this->info('源数据处理');
        //获取一条未结束的比赛
        $now = Carbon::yesterday()->addHours(12);
        $this->info($now);
        $data = SourceWangyiCaipiao::where('betting_date','<',$now)->where('status',0)->orderBy('betting_date','desc')->groupBy('betting_date')->select('betting_date');
        if($data->count() == 0){
            $this->info('库中暂无 需要处理的数据');
        }
        foreach($data->cursor() as $item){
//            $date = Carbon::parse($item->match_time)->subHours(12)->toDateString();
            $date = Carbon::parse($item->betting_date)->toDateString();

            $url = $this->url.$date;

            $data = $this->all($url);

            foreach($data['team'] as $k => $team){

                $source_item = SourceWangyiCaipiao::where('status',0)
                    ->where([
                        'match_number'=>$team['match_number'],
                        'competition_name' => $team['competition_name'],
                        'host_team_name' => $team['host_team_name'],
                        'guest_team_name' => $team['guest_team_name']
                    ])
                    ->first();
                if(!empty($source_item)){
                    if(preg_match('/-/', $data['total_score'][$k]['total'])){
                        $this->info($team['competition_name'] . $team['match_number'] .'没出结果');
                    }else{
                        if(preg_match('/-/',$data['win'][$k]['win'])){
                            $data['win'][$k]['win_rate'] = 0;
                        }
                        if(preg_match('/-/',$data['give_score'][$k]['give_score_win'])){
                            $data['give_score'][$k]['give_score_rate'] = 0;
                        }
                        $source_item->status = 2;
                        $source_item->host_team_score = $data['result'][$k]['host_score'];
                        $source_item->guest_team_score = $data['result'][$k]['guest_score'];
                        $source_item->match_result = $this->win[$data['win'][$k]['win']];
                        $source_item->final_rate = $data['win'][$k]['win_rate'];
                        $source_item->match_give_score_result = $this->give_win[$data['give_score'][$k]['give_score_win']];
                        $source_item->final_give_score_rate = $data['give_score'][$k]['give_score_rate'];
                        $source_item->total = $data['total_score'][$k]['total'];
                        $source_item->total_rate = $data['total_score'][$k]['total_rate'];
                        //分差
                        $source_item->big_score = intval($data['result'][$k]['host_score'] - $data['result'][$k]['guest_score']);
                        $source_item->save();
                        $this->info('success--'.$k);
                    }
                }

            }
        }
    }
    public function all($url){
        $team = $this->getHtml($url);//  ['match_number','competition_name','match_time'=>'17:00','host_team_name','guest_team_name']
        $win = $this->getWin($url); // ['win'=>'平','win_rate'=>2.90]
        $give_score = $this->giveScore($url); // ['give_score_win'=>'让球胜','give_score_rate' => 2.04]
        $total_score = $this->totalScore($url); //['total'=> 0,'total_rate'=>15]
        $half_match = $this->halfMatch($url); // ['half_match'=>'平平','half_rate' => 6]
        $result = $this->result($url); // ['host_score'=>0,'guest_score'=>0]
        return ['team' =>$team,'win'=>$win,'give_score'=>$give_score,'total_score' =>$total_score,'half_match'=>$half_match,'result'=>$result];
    }

    public function getHtml($url = 'http://caipiao.163.com/award/jczqspfp.html?category=all&selectedDate=2018-07-22'){
        $data = QueryList::get($url)->rules([
            'match_info'=>[
                '.ss_list tbody tr',
                'html'
            ],
        ])->query()->getData(function($x){
            $crawler = new Crawler();
            $crawler->addHtmlContent($x['match_info']);
            $match_number = $crawler->filter('td')->eq(0)->text();
            $competition_name = $crawler->filter('td')->eq(1)->text();
            $match_time = $crawler->filter('td')->eq(2)->text();
            $host_team_name = $crawler->filter('td')->eq(3)->text();
            $guest_team_name = $crawler->filter('td')->eq(4)->text();
            preg_match('/\d+/',$match_number,$out);
            $x = [
                'match_number'=>trim($out[0]),
                'competition_name'=>$competition_name,
                'match_time'=>$match_time,
                'host_team_name'=>trim($host_team_name),
                'guest_team_name'=>trim($guest_team_name)
            ];

            return $x;
        });

        return $data->all();
    }

    //获取胜平负
    public function getWin($url = 'http://caipiao.163.com/award/jczqspfp.html?category=all&selectedDate=2018-07-22'){
        $data = QueryList::get($url)->rules([

            'win_fail'=>[
                '.bf_detail .zqdc_table:eq(0) tbody tr',
                'html'
            ],

        ])->query()->getData(function($x){
            $crawler = new Crawler();
            $crawler->addHtmlContent($x['win_fail']);
            $win = $crawler->filter('td')->eq(0)->text();
            $win_rate = $crawler->filter('td')->eq(1)->text();
//            if(preg_match('/-/',$win)){
//                $win = '其他';
//            }
            $x = [
                'win'=>$win,
                'win_rate'=>$win_rate,
            ];

            return $x;
        });

        return $data->all();
    }
    //让球
    public function giveScore($url = 'http://caipiao.163.com/award/jczqspfp.html?category=all&selectedDate=2018-07-22'){
        $data = QueryList::get($url)->rules([

            'give_score'=>[
                '.bf_detail .zqdc_table:eq(1) tbody tr',
                'html'
            ],
        ])->query()->getData(function($x){
            $crawler = new Crawler();
            $crawler->addHtmlContent($x['give_score']);
            $win = $crawler->filter('td')->eq(0)->text();
            $win_rate = $crawler->filter('td')->eq(1)->text();
            if(preg_match('/-/',$win)){
                $win = '其他';
            }
            $x = [
                'give_score_win'=>$win,
                'give_score_rate'=>$win_rate,
            ];

            return $x;
        });

        return $data->all();
    }
    //总进球
    public function totalScore($url = 'http://caipiao.163.com/award/jczqspfp.html?category=all&selectedDate=2018-07-22'){
        $data = QueryList::get($url)->rules([

            'total'=>[
                '.bf_detail .zqdc_table:eq(2) tbody tr',
                'html'
            ],

        ])->query()->getData(function($x){
            $crawler = new Crawler();
            $crawler->addHtmlContent($x['total']);
            $win = $crawler->filter('td')->eq(0)->text();
            $win_rate = $crawler->filter('td')->eq(1)->text();
            //如果进球数为 7+
            $win = str_replace('+','',$win);
            $x = [
                'total'=>$win,
                'total_rate'=>$win_rate,
            ];
            return $x;
        });

        return $data->all();
    }
    //半场
    public function halfMatch($url = 'http://caipiao.163.com/award/jczqspfp.html?category=all&selectedDate=2018-07-22'){
        $data = QueryList::get($url)->rules([

            'half_match'=>[
                '.bf_detail .zqdc_table:eq(3) tbody tr',
                'html'
            ]
        ])->query()->getData(function($x){
            $crawler = new Crawler();
            $crawler->addHtmlContent($x['half_match']);
            $win = $crawler->filter('td')->eq(0)->text();
            $win_rate = $crawler->filter('td')->eq(1)->text();

            $x = [
                'half_match'=>$win,
                'half_rate'=>$win_rate,
            ];
            return $x;
        });

        return $data->all();
    }
    //半场
    public function result($url = 'http://caipiao.163.com/award/jczqspfp.html?category=all&selectedDate=2018-07-22'){
        $data = QueryList::get($url)->rules([

            'result'=>[
                '.bf_detail .zqdc_table:eq(4) tbody tr',
                'html'
            ],
        ])->query()->getData(function($x){
            $crawler = new Crawler();
            $crawler->addHtmlContent($x['result']);
            $win = $crawler->filter('td')->eq(0)->text();
            $res = explode(':',$win);

            //如果出现胜其他的情况
            if(count($res) < 2){
                $host_score = 10;
                $guest_score = 10;
            }else{
                $host_score = @$res[0];
                $guest_score = @$res[1];
            }
            $x = [
                'host_score'=> $host_score,
                'guest_score'=> $guest_score,
            ];
            return $x;
        });

        return $data->all();
    }
    public function getHtml3($url = 'http://caipiao.163.com/award/jczqspfp.html?category=all&selectedDate=2018-07-22'){
        $client = new Client();
        $response = $client->get($url);
        $html =  $response->getBody();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $crawler->filter('.ss_list tbody tr')->each(function(Crawler $node,$i){
            $name = $node->filter('td')->eq(0)->text();

            echo $name;
        });
    }

}
