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
        $group = self::orderBy('sort','desc')->orderBy('created_at','desc')->paginate(10);
        return $group;
    }

    public function items(){
        return $this->hasMany('App\Models\AnalogueInjection','group_id','id');
    }
}
