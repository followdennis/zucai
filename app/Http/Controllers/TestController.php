<?php

namespace App\Http\Controllers;

use App\Models\AnalogueInjection;
use App\Models\SourceWangyiCaipiao;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use QL\QueryList;
use Symfony\Component\DomCrawler\Crawler;

class TestController extends Controller
{
    //
    public function index(){

        $now = Carbon::now();
        $fivedata = Carbon::now()->subDays(3);

        echo $now."<br/>";
        echo $fivedata;
        if($now < $fivedata){
            echo 'ok';
        }
        die;

        $data = AnalogueInjection::orderBy('id','desc')->get();
        foreach($data as $k => $v){
            echo $v->match_id;
            foreach($v->host_history_score as $kk => $vv){
               echo  $vv->host_team_name;die;
            }
die;
        }
       die;
        $client = new Client();
        $response = $client->request('get','http://caipiao.163.com/order/jczq-hunhe/#from=leftnav');
        $html =  $response->getBody();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $dom = $crawler->filterXPath('//div[@class="gameSelect"]//dl[contains(@gamedate,"20180721")]/dd[contains(@matchcode,"2018")]');
        foreach($dom as $i => $node){
            $c = new Crawler($node);
            //编号
            echo $c->filterXpath('//span[@class="co1"]')->text();
            echo "&nbsp;&nbsp;";
            //赛事
            echo $c->filterXPath('//span[@class="co2"]')->text();
            echo "&nbsp;&nbsp;";
            //时间
            echo $c->filterXPath('//span[contains(@class,"co3")]')->text();
            echo "&nbsp;&nbsp;";
            // 交战队伍
            echo $c->filterXPath('//span[contains(@class,"co4")]//em[@class="hostTeam"]')->text();
            echo "&nbsp;";
            echo $c->filterXPath('//span[contains(@class,"co4")]//em[@class="guestTeam"]')->text();
            echo "&nbsp;&nbsp;";
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line1"]/em[@class="rq"]')->text();
            echo "赔率";
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line1"]/em[2]')->text();
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line1"]/em[2]')->text();
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line1"]/em[3]')->text();

            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line2"]/em[contains(@class,"rq")]')->text();
            echo "赔率";
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line2"]/em[2]')->text();
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line2"]/em[2]')->text();
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line2"]/em[3]')->text();

            echo "&nbsp;&nbsp;";
            echo $c->filterXPath('//span[contains(@class,"co6_2")]')->text();
            echo "&nbsp;&nbsp;";
            echo $c->filterXPath('//span[contains(@class,"co7")]')->text();

            echo $i."<br/>";
        }
    }
    public function score(){
        $url = 'http://zx.caipiao.163.com/library/football/match.html?mId=1368658&hId=2228&vId=2233';
        $cache = time();
        $modelId = 'data_recCase';
        $matchId = '';
        $league = '';
        $field = '';
        echo $url;
        $client = new Client();
        $response = $client->request('get',$url);
        echo "<pre>";
        $html = $response->getBody();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $script = $crawler->filter('.docBody  script')->text();
        $input = $crawler->filter('#data_recCase')->filter('dd.list')->filter('input')
        ->extract(['value']); //获取联盟id
        $crawler->clear();
        //获取matchId


        preg_match('/Core.pageData\(\'matchId\', \'(\d+)\'\)/',$script,$match);
        preg_match('/Core.pageData\(\'hostId\', \'(\d+)\'\)/',$script,$host);
        preg_match('/Core.pageData\(\'guestId\', \'(\d+)\'\);/',$script,$guest);
        $matchId = $match[1];
        $realHostId = $host[1];
        $realGuestId = $guest[1];

        $league = implode(',',$input);
        $url2 = 'http://bisai.caipiao.163.com/match/data.html?cache='.$cache.'&modelId='.$modelId.'&matchId='.$matchId.'&league='.urlencode($league).'&field=10';

        $html2 = $client->request('get',$url2)->getBody();
        echo $html2;
        $crawler = new Crawler();

        //修改比赛表  match_id
        $crawler->addHtmlContent($html2);
        //历史数据
        $dom = $crawler->filter('.u-tb-s02 ')->each(function(Crawler $node ,$i) use($realHostId,$realGuestId,$matchId){

            $tr = $node->filter('tr')->each(function(Crawler $node2 ,$j) use($i,$realHostId,$realGuestId,$matchId){


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
                //主队数据
                if($i == 0){
                    $aim_team_id = $realHostId;
                    //判断目标队伍的位置
                    if($realHostId == $hostId){
                        $is_host = 0;
                    }else{
                        $is_host = 1;
                    }
                    //写入主队历史数据库
                }
                //客队
                if($i == 1){

                    $aim_team_id = $realGuestId;
                    if($realGuestId == $hostId){
                        $is_host = 0;
                    }else{
                        $is_host = 1;
                    }
                    //写入客队历史数据库
                }


//                echo $league_name.'-'.$date.'-'.$hostId.'-'.$host_name.'-'.$hostScore.':'.$guestScore.'-'.$guestId.'-'.$guest_name.'-'.$result.'-'.$aim_team_id.'-'.$is_host .'-'.$res_int;

               }



            });
        });
        //统计数据
        $host_average = $crawler->filter('.u-tb-s01')->filter('tr')->eq(2)->filter('td')->eq(0)->text();
        $guest_average = $crawler->filter('.u-tb-s01')->filter('tr')->eq(2)->filter('td')->eq(3)->text();

        //修改比赛数据平均值和状态
        echo $host_average .'-' . $guest_average;die;
    }
    public function getNumber(){
        $str = 'aaabbbv321';
        preg_match('/\d+/',$str,$out);
        print_r($out);
        $a = [
            0 => 'aaa',
            1 => 'bbb'
        ];
        $b = [
            2 => 'cc',
            3 => 'dd'
        ];
        $t1 = '2008-02-12 11:33:20';
        $today = Carbon::parse($t1)->startOfDay();

        $start = Carbon::parse($today)->addHours(12);
        if(strtotime($t1) > strtotime($start)){
            echo $today;
        }else{
            echo Carbon::parse($today)->subDay();
        }
        $a = '2.3';
        $b = '1.2';
        $c = '3.8';
        $arr = [
            1 =>$a,
            2 =>$b,
            3 =>$c
        ];

        $new = $this->hope($arr,2);
        echo "<br/>";
        print_r($new);
        echo '<hr/>';
        $data = SourceWangyiCaipiao::where('status',2)->first();
        echo $data->color1;
        echo "<br/>";
        echo $data->id;
        echo $data->competition_name;
        echo $data->match_time;

    }
    public function aoke(){
        $client = new Client();
        $response = $client->request('get','http://www.okooo.com/jingcai/');
        $html =  $response->getBody();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $dom = $crawler->filter('div.cont');
        foreach($dom as $node){
            $c1 = new Crawler($node);

            $content = $c1->filter('.cont_1 .riqi .time')->html();
            echo $content;
            die;
        }

    }
    public function hope($arr,$pos = 1){
        asort($arr);
        $i = 1;
        $color = 0;
        foreach($arr as $k => $v){

            if($k == $pos){

                $color = $i;
                break;

            }
            $i++;
        }
        return $color;
    }

    /**
     * 常用内容测试
     */
    public function test(){
        echo "test<br/>";

        $client = new Client();
//        $response = $client->request('get','http://bisai.caipiao.163.com/match/data.html?cache=1535184705081&modelId=data_recHis&matchId=2721965&league=110%2C577%2C109&field=10');
//        $response = $client->request('get','http://zx.caipiao.163.com/library/football/match.html?mId=1398732&hId=290&vId=216');

//        $response = $client->request('get','http://bisai.caipiao.163.com/match/data.html?cache='.time().)
        $url = '';

            echo microtime(true);
            echo "变量解析";
            echo "<hr>";
            //有效cookie
            $str1= "_ga=GA1.2.1495388222.1529303494; LastUrl=; FirstURL=www.okooo.com/; FirstOKURL=http%3A//www.okooo.com/jingcai/; First_Source=www.okooo.com; __utmz=56961525.1551359810.47.13.utmcsr=baidu|utmccn=(organic)|utmcmd=organic; Hm_lvt_5ffc07c2ca2eda4cc1c4d8e50804c94b=1550909400,1551359810,1551520677,1551577119; __utmc=56961525; PHPSESSID=2d054a26a48a12dfb494691a9e7f34ced48840d0; pm=; __utma=56961525.1495388222.1529303494.1551577119.1551577123.51; LStatus=N; LoginStr=%7B%22welcome%22%3A%22%u60A8%u597D%uFF0C%u6B22%u8FCE%u60A8%22%2C%22login%22%3A%22%u767B%u5F55%22%2C%22register%22%3A%22%u6CE8%u518C%22%2C%22TrustLoginArr%22%3A%7B%22alipay%22%3A%7B%22LoginCn%22%3A%22%u652F%u4ED8%u5B9D%22%7D%2C%22tenpay%22%3A%7B%22LoginCn%22%3A%22%u8D22%u4ED8%u901A%22%7D%2C%22qq%22%3A%7B%22LoginCn%22%3A%22QQ%u767B%u5F55%22%7D%2C%22weibo%22%3A%7B%22LoginCn%22%3A%22%u65B0%u6D6A%u5FAE%u535A%22%7D%2C%22renren%22%3A%7B%22LoginCn%22%3A%22%u4EBA%u4EBA%u7F51%22%7D%2C%22baidu%22%3A%7B%22LoginCn%22%3A%22%u767E%u5EA6%22%7D%2C%22weixin%22%3A%7B%22LoginCn%22%3A%22%u5FAE%u4FE1%u767B%u5F55%22%7D%2C%22snda%22%3A%7B%22LoginCn%22%3A%22%u76DB%u5927%u767B%u5F55%22%7D%7D%2C%22userlevel%22%3A%22%22%2C%22flog%22%3A%22hidden%22%2C%22UserInfo%22%3A%22%22%2C%22loginSession%22%3A%22___GlobalSession%22%7D; Hm_lpvt_5ffc07c2ca2eda4cc1c4d8e50804c94b=1551586270; __utmb=56961525.30.9.1551586271777";
            //无效cookie
            $str2 = "_ga=GA1.2.1495388222.1529303494; LastUrl=; FirstURL=www.okooo.com/; FirstOKURL=http%3A//www.okooo.com/jingcai/; First_Source=www.okooo.com; __utmz=56961525.1551359810.47.13.utmcsr=baidu|utmccn=(organic)|utmcmd=organic; Hm_lvt_5ffc07c2ca2eda4cc1c4d8e50804c94b=1550909400,1551359810,1551520677,1551577119; __utmc=56961525; PHPSESSID=2d054a26a48a12dfb494691a9e7f34ced48840d0; pm=; __utma=56961525.1495388222.1529303494.1551577119.1551577123.51; LStatus=N; LoginStr=%7B%22welcome%22%3A%22%u60A8%u597D%uFF0C%u6B22%u8FCE%u60A8%22%2C%22login%22%3A%22%u767B%u5F55%22%2C%22register%22%3A%22%u6CE8%u518C%22%2C%22TrustLoginArr%22%3A%7B%22alipay%22%3A%7B%22LoginCn%22%3A%22%u652F%u4ED8%u5B9D%22%7D%2C%22tenpay%22%3A%7B%22LoginCn%22%3A%22%u8D22%u4ED8%u901A%22%7D%2C%22qq%22%3A%7B%22LoginCn%22%3A%22QQ%u767B%u5F55%22%7D%2C%22weibo%22%3A%7B%22LoginCn%22%3A%22%u65B0%u6D6A%u5FAE%u535A%22%7D%2C%22renren%22%3A%7B%22LoginCn%22%3A%22%u4EBA%u4EBA%u7F51%22%7D%2C%22baidu%22%3A%7B%22LoginCn%22%3A%22%u767E%u5EA6%22%7D%2C%22weixin%22%3A%7B%22LoginCn%22%3A%22%u5FAE%u4FE1%u767B%u5F55%22%7D%2C%22snda%22%3A%7B%22LoginCn%22%3A%22%u76DB%u5927%u767B%u5F55%22%7D%7D%2C%22userlevel%22%3A%22%22%2C%22flog%22%3A%22hidden%22%2C%22UserInfo%22%3A%22%22%2C%22loginSession%22%3A%22___GlobalSession%22%7D; Hm_lpvt_5ffc07c2ca2eda4cc1c4d8e50804c94b=1551586202; __utmb=56961525.23.9.1551586203771";

            $res1 = $this->parseParams($str1);
            $res2 = $this->parseParams($str2);
            echo "<pre>";
            print_r($res1);
            print_r($res2);

            foreach($res1 as $k =>$v){

                if($v != $res2[$k]){
                    echo "不相等的项";
                    echo "<br/>" . $k ."<br/>";
                    echo $v;
                    echo "<br/>";
                    echo $res2[$k];

                }
            }
            echo "<br/>";
        $url = "http://www.okooo.com/soccer/match/1029611/history/";
          $sql = QueryList::get($url)->find('*')->html();
        $encode = mb_detect_encoding($sql, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        echo $encode;
        print_r(mb_convert_encoding($sql, 'UTF-8', $encode));

//        $html = $response->getBody();
//        echo $html;
    }

    public function parseParams($str){
        $parse_arr = explode(";",$str);
        $res = [];
        foreach($parse_arr as $key => $v){
            $item = explode("=",$v);
            $res[$item[0]] = $item[1];
        }
        return $res;
    }
}
