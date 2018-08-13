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
}
