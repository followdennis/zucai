<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WangyiHostHistoryScore extends Model
{
    //
    protected $table = 'wangyi_host_history_score';
    public $guarded = [];
    public $appends = ['date'];
    public function getDateAttribute(){
        return $this->attributes['date'] = Carbon::parse($this->match_time)->format('m-d');
    }
}
