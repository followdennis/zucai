<?php

namespace App\Console\Commands;

use App\Models\AnalogueInjection;
use App\Models\AnalogueInjectionGroup;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Caipiao163Order extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:163order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理订单完成状态';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //处理订单完成状态
        $data = DB::table('analogue_injection_group as ag')->where('ag.is_finish',0)->get();

        foreach($data as $key => $item){
            $orders = AnalogueInjection::where('group_id',$item->id)->get();
            $is_finish = 1;
            $max_time = '2000-01-01 00:00:00';
            foreach($orders as $order){
                $match_status = $order->match->match_result;
                if($match_status > 0) $match_status = 1;
                $is_finish  &= $match_status;
                if($order->match->match_time > $max_time){
                    $max_time = $order->match->match_time;
                }
            }

            if($is_finish){

            }
            $real_max_time = Carbon::parse($max_time)->addHours(2);
            //更新状态
            $status = AnalogueInjectionGroup::where('id',$item->id)->where('is_finish',0)->update(['is_finish'=>$is_finish,'end_time'=>$real_max_time]);
            if($status){
                $this->info($status.'-'.$item->id);
            }
        }
    }
}
