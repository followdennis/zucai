<?php
/**
 * 生成标准uuid（guid）
 * @param type $enableTrim  去掉两侧的大括号
 * @return type
 */
if (! function_exists('guid')) {
    /**
     * @param string $str
     * @return string
     */
    function guid() : string
    {
        if (function_exists('com_create_guid')){
            $guid = com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $guid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
        }
        return  trim($guid, '{}');
    }
}

/**
 * 将数据库中获取数据转换成数组
 * @param type $enableTrim  去掉两侧的大括号
 * @return type
 */
if (! function_exists('obj_to_array')) {
    /**
     * @param string $str
     * @return string
     */
    function obj_to_array($list_arr)
    {
        $list = [];
        if(!empty($list_arr))
        {
            foreach ($list_arr as $r) {
                if(is_object($r))
                {
                    $list[] = \App\Services\Utils::objectToArray($r);
                }else{
                    $list[] = $r;
                }
            }
        }
        return $list;
    }
}

/**
 * 2017-11-04
 * 两个等长的二位数组合并（可用）
 * select   true 则均为二位数组，false 表示arr2为一维数组
 * @param array $arr1 二维数组
 * @param array $arr2 二维数组
 */
if (! function_exists('array_add_column')) {
    function array_add_column($arr1=array(),$arr2= array(),$select = true,$field = 'id'){
        if(!is_array($arr1) || !is_array($arr2)){
            return '';
        }
        if($select){
            foreach($arr1 as $k => &$v){
                foreach($arr2[$k] as $key => $val){
                    $v[$key] = $val;
                }
            }
        }else{
            foreach($arr1 as $k => &$v){
                $v[$field] = $arr2[$k];
            }
        }
        return $arr1;
    }
}

/**
 * 获取文章head表id
 */
if(!function_exists('get_article_head_id')){
    function get_article_head_id($id){
        return ceil($id/10000);
    }
}
/**
 * 获取文章body表id
 */
if(!function_exists('get_article_body_id')){
    function get_article_body_id($id){
        return ceil($id/5000);
    }
}
//下注时间
if(!function_exists('betting_day')){
//    $datetime = '2008-02-12 23:33:20';
    function betting_day($datetime){
        $today = \Carbon\Carbon::parse($datetime)->startOfDay();

        $start = \Carbon\Carbon::parse($today)->addHours(12);
        if(strtotime($datetime) > strtotime($start)){
            return $today;
        }else{
            return \Carbon\Carbon::parse($today)->subDay();
        }
    }

}
