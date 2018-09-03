<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WangyiHostHistoryScore extends Model
{
    //
    protected $table = 'wangyi_host_history_score';
    public $guarded = [];
    public $appends = ['date','color','host_score_color','guest_score_color'];
    public function getDateAttribute(){
        return $this->attributes['date'] = Carbon::parse($this->match_time)->format('m-d');
    }

    /**
     * 根据比赛的结果情况，返回颜色
     */
    public function getColorAttribute(){
        $color = '';
        if($this->match_result == 1){
            $color = 'text-danger';
        }else if($this->match_result == 2){
            $color = 'text-warning';
        }else if($this->match_result == 3){
            $color = 'text-dark';
        }
        return $this->attributes['color'] = $color;
    }

    /**
     *  主队比分的颜色
     */
    public function getHostScoreColorAttribute(){
        $color = '';
        if($this->aim_team_id == $this->host_team_id){
            $color = 'text-white';
        }else{
            $color = 'text-secondary';
        }
        return $this->attributes['host_score_color']  = $color;
    }
    /**
     * 从队颜色
     */
    public function getGuestScoreColorAttribute(){
        $color = '';
        if($this->aim_team_id == $this->guest_team_id){
            $color = 'text-white';
        }else{
            $color = 'text-secondary';
        }
        return $this->attributes['guest_score_color'] = $color;
    }
}
