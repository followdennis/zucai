<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnalogueInjectionGroup extends Model
{
    //
    use SoftDeletes;
    protected $table = 'analogue_injection_group';
    protected $guarded = [];

    public function insertData($array){
        return self::insertGetId($array);
    }

    /**
     *
     */
    public function getGroupList(){
        $groups = self::orderBy('sort','desc')->orderBy('created_at','desc')->paginate(10);
        foreach($groups as $group){
            if($group->is_finish == 1){
                $i = 0;//串子正确个数
                $j = 0; //进球正确个数
                $score_money = 0;
                $score_rate = 0;//进球比率（求和）
                $win_rate = 1; //求积
                $match_no = 0;
                foreach($group->items as $k => $item){
                    //投注正确
                    if($item->match->match_result == $item->betting_result){
                        $i++;
                        $win_rate *= $item->match->final_rate;
                    }else{
                        $win_rate = 0;
                    }

                    if($item->match->total == $item->total){
                        $j++;
                        $score_rate += $item->match->total_rate;
                    }
                    $match_no++;
                }
                $score_money = $score_rate*20;//进球中奖金额
                $win_money = sprintf('%.2f',$win_rate * 20);
                $group->win_correct_num = $i; //胜平负正确个数
                $group->win_money = $win_money;
                $group->score_correct_num = $j;
                $group->score_money = $score_money;
                $group->match_no = $match_no;
                $group->sum = $score_money + $win_money - ($match_no+1)*20;
            }
        }
        return $groups;
    }

    public function items(){
        return $this->hasMany('App\Models\AnalogueInjection','group_id','id');
    }
}
