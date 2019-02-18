<?php

namespace App\Console\Commands;

use App\Models\SourceWangyiCaipiao;
use App\Models\WangyiGuestHistoryScore;
use App\Models\WangyiHostHistoryScore;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

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
    //比赛正文url
    public $content_url = 'http://bisai.caipiao.163.com/match/data.html?';
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
        $list = SourceWangyiCaipiao::where('has_history_score',0)->select('detail_url','id','host_team_name','guest_team_name')->orderBy('id','desc');
        foreach($list->cursor() as $k => $item){
            if(strlen($item->detail_url) > 10){

                $params = $this->getParams($item->detail_url);
                if($params == false){
                    continue;
                }
                $hostId = $params['hostId'];
                $guestId = $params['guestId'];
                $matchId = $params['matchId'];
                unset($params['hostId']);
                unset($params['guestId']);

                $content_url = $this->content_url . http_build_query($params);
                $content = $this->getContent($content_url);

                $res = $this->processData($content,$hostId,$guestId,$matchId,$item->id);
                if($res['code'] == 0){
                    //成功
                    $this->info($res['msg']);
                }elseif($res['code'] == -1){
                    //暂无相关数据
                }elseif($res['code'] == -2){
                    //更新失败
                    $this->info($res['msg']);
                }
                $this->info($item->id.'赛事id');
            }
        }
    }

    /**
     * 获取最终请求所需的参数
     * @param $url
     */
    public function getParams($detail_url){
        $client = new Client();
        $response = $client->request('get',$detail_url);
        $html = $response->getBody();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        if($crawler->filter('.docBody  script')->count() <1){
            $this->info('节点为空');
            return false;
        }
        $script = $crawler->filter('.docBody  script')->text();
        $input = $crawler->filter('#data_recCase')->filter('dd.list')->filter('input')
            ->extract(['value']); //获取联盟id
        $league = implode(',',$input);

        preg_match('/Core.pageData\(\'matchId\', \'(\d+)\'\)/',$script,$match);
        preg_match('/Core.pageData\(\'hostId\', \'(\d+)\'\)/',$script,$host);
        preg_match('/Core.pageData\(\'guestId\', \'(\d+)\'\);/',$script,$guest);
        $matchId = $match[1];
        $hostId = $host[1];
        $guestId = $guest[1];
        $modelId = 'data_recCase';
        $cache = time();
        $data = [
            'cache' => $cache,
            'modelId' => $modelId,
            'matchId' => $matchId,
            'league' => $league,
            'field' => 10,
            'hostId' => $hostId,
            'guestId'=> $guestId
        ];
        return $data;
    }

    /**
     * 获取历史比赛正文内容
     */
    public function getContent($content_url){
        $client = new Client();
        $response = $client->request('get',$content_url);
        $html = $response->getBody();
        return $html;
    }

    /**
     * @param $html
     * @param $realHostId  真正的主队id
     * @param $realGuestId 真正的客队id
     * @param $matchId     比赛id
     * @param $sourceId    source 表对应的id
     */
    public function processData($html,$realHostId,$realGuestId,$matchId,$sourceId){

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $tr_num = $crawler->filter('tr')->count();
        if($tr_num <3){
            $this->info('暂无相关数据');
            return ['code'=>-1,'msg'=>'暂无相关数据'];
        }
        //历史数据
        $dom = $crawler->filter('.u-tb-s02 ')->each(function(Crawler $node ,$i) use($realHostId,$realGuestId,$matchId,$sourceId){
            $tr = $node->filter('tr')->each(function(Crawler $node2 ,$j) use($i,$realHostId,$realGuestId,$matchId,$sourceId){
                //去掉表头，从正文开始
                if($j > 0){
                    $league_name = $node2->filter('td')->eq(0)->text();

                    $date = trim($node2->filter('th')->text());
                    $hostIdStr = $node2->filter('td')->eq(1)->attr('data-fid');
                    $hostId = explode(';',$hostIdStr)[1];


                    $host_name = $node2->filter('td')->eq(1)->filter('a')->text();
                    $scoresStr = $node2->filter('td')->eq(2)->text();
                    $hostScore = explode(':',$scoresStr)[0];
                    $guestScore = explode(':',$scoresStr)[1];

                    $guestIdStr = $node2->filter('td')->eq(3)->attr('data-fid');
                    $guestId = explode(';',$guestIdStr)[1];

                    $guest_name = $node2->filter('td')->eq(3)->filter('a')->text();
                    $result = $node2->filter('td')->eq(4)->text();


                    $res_int = 0;
                    if($result == '胜'){
                        $res_int = 1;
                    }elseif($result == '平'){
                        $res_int = 2;
                    }elseif($result == '负'){
                        $res_int = 3;
                    }

                    $historyData = [
                        'source_id'=>$sourceId,
                        'match_id' => $matchId,
                        'host_team_id' => $hostId, //历史数据中标记的队伍id
                        'guest_team_id'=> $guestId,
                        'host_team_name' => $host_name,
                        'guest_team_name'=> $guest_name,
                        'host_score' => $hostScore,
                        'guest_score' => $guestScore,
                        'match_time' => $date,
                        'league_name' => $league_name,
                        'match_result' => $res_int,
                        'created_at' => Carbon::now()->toDateTimeString()
                    ];
                    //缺 is_host  aim_team_id 字段

                    //主队数据
                    if($i == 0){
                        $aim_team_id = $realHostId;
                        //判断目标队伍的位置
                        if($realHostId == $hostId){
                            $is_host = 0;
                        }else{
                            $is_host = 1;
                        }
                        $historyData['is_host'] = $is_host;
                        $historyData['aim_team_id'] = $aim_team_id;
                        //写入主队历史数据库
                        WangyiHostHistoryScore::insert($historyData);//插入主队数据
                    }
                    //客队
                    if($i == 1){

                        $aim_team_id = $realGuestId;
                        if($realGuestId == $hostId){
                            $is_host = 0;
                        }else{
                            $is_host = 1;
                        }
                        $historyData['is_host'] = $is_host;
                        $historyData['aim_team_id'] = $aim_team_id;
                        //写入客队历史数据库
                        WangyiGuestHistoryScore::insert($historyData);//插入客队数据
                    }

//                echo $league_name.'-'.$date.'-'.$hostId.'-'.$host_name.'-'.$hostScore.':'.$guestScore.'-'.$guestId.'-'.$guest_name.'-'.$result.'-'.$aim_team_id.'-'.$is_host .'-'.$res_int;
                }
            });
        });
        //统计数据
        $host_average = $crawler->filter('.u-tb-s01')->filter('tr')->eq(2)->filter('td')->eq(0)->text();
        $guest_average = $crawler->filter('.u-tb-s01')->filter('tr')->eq(2)->filter('td')->eq(3)->text();
        //修改比赛数据平均值和状态
        $update_status = SourceWangyiCaipiao::where(['id'=>$sourceId])->update(['match_id'=>$matchId,'host_average'=>$host_average,'guest_average'=>$guest_average,'has_history_score'=>1]);
        if($update_status){
            $this->info($matchId.'- success');
            return ['code'=>0,'msg'=>'数据更新成功'];
        }else{
            return ['code'=>-2,'msg'=>'数据更新失败'];
        }
    }
}
