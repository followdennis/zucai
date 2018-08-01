<?php

namespace App\Http\Controllers\Frontend;

use App\Models\SourceWangyiCaipiao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends BaseController
{
    //
    protected $sourceModel;
    public function __construct(SourceWangyiCaipiao $sourceWangyiCaipiao)
    {
        $this->sourceModel = $sourceWangyiCaipiao;
    }

    public function index(Request $request){
        $this->validate($request,[
            'competitionName'=>'max:30',
            'teamName' =>'max:10',
            'bettingTime' => 'max:25',
            'matchStatus' => 'max:1',
            'totalScore' =>'max:3',
            'pageSize'=>'max:3'
        ]);
        $condition = $request->all();
        //添加分页的查询条件
        $data = $this->sourceModel->getList($condition)->appends([
            'competitionName' =>$condition['competitionName'] ?? '',
            'teamName' =>$condition['teamName'] ??'',
            'bettingTime' =>$condition['bettingTime'] ?? '',
            'matchStatus' =>$condition['matchStatus'] ?? 2,
            'totalScore' =>$condition['totalScore'] ?? '',
            'pageSize' =>$condition['pageSize'] ?? '',
        ]);
        $dateList = $this->sourceModel->getBettingDateList();
        return view('frontend.index.index',[
            'list'=>$data,
            'dateList'=>$dateList,
            'competitionName' =>$condition['competitionName'] ?? '',
            'teamName' =>$condition['teamName'] ??'',
            'bettingTime' =>$condition['bettingTime'] ?? '',
            'matchStatus' =>$condition['matchStatus'] ?? 2,
            'totalScore' =>$condition['totalScore'] ?? '',
            'pageSize' =>$condition['pageSize'] ?? '',
        ]);
    }

    /**
     * 页面相关的一些数值计算
     */
    public function calculate(Request $request){
        $this->validate($request,[
            'competitionName'=>'max:30',
            'teamName' =>'max:10',
            'bettingTime' => 'max:25',
            'matchStatus' => 'max:1',
            'totalScore' =>'max:3',
            'pageSize'=>'max:3'
        ]);
        $condition = $request->all();
        //添加分页的查询条件
        $condition['status'] = 2;
        $data = $this->sourceModel->getList($condition);
        $hope_number = 0;
        //单场投注
        $vest = 0; //投入 正常投入
        $repay = 0; //返回 正常返奖
        $score_feedback = [
            'score0' => 0,
            'score1' => 0,
            'score2' => 0,
            'score3' => 0,
            'score4' => 0
        ];
        foreach($data as $k => $item){
            $vest += 10; //投入的钱
            if($item->hope == 1){
                $hope_number++;//符合预期的数量统计
                $repay += $item->final_rate * 10; //获奖总额
            }
            //进球数的回报率
            if($item->total == 0){
                $score_feedback['score0'] += $item->total_rate * 10;
            }
            if($item->total == 1){
                $score_feedback['score1'] += $item->total_rate * 10;
            }
            if($item->total == 2){
                $score_feedback['score2'] += $item->total_rate * 10;
            }
            if($item->total == 3){
                $score_feedback['score3'] += $item->total_rate * 10;
            }
            if($item->total == 4){
                $score_feedback['score4'] += $item->total_rate * 10;
            }
        }
        $score_feedback['score0Rate'] = round($score_feedback['score0']/$vest * 100,2) .'%';
        $score_feedback['score1Rate'] = sprintf('%.2f',$score_feedback['score1']/$vest)*100 .'%';
        $score_feedback['score2Rate'] = sprintf('%.2f',$score_feedback['score2']/$vest)*100 .'%';
        $score_feedback['score3Rate'] = sprintf('%.2f',$score_feedback['score3']/$vest)*100 .'%';
        $score_feedback['score4Rate'] = sprintf('%.2f',$score_feedback['score4']/$vest)*100 .'%';
        $hope_rate = sprintf('%.2f',$hope_number/($k + 1))*100;
        $feedback = sprintf('%.2f',($repay/$vest))*100;
        return response()->json([
            'hopeRate'=>$hope_rate .'%',
            'number' => $k+1, //统计的总数
            'vestTotal' => sprintf('%.2f',$vest),
            'repayTotal' => sprintf('%.2f',$repay),
            'feedback' =>$feedback.'%',
            'scoreFeedback' => $score_feedback
        ]);
    }
}
