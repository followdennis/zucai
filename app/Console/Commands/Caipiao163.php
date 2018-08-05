<?php

namespace App\Console\Commands;

use App\Models\SourceWangyiCaipiao;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Mockery\Exception;
use phpDocumentor\Reflection\DocBlock\Tags\Source;
use QL\QueryList;
use Symfony\Component\DomCrawler\Crawler;

class Caipiao163 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:163';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'wangyicaipiao spider';

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
    public function handle(){
        $this->info('网易彩票');
        $url = 'http://caipiao.163.com/order/jczq-hunhe/#from=leftnav';
        $this->getList($url);
        $this->info('抓取结束!');
    }
    public function getList( $url = null)
    {
        $i =0;
        $match_time = SourceWangyiCaipiao::orderBy('id','desc')->select('match_time')->first();
        $match_time = empty($match_time) ? '2000-01-01 12:00:00':$match_time->match_time;
        $max_time = strtotime($match_time);
        $data = QueryList::get($url)->rules([
            'match_number'=>[
                'div.gameSelect dl dd span.co1',
                'text'
            ],
            'competition_name'=>[
                'span.co2',
                'text'
            ],
            'match_time'=>[
                'span.co3 .jtip',
                'inf',
            ],
            'host_team_name'=>[
                'em.hostTeam b',
                'text'
            ],
            'host_team_rank'=>[
                'em.hostTeam i',
                'text'
            ],
            'guest_team_name'=>[
                'em.guestTeam b',
                'text'
            ],
            'guest_team_rank'=>[
                'em.guestTeam i',
                'text'
            ],
            'give_score_1' =>[
                '.co6_1 div.line1',
                'html'
            ],
            'give_score_2'=>[
                'span.co6_1 div.line2',
                'html'
            ],
        ])->query()->getData(function($x) use($max_time,$i){
            try{
                $crawler = new Crawler();
                $html = $x['give_score_1'];
                $crawler->addHtmlContent($html);
                if($crawler->filter('em')->count()){
                    $x['win_rate_1'] = $crawler->filter('em')->eq(1)->text();
                    $x['draw_rate_1'] = $crawler->filter('em')->eq(2)->text();
                    $x['fail_rate_1'] = $crawler->filter('em')->eq(3)->text();
                    $x['give_score_1'] = $crawler->filter('em.rq')->text();
                }else{
                    $x['win_rate_1'] = 0;
                    $x['draw_rate_1'] = 0;
                    $x['fail_rate_1'] = 0;
                    $x['give_score_1'] = 0;
                }

                $crawler->clear();
                $crawler = new Crawler();
                $crawler->addHtmlContent($x['give_score_2']);
                if($crawler->filter('em')->count()){
                    $x['win_rate_2'] = $crawler->filter('em')->eq(1)->text();
                    $x['draw_rate_2'] = $crawler->filter('em')->eq(2)->text();
                    $x['fail_rate_2'] = $crawler->filter('em')->eq(3)->text();
                    $x['give_score_2'] = $crawler->filter('em.rq')->text();
                    $crawler->clear();
                }else{
                    $x['win_rate_2'] = 0;
                    $x['draw_rate_2'] = 0;
                    $x['fail_rate_2'] = 0;
                    $x['give_score_2'] =0;
                }

                if(isset($x['host_team_rank'])){
                    $x['host_team_rank'] = str_replace(['[',']'],'',$x['host_team_rank']);
                }else{
                    $x['host_team_rank'] = 0;
                }
                if(isset($x['guest_team_rank'])){
                    $x['guest_team_rank'] = str_replace(['[',']'],'',$x['guest_team_rank']);
                }else{
                    $x['guest_team_rank'] = 0;
                }
                if(isset($x['match_time'])){
                    $x['match_time'] = substr($x['match_time'],-16);
                    $time = strtotime($x['match_time']);

                    $x['status'] = 0; //比赛状态
                    //筛选去除已入库的数据
                    if($time <=  $max_time){
                        return false;
                    }
                    if($time -5*60 < time()){
                        return false; //赛前五分钟截止
                    }
                    $x['betting_date'] = betting_day($x['match_time']);//获取投注日期
                }
                if(isset($x['give_score_1'])){
                    $x['give_score_1'] = str_replace('+','',$x['give_score_1']);
                }
                if(isset($x['give_score_2'])){
                    $x['give_score_2'] = str_replace('+','',$x['give_score_2']);
                }
                $x['created_at'] = Carbon::now()->toDateTimeString();

            }catch (\Exception $e){
                echo $e->getMessage();
            }
            return $x;
        });


        //删除空数据
        $data = array_filter($data->all(),function($item){
            return empty($item) ? false:true;
        });
        if(empty($data)){
            $this->info('没有最新的数据了');
        }else{
            $save_status = SourceWangyiCaipiao::insert($data);
            if($save_status){
                $num = count($data);
                $this->info('数据插入成功,共' .$num. '条数据');
                info('数据插入成功，共 '.$num. '条数据');
            }else{
                $this->info('数据插入失败');
                info('数据插入失败');
            }
        }
    }


}
