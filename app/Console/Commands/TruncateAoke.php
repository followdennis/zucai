<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateAoke extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:truncate_aoke';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清空aoke表及相关表';

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
        //
        $this->info('开始清空数据表');
        $aoke_res = DB::table('aoke')->truncate();
        $aoke_total = DB::table('total_score')->truncate();
        $aoke_half_ground = DB::table('half_ground')->truncate();
        $aoke_match_score = DB::table('match_score')->truncate();
        $aoke_win_and_fail = DB::table('win_and_fail')->truncate();

        if( $aoke_res && $aoke_total && $aoke_half_ground && $aoke_match_score && $aoke_win_and_fail){
            $this->info('清空成功');
        }
        $this->info('清空数据完成');
    }
}
