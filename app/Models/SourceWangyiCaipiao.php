<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SourceWangyiCaipiao extends Model
{
    //
    use SoftDeletes;
    public $table = 'source_wangyicaipiao';
    public $guarded = [];
    protected $appends = ['color1','color2','hope','updateDate','bigScoreColor','rankDiff','rankDiffColor'];

    public function getList($condition){
        $condition['pageSize']  = isset($condition['pageSize']) ? $condition['pageSize'] :  15;
        switch($condition['pageSize']){
            case 15: $pageSize = 15;break;
            case 20: $pageSize = 20;break;
            case 50: $pageSize = 50; break;
            case 100: $pageSize = 100; break;
            default: $pageSize = 15;
        }
        $data = self::orderBy('id','desc')->where(function($query)use($condition){
            if(!empty($condition['competitionName'])){
                $query->where('competition_name','like','%'.$condition['competitionName'].'%');
            }
            if(!empty($condition['teamName'])){
                $query->where('host_team_name','like','%'.$condition['teamName'].'%')->orWhere('guest_team_name','like','%'.$condition['teamName'].'%');
            }
            if(!empty($condition['bettingTime'])){
                $betting_date = Carbon::parse($condition['bettingTime'])->toDateTimeString();
                $query->where('betting_date',$betting_date);
            }
            if(isset($condition['matchStatus']) && $condition['matchStatus'] != 3){
                $query->where('status',$condition['matchStatus']);
            }

            if(isset($condition['totalScore']) && $condition['totalScore'] != 8){
                $query->where('total',$condition['totalScore']);
            }
        })->paginate($pageSize);
        return $data;
    }
    public function getBettingDateList(){
        return self::orderBy('betting_date','desc')->groupBy('betting_date')->select('betting_date')->get()->map(function($item){
            return $item->betting_date = Carbon::parse($item->betting_date)->toDateString();
        });
    }

    /**
     * @return mixed
     *  获取排名差
     */
    public function getRankDiffAttribute(){
        return $this->attributes['rankDiff'] = $this->host_team_rank - $this->guest_team_rank;
    }
    public function getRateHopeTeamAttribute(){
        $arr = [
            1 => $this->win_rate_1,
            2 => $this->draw_rate_1,
            3 => $this->fail_rate_1
        ];

        asort($arr);
        $res = 0;
        foreach($arr as $k => $rate){
            $res = $k;
            break;
        }
        return $this->attributes['rateHopeTeam'] = $res;// 1 主队胜 2 平 3 负
    }
    public function getRankDiffColorAttribute(){
        $diff = abs($this->host_team_rank - $this->guest_team_rank);
        if( $diff > 10){
            $color = 'btn-outline-danger';
        }else if($diff > 5){
            $color = 'btn-outline-warning';
        }else{
            $color = 'btn-outline-primary';
        }
        return $this->attributes['rankDiffColor'] = $color;
    }
    public function getColor1Attribute(){
        if($this->status != 2){
            return $this->attributes['color1'] = 0;
        }
        $arr = [
            1 => $this->win_rate_1,
            2 => $this->draw_rate_1,
            3 => $this->fail_rate_1
        ];
        $pos = $this->match_result;
        return $this->attributes['color1'] = $this->getColor($arr,$pos);
    }
    public function getColor2Attribute(){
        if($this->status != 2){
            return $this->attributes['color2'] = 0;
        }
        $arr = [
            1 => $this->win_rate_2,
            2 => $this->draw_rate_2,
            3 => $this->fail_rate_2
        ];
        $pos = $this->match_give_score_result;
        return $this->attributes['color2'] = $this->getColor($arr,$pos);
    }
    public function getUpdateDateAttribute(){
        return $this->attributes['updateDate'] = Carbon::parse($this->updated_at)->format('m-d H:i');
    }

    /**
     *  大比分差颜色
     * @return string
     */
    public function getBigScoreColorAttribute(){
        if(abs($this->big_score) > 2){
            $color = 'btn-danger';
        }elseif(abs($this->big_score ) > 1){
            $color = 'btn-warning';
        }else{
            $color = 'btn-primary';
        }
        return $this->attributes['bigScoreColor'] = $color;
    }
    public function getHopeAttribute(){
        if($this->status != 2){
            //未比赛
            return $this->attributes['hope'] = 3;
        }
        $arr = [
            1 => $this->win_rate_1,
            2 => $this->draw_rate_1,
            3 => $this->fail_rate_1
        ];
        $rank = [
            0 => $this->host_team_rank,
            1 => $this->guest_team_rank
        ];
        $pos = $this->match_result;
        return $this->attributes['hope'] = $this->hope($rank,$arr,$pos);
    }
    /**
     * 计算出比赛结果对应的颜色
     * @param $arr [1=>xx,2=>xx,3=>xx]
     * @param $pos 实际结果  1，胜 2 平 3 负
     * @return int 1 蓝 2 黄色  3 红色
     */
    public function getColor($arr,$pos){
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
        $colors = [
            ' ',
            'btn btn-sm btn-primary ',
            'btn btn-sm btn-warning ', //text-warning
            'btn btn-sm btn-danger'    //text-danger
        ];
        return $colors[$color];
    }

    /**
     * 通过 赔率 获取是否符合预期
     *  $status = 0 获取 是否符合预期  status = 1 获取 期望赢的队
     */
    public function hope($rank_arr,$rate_arr,$pos = 0){

        asort($rate_arr);
        $hope_res = 0;
        foreach($rate_arr as $k => $v){
            $hope_res = $k;
            break;
        }
        $res = 0;
        if($rank_arr[0] > $rank_arr[1]){
            $res = 1;
        }elseif($rank_arr[0] == $rank_arr[1]){
            $res = 2;
        }else{
            $res = 3;
        }
        if($hope_res == $pos){
            return 1;
        }else{
            return 0;
        }
    }

}
