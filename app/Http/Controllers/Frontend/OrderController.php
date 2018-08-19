<?php

namespace App\Http\Controllers\Frontend;

use App\Models\AnalogueInjectionGroup;
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
}
