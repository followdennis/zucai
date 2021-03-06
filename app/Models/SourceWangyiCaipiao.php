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
    protected $appends = ['color1','color2','hope','updateDate','bigScoreColor','rankDiff','rankDiffColor','isOpposite','average_res','total_average','total_average_diff'];

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
            if(!empty($condition['matchResult'])){
                $query->where('match_result',$condition['matchResult']);
            }
        })->paginate($pageSize);
        return $data;
    }

    /**
     * 通过id 数组获取数据
     * @return mixed
     */
    public function getDataByIdArr($IdArr = []){
        return self::whereIn('id',$IdArr)->get();
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
        return $this->attributes['rankDiff'] = preg_match('/\d+/',$this->host_team_rank) - preg_match('/\d+/',$this->guest_team_rank);
    }

    /**
     * @return int|string
     * 获取赔率与排名相反的数据
     */
    public function getIsOppositeAttribute(){
        $rank_num = 0;
        $rate_num = 0;
        $rank_result = preg_match('/\d+/',$this->host_team_rank) - preg_match('/\d+/',$this->guest_team_rank);

        if($rank_result < -5 ){
            $rank_num = 1;
        }elseif($rank_result == 0){
            $rank_num = 2;
        }elseif($rank_result > 5){
            $rank_num = 3;
        }else{
            $rank_num = 0;
        }

        $rate_num = $this->getHopeWinByRate();

        if(($rate_num + $rank_num) == 4){
           $is_opposite = 1;
        }else{
            $is_opposite = 0;
        }
        return $this->attributes['isOpposite'] = $is_opposite;
    }
    public function getRateHopeTeamAttribute(){
       $res = $this->getHopeWinByRate();
        return $this->attributes['rateHopeTeam'] = $res;// 1 主队胜 2 平 3 负
    }

    public function getHopeWinByRate(){
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
        return $res;
    }

    public function getRankDiffColorAttribute(){
        $diff = abs(preg_match('/\d+/',$this->host_team_rank) - preg_match('/\d+/',$this->guest_team_rank));
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
            0 => preg_match('/\d+/',$this->host_team_rank),
            1 => preg_match('/\d+/',$this->guest_team_rank)
        ];
        $pos = $this->match_result;
        return $this->attributes['hope'] = $this->hope($rank,$arr,$pos);
    }

    public function getCompetitions(){
        return self::groupBy('competition_name')->select('competition_name')->get();
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
    /**
     * 通过平均进球，进行比赛推荐
     */
    public function getAverageResAttribute(){
        $diff = $this->host_average - $this->guest_average;
        $res = 0;
        if($diff > 0.2){
            $res = 1;
        }elseif($diff >= -0.2){
            $res = 2;
        }else if($diff < -0.2){
            $res = 3;
        }
        return $this->attributes['average_res'] = $res;
    }

    /**
     * 获取平均进球数
     */
    public function getTotalAverageAttribute(){
        return $this->attributes['total_average'] = $this->host_average + $this->guest_average;
    }

    /**
     *  总进球的平均值 与结果的差
     */
    public function getTotalAverageDiffAttribute(){
        if($this->status == 2){
            $diff = abs($this->total_average - $this->total);

            if($diff <= 0.5){
                return $this->attributes['total_average_diff'] = 1; //差值小于等于0.5
            }
            return $this->attributes['total_average_diff'] = 0;
        }
        return $this->attributes['total_average_diff'] = 0;
    }
    public function host_history_score(){
        return $this->hasMany('App\Models\WangyiHostHistoryScore','source_id','id');
    }
    public function guest_history_score(){
        return $this->hasMany('App\Models\WangyiGuestHistoryScore','source_id','id');
    }
}
