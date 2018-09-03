<?php

namespace App\Http\Controllers\Frontend;

use App\Models\AnalogueInjection;
use App\Models\AnalogueInjectionGroup;
use App\Models\SourceWangyiCaipiao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends BaseController
{
    //
    protected $groupModel;
    public function __construct(AnalogueInjectionGroup $group)
    {
        $this->groupModel = $group;
    }

    public function index(Request $request){
        $groups = $this->groupModel->getGroupList();
        return view('frontend.order.index',['groups'=>$groups]);
    }
    public function detail(Request $request,SourceWangyiCaipiao $sourceModel){
        $ids = $request->get('itemId');
        $IdArr = explode(',',$ids);
        $data = $sourceModel->getDataByIdArr($IdArr);
        return view('frontend.order.match_detail',['list'=>$data]);
    }
    /**
     * 获取备注相关信息
     */
    public function remark(Request $request){
        $analogue_id = $request->get('analogue_id');

        $data = AnalogueInjection::where('id',$analogue_id)->first();
        if(empty($data)){
            return false;
        }
        return view('frontend.order.remark_detail',['data'=>$data]);
    }
    public function remarkSave(Request $request){
        if($request->ajax()){
            $analogueId = intval($request->get('analogueId'));
            $remark2 = $request->get('remark2');
            $status = AnalogueInjection::where('id',$analogueId)->update(['remark2'=>$remark2]);
            if($status){
                return response()->json(['code'=>0,'msg'=>'添加成功']);
            }else{
                return response()->json(['code'=>-1,'msg'=>'添加失败']);
            }
        }
    }

}
