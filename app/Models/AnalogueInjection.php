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

    public function insertData($array){
        return self::insert($array);
    }

}
