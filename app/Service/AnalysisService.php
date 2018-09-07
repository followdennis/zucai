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
        foreach($item->host_history_score as $k => $host){
            if($host->aim_team_id == $host->host_team_id){
                array_push($host_score,$host->host_score);
            }
            if($host->aim_team_id == $host->guest_team_id){
                array_push($host_score,$host->guest_score);
            }
            array_push($host_total,($host->host_score + $host->guest_score));
        }
        foreach($item->guest_history_score as $k => $guest){
            if($guest->aim_team_id == $guest->host_team_id){
                array_push($guest_score,$host->host_score);
            }
            if($guest->aim_team_id == $guest->guest_team_id){
                array_push($guest_score,$guest->guest_score);
            }
            array_push($guest_total,($guest->host_score + $guest->guest_score));
        }
        $total_score = array_map(function($score1,$score2){
            return $score1 + $score2;
        },$host_score,$guest_score);

        $history_total= array_map(function($score1,$score2){
            return $score1 + $score2;
        },$host_total,$guest_total);

        $arr['host_score'] = implode(',',$host_score);
        $arr['guest_score'] = implode(',',$guest_score);
        $arr['total_score'] = implode(',',$total_score);

        $arr['host_total'] = implode(',',$host_total);
        $arr['guest_total'] = implode(',',$guest_total);
        $arr['history_total'] = implode(',',$history_total);
        $arr['data'] = $item;

        return $arr;
    }
}
