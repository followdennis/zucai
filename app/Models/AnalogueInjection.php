<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnalogueInjection extends Model
{
    //
    use SoftDeletes;
    public $table = 'analogue_injection';
    public $guarded = [];
    protected $appends = ['win'];
    public function insertData($array){
        return self::insert($array);
    }

    public function getWinAttribute(){
        $res_arr = [
            0 => '',
            1 => '胜',
            2 => '平',
            3 => '负'
        ];
        return $this->attributes['win'] = $res_arr[$this->betting_result];
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *  获取对应的比赛
     */
    public function match(){
        return $this->hasOne('App\Models\SourceWangyiCaipiao','id','match_id');
    }


}
