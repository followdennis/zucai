<?php

namespace App\Console\Commands;

use App\Models\SourceWangyiCaipiao;
use App\Models\WangyiGuestHistoryScore;
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
        $list = SourceWangyiCaipiao::where('has_history_score',0)->select('detail_url','id','host_team_name','guest_team_name');
        foreach($list->cursor() as $k => $item){
            if(strlen($item->detail_url)){

                $params = $this->getParams($item->detail_url);
                $content_url = $this->content_url . $params;

                $content = $this->getContent($content_url);
                echo $content;die;
                $this->info($item->match_id);
                die;
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
            'field' => 10
        ];

        return http_build_query($data);
    }

    /**
     * 获取历史比赛正文内容
     */
    public function getContent($content_url){
        $client = new Client();
        $response = $client->request('get',$content_url);
        $html = $response->getBody();
        echo $html;
    }
}
