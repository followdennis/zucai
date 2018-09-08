<?php

namespace App\Service;

use App\Models\SourceWangyiCaipiao;
use Illuminate\Database\Eloquent\Model;

class AnalysisService extends Model
{
    //获取分析数据
    public function analysis($item_id){
        $item = SourceWangyiCaipiao::where('id',$item_id)->first();
        $arr = [];
        $host_score = [];
        $guest_score = [];

        $host_total = [];
        $guest_total = [];

        $host_times = [
            'win' =>0,
            'draw'=>0,
            'fail'=>0,
        ];
        $guest_times = [
            'win' => 0,
            'draw'=> 0,
            'fail'=> 0,
        ];
        $host_result = [];
        $guest_result = [];
        foreach($item->host_history_score()->orderBy('match_time','asc')->get() as $k => $host){
            if($host->aim_team_id == $host->host_team_id){
                array_push($host_score,$host->host_score); //目标队伍进球数
            }
            if($host->aim_team_id == $host->guest_team_id){
                array_push($host_score,$host->guest_score);
            }
            array_push($host_total,($host->host_score + $host->guest_score));//进球之和
            //主队 胜平负比例
            if($host->match_result == 1){
                $host_times['win']++;
            }elseif($host->match_result == 2){
                $host_times['draw']++;
            }elseif($host->match_result == 3){
                $host_times['fail']++;
            }
            array_push($host_result,(4-$host->match_result));//比赛结果 重定义 3表示胜 2表示平 1表示负
        }
        foreach($item->guest_history_score()->orderBy('match_time','asc')->get() as $k => $guest){
            if($guest->aim_team_id == $guest->host_team_id){
                array_push($guest_score,$guest->host_score);
            }
            if($guest->aim_team_id == $guest->guest_team_id){
                array_push($guest_score,$guest->guest_score);
            }
            array_push($guest_total,($guest->host_score + $guest->guest_score));
            //客队进球比例
            if($guest->match_result == 1){
                $guest_times['win']++;
            }else if($guest->match_result == 2){
                $guest_times['draw']++;
            }elseif($guest->match_result == 3){
                $guest_times['fail']++;
            }
            array_push($guest_result,(4-$guest->match_result));
        }
        $total_score = array_map(function($score1,$score2){
            return $score1 + $score2;
        },$host_score,$guest_score);

        $history_total= array_map(function($score1,$score2){
            return $score1 + $score2;
        },$host_total,$guest_total);

        $arr['host_math'] = $this->variance($host_score);  //variance  标准差  square 方差  average 平均数
        $arr['guest_math'] = $this->variance($guest_score);

        $arr['host_score'] = implode(',',$host_score);//主队进球
        $arr['guest_score'] = implode(',',$guest_score);//客队进球
        $arr['total_score'] = implode(',',$total_score);

        $arr['host_total_average'] = round(array_sum($host_total)/count($host_total),2);
        $arr['guest_total_average'] = round(array_sum($guest_total)/count($guest_total),2);
        $arr['host_total'] = implode(',',$host_total);  //主队历史交战两队总进球
        $arr['guest_total'] = implode(',',$guest_total);
        $arr['history_total'] = implode(',',$history_total);

        $arr['host_times'] = $host_times;
        $arr['guest_times'] = $guest_times;
        $arr['data'] = $item;

        $arr['host_result'] = implode(',',$host_result);
        $arr['guest_result'] = implode(',',$guest_result);

        return $arr;
    }

    /**
     * 计算方差，标准差，平均值 2018-09-07 22:50:22
     */
    public function variance($arr) {
        $length = count($arr);
        if ($length == 0) {
            return ['variance'=>0,'square'=>0,'average'=>0];
        }
        $average = array_sum($arr)/$length;
        $count = 0;
        foreach ($arr as $v) {
            $count += pow($average-$v, 2);
        }
        $variance = $count/$length;
        return ['variance' => $variance, 'square' => sprintf('%.2f',sqrt($variance)), 'average' => $average];
    }
}
