<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

class synModule  extends baseModule
{

    public function index(){
        if(IS_DEBUG||1){
            $html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>数据操作</title>
</head>
<body>
<script type="text/javascript" src="'.SITE_DOMAIN.'/admin/Tpl/default/Common/js/jquery.js"></script>
<div align="center" style="padding-top: 50px;">
<form action="'.SITE_DOMAIN.'/syn/index.php?ctl=syn&act=do_action" method="get" id="form_type">
    执行操作：<select name="type" id="type" onchange="change_type();">
    <option value="0">选择数据的操作方式</option>
    <option value="1">0-100的机器人同步到IM</option>
    <option value="13">100-200的机器人同步到IM</option>
    <option value="14">200-300的机器人同步到IM</option>
    <option value="15">300-392的机器人同步到IM</option>
    <option value="2">机器人同步到redis</option>
    <option value="7">模拟登录账号（手机号或是ID）</option>
    <option value="8">获取腾讯IM的AES</option>
    <option value="9">获取服务器的AES</option>
    <option value="10">获取缓存数据</option>
    <option value="11">删除定时器加入直播的机器人列表</option>
     <option value="12">同步指定用户信息到redis</option>
</select>
    <br/>
    <div style="display: none;padding-top: 20px;" id="user_module">
        主播ID：<input type = "text" value="" name="user_id" id="user_id"/>
        主播手机号：<input type = "text" value="" name="mobile" id="mobile"/>
    </div>
    <div style="display: none;padding-top: 20px;" id="m_config">
        缓存名称：<input type = "text"  name="cache_name" id="cache_name" value = "m_config"/> 系统缓存：m_config
        是否清除缓存：<input type = "text"  name="rm_cache" id="rm_cache" value = "0" /> 0:不更新缓存；1：更新缓存
    </div>
    <br/>
    <input type="button" class="submit button" value="提交" onclick="submit_type();"/>
</form>
</div>
<script type="text/javascript">
    $(function(){
        var type = $("#type option:selected") .val();
        if(type==7 || type==12){
            $("#user_module").show();
        }else if(type==10){
            $("#m_config").show();
        }else{
            $("#user_module").hide();
            $("#m_config").hide();
            $("#user_id").val("");
        }
    });
        function change_type(){
            var type = $("#type option:selected") .val();
            if(type==7 || type==12 ){
                $("#user_module").show();
            }else if(type==10){
                $("#m_config").show();
            }else{
                $("#user_module").hide();
                $("#m_config").hide();
                $("#user_id").val("");
            }
        }

        function submit_type(){
            var type = $("#type option:selected") .val();
            if(type>0){
                var confirm_str = "确定将";
                var user_id = $("#user_id").val();
                var user_str = "所有";
                if(user_id){
                    user_str = user_id;
                }
                confirm_str = confirm_str+user_str+$("#type option:selected") .text() +"吗？";
                if(confirm(confirm_str)){
                    var url = $("#form_type").attr("action");
                    var query = $("#form_type").serialize();
                    $.ajax({
                        url:url,
                        data:query,
                        dataType:"json",
                        type:"post",
                        success:function(result){
                            alert(result.error);
                            func();
                            function func(){
                                if(result.status==1){
                                    location.href=location.href;
                                }
                            }
                        }
                    });
                }
            }else{
                alert("请选择数据的操作方式！！");
            }
        }
        </script>
</body>
</html>';
            echo $html;
        }else{
            print_r("请开启debug模式");exit;
        }
    }
    //1、2、7、8、9
    public function do_action(){
        $root = array('status'=>0,'error'=>'');
        if(IS_DEBUG){
            $type = $_REQUEST['type'];
            $root['error'] = '请选择数据的操作方式！！';
            if($type){
                $user_id = intval($_REQUEST['user_id']);
                if($type == 7 || $type==12){
                    $user_id = $GLOBALS['db']->getOne("select id from " . DB_PREFIX . "user where id = " . intval($user_id),true,true);
                    if(intval($user_id)<=0){
                        $root['error'] = '主播ID不存在';
                        admin_ajax_return($root);
                    }
                }
                if($type == 1){
                    $res = $this->robot_im1(1);
                    if($res['status']==0){
                        admin_ajax_return($res);
                    }else{
                        $root['error'] =$res['error'];
                        admin_ajax_return($root);
                    }
                }elseif($type == 13){
                    $res = $this->robot_im2(1);
                    if($res['status']==0){
                        admin_ajax_return($res);
                    }else{
                        $root['error'] =$res['error'];
                        admin_ajax_return($root);
                    }
                }elseif($type == 14){
                    $res = $this->robot_im3(1);
                    if($res['status']==0){
                        admin_ajax_return($res);
                    }else{
                        $root['error'] =$res['error'];
                        admin_ajax_return($root);
                    }
                }elseif($type == 15){
                    $res = $this->robot_im4(1);
                    if($res['status']==0){
                        admin_ajax_return($res);
                    }else{
                        $root['error'] =$res['error'];
                        admin_ajax_return($root);
                    }
                }elseif($type == 2){
                    $this->robot(1);
                }elseif($type==7){
                    $user_id = intval($_REQUEST['user_id']);
                    $mobile = strim($_REQUEST['mobile']);
                    $root['error'] =  print_r($this->login(1,$user_id,$mobile),1) ;
                    admin_ajax_return($root);
                }elseif($type==8){
                    $get_aes_key = $this->get_aes_key();
                    $root['error'] = print_r($get_aes_key,1);
                    admin_ajax_return($root);
                }elseif($type==9){
                    $get_privatekey =  $this->get_privatekey();
                    $root['error'] = print_r($get_privatekey,1);
                    admin_ajax_return($root);
                }elseif($type==10){
                    $cache_name = $_REQUEST['cache_name'];
                    $rm_cache = intval($_REQUEST['rm_cache']);
                    if($cache_name==''){
                        $root['error'] = '缓存文件名称不能为空！';
                    }else{
                        $m_configs =  $this->m_configs($cache_name,$rm_cache);
                        $root['error'] = print_r($m_configs,1);
                    }
                    admin_ajax_return($root);
                }elseif($type==11){
                    $d_user_robot =  $this->del_user_robot();
                    if(intval($d_user_robot)>0){
                        $root['error'] = '删除成功！';
                    }else{
                        $root['error'] = '无数据！';
                    }
                    admin_ajax_return($root);
                }elseif($type==12){
                    $id = intval($_REQUEST['user_id']);
                    $mobile = strim($_REQUEST['mobile']);
                    $update_user =  $this-> update_user($id,$mobile);
                    $root['error'] = print_r($update_user,1);
                    admin_ajax_return($root);
                }
            }
        }else{
            $root['error'] = '请开启debug模式';
            admin_ajax_return($root);
        }
    }

    //同步机器人到im1
    public function robot_im1($json = 0){
        $root = array('status' => 0, 'error' => '');
        if(IS_DEBUG) {
            $user_data = $GLOBALS['db']->getAll("select * from " . DB_PREFIX . "user where is_robot = 1 limit 0,100");
            require_once(APP_ROOT_PATH . 'system/tim/TimApi.php');
            fanwe_require(APP_ROOT_PATH . 'mapi/lib/redis/BaseRedisService.php');
            fanwe_require(APP_ROOT_PATH . 'mapi/lib/redis/UserRedisService.php');
            $user_redis = new UserRedisService();
            $api = createTimAPI();

            if (is_array($api)) {
                if ($json) {
                    $res = json_decode($api);
                    $root = array('status' => 0, 'error' => $res['ErrorInfo']);
                    admin_ajax_return($root);
                }
                print_r($api,1);
                exit;
            }
            if (count($user_data)) {
                foreach ($user_data as $k => $v) {
                    //添加成功，同步信息
                    $ret = $api->account_import((string)$v['id'], $v['nick_name'], $v['head_image']);
                    if ($ret['ErrorCode'] == 0) {
                        $GLOBALS['db']->query("update " . DB_PREFIX . "user set synchronize = 1 where id =" . $v['id']);
                        $data['synchronize'] = 1;
                        $user_redis->update_db($v['id'], $data);
                        $ret_im[] = $v['id'];
                    } else {
                        $root = array('status' => 0, 'error' => $ret['ErrorInfo']);
                    }
                }
                if ($ret_im) {
                    $root = array('status' => 1, 'error' => '第一次同步实际数量：' . count($user_data) . '   同步数量：' . count($ret_im));
                }
            }else{
                $root['error'] = '机器人数量少于10';
            }
        }else{
            $root['error'] = 'IS_DEBUG参数未开启';
        }
        return $root;
    }
    //同步机器人到im2
    public function robot_im2($json = 0){
        $root = array('status' => 0, 'error' => '');
        if(IS_DEBUG) {
            $user_data = $GLOBALS['db']->getAll("select * from " . DB_PREFIX . "user where is_robot = 1 limit 100,100");
            require_once(APP_ROOT_PATH . 'system/tim/TimApi.php');
            fanwe_require(APP_ROOT_PATH . 'mapi/lib/redis/BaseRedisService.php');
            fanwe_require(APP_ROOT_PATH . 'mapi/lib/redis/UserRedisService.php');
            $user_redis = new UserRedisService();
            $api = createTimAPI();

            if (is_array($api)) {
                if ($json) {
                    admin_ajax_return($api);
                }
                print_r($api);
                exit;
            }
            if (count($user_data)) {
                foreach ($user_data as $k => $v) {
                    //添加成功，同步信息
                    $ret = $api->account_import((string)$v['id'], $v['nick_name'], $v['head_image']);
                    if ($ret['ErrorCode'] == 0) {
                        $GLOBALS['db']->query("update " . DB_PREFIX . "user set synchronize = 1 where id =" . $v['id']);
                        $data['synchronize'] = 1;
                        $user_redis->update_db($v['id'], $data);
                        $ret_im[] = $v['id'];
                    } else {
                        $root = array('status' => 0, 'error' => $ret['ErrorInfo']);
                    }
                }
                if ($ret_im) {
                    $root = array('status' => 1, 'error' => '第二次同步实际数量：' . count($user_data) . '   同步数量：' . count($ret_im));
                }
            }else{
                $root['error'] = '机器人数量少于100';
            }
        }else{
            $root['error'] = 'IS_DEBUG参数未开启';
        }
        return $root;
    }
    //同步机器人到im3
    public function robot_im3($json = 0){
        $root = array('status' => 0, 'error' => '');
        if(IS_DEBUG) {
            $user_data = $GLOBALS['db']->getAll("select * from " . DB_PREFIX . "user where is_robot = 1 limit 200,100");
            require_once(APP_ROOT_PATH . 'system/tim/TimApi.php');
            fanwe_require(APP_ROOT_PATH . 'mapi/lib/redis/BaseRedisService.php');
            fanwe_require(APP_ROOT_PATH . 'mapi/lib/redis/UserRedisService.php');
            $user_redis = new UserRedisService();
            $api = createTimAPI();

            if (is_array($api)) {
                if ($json) {
                    admin_ajax_return($api);
                }
                print_r($api);
                exit;
            }
            if (count($user_data)) {
                foreach ($user_data as $k => $v) {
                    //添加成功，同步信息
                    $ret = $api->account_import((string)$v['id'], $v['nick_name'], $v['head_image']);
                    if ($ret['ErrorCode'] == 0) {
                        $GLOBALS['db']->query("update " . DB_PREFIX . "user set synchronize = 1 where id =" . $v['id']);
                        $data['synchronize'] = 1;
                        $user_redis->update_db($v['id'], $data);
                        $ret_im[] = $v['id'];
                    } else {
                        $root = array('status' => 0, 'error' => $ret['ErrorInfo']);
                    }
                }
                if ($ret_im) {
                    $root = array('status' => 1, 'error' => '第三次同步实际数量：' . count($user_data) . '   同步数量：' . count($ret_im));
                }
            }else{
                $root['error'] = '机器人数量少于200';
            }
        }else{
            $root['error'] = 'IS_DEBUG参数未开启';
        }
        return $root;
    }
    //同步机器人到im4
    public function robot_im4($json = 0){
        $root = array('status' => 0, 'error' => '');
        if(IS_DEBUG) {
            $user_data = $GLOBALS['db']->getAll("select * from " . DB_PREFIX . "user where is_robot = 1 limit 300,100");
            require_once(APP_ROOT_PATH . 'system/tim/TimApi.php');
            fanwe_require(APP_ROOT_PATH . 'mapi/lib/redis/BaseRedisService.php');
            fanwe_require(APP_ROOT_PATH . 'mapi/lib/redis/UserRedisService.php');
            $user_redis = new UserRedisService();
            $api = createTimAPI();

            if (is_array($api)) {
                if ($json) {
                    admin_ajax_return($api);
                }
                print_r($api);
                exit;
            }
            if (count($user_data)) {
                foreach ($user_data as $k => $v) {
                    //添加成功，同步信息
                    $ret = $api->account_import((string)$v['id'], $v['nick_name'], $v['head_image']);
                    if ($ret['ErrorCode'] == 0) {
                        $GLOBALS['db']->query("update " . DB_PREFIX . "user set synchronize = 1 where id =" . $v['id']);
                        $data['synchronize'] = 1;
                        $user_redis->update_db($v['id'], $data);
                        $ret_im[] = $v['id'];
                    } else {
                        $root = array('status' => 0, 'error' => $ret['ErrorInfo']);
                    }
                }
                if ($ret_im) {
                    $root = array('status' => 1, 'error' => '第四次同步实际数量：' . count($user_data) . '   同步数量：' . count($ret_im));
                }
            }else{
                $root['error'] = '机器人数量少于300';
            }
        }else{
            $root['error'] = 'IS_DEBUG参数未开启';
        }
        return $root;
    }

    //同步机器人到redis
    public function robot($json = 0){
        if(IS_DEBUG){
            $user_data = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where is_robot = 1");
            if(count($user_data)>0){
                fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/BaseRedisService.php');
                fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
                $user_redis = new UserRedisService();
                foreach($user_data as $k=>$v){
                    $user_redis->insert_db($v['id'],$v);
                    $ret[] = $v['id'];
                }
            }
            if($json){
                $root = array('status'=>1,'error'=>'实际数量：'.count($user_data).'   同步数量：'.count($ret));
                admin_ajax_return($root);
            }
            print_r($ret);exit;
        }

    }

    //登录 test
    public function login($json=0,$user_id=0,$mobile=0)
    {
        if(IS_DEBUG){
            $mobile = intval($mobile);
            $uid = intval($user_id);
            if($mobile){
                $user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where mobile =".$mobile);
            }else{
                if($uid){
                    $user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id =".$uid);
                }else{
                    return "请填写会员ID";
                }
            }

            es_session::set("user_info",$user_data);
            $GLOBALS['user_info'] = $user_data;
            es_cookie::set("client_ip",CLIENT_IP,3600*24*30);
            es_cookie::set("nick_name",$user_data['nick_name'],3600*24*30);
            es_cookie::set("user_id",$user_data['id'],3600*24*30);
            es_cookie::set("user_pwd",md5($user_data['user_pwd']."_EASE_COOKIE"),3600*24*30);
            es_cookie::set("is_agree",$user_data['is_agree'],3600*24*30);
            es_cookie::set("PHPSESSID2",es_session::id(),3600*24*30);
            return $user_data;
        }
    }

    //获取腾讯aeskey
    public function get_aes_key(){
        if(IS_DEBUG) {
            $m_config = load_auto_cache("m_config");//初始化手机端配置
            require_once(APP_ROOT_PATH . 'system/tim/TimApi.php');
            $api = createTimAPI();
            $group_id = strim($m_config['full_group_id']);
            $base_info_filter = array("Introduction");
            $ret = $api->group_get_group_info2(array('0' => $group_id), $base_info_filter);
            return $ret;
        }
    }

    //获取服务端key
    public function get_privatekey(){
        if(IS_DEBUG) {
            $key_list = get_privatekey();
            return $key_list;
        }
    }

    //测试读取缓存 load_auto_cache
    public function m_configs($cache_name,$rm_cache=0){
        if(IS_DEBUG){
            if($rm_cache){
                rm_auto_cache($cache_name);
            }
            $cache =  load_auto_cache($cache_name);
            return $cache;
        }
    }

    //删除定时器加入直播的机器人列表
    public function del_user_robot(){
        if(IS_DEBUG){
            fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/BaseRedisService.php');
            fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
            $user_redis = new UserRedisService();
            $video_con_keys = $user_redis->redis->keys($GLOBALS['distribution_cfg']['REDIS_PREFIX'].'user_robot');
            $video_con_count = $user_redis->redis->delete($video_con_keys);
            return $video_con_count;
        }
    }

    //同步某个用户信息到redis
    public function update_user($id,$mobile=''){
        if(IS_DEBUG){
            if($mobile!=''){
                $user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where mobile = ".$mobile);
            }else{
                $user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id);
            }
            return $user_data;
            if($user_data){
                $user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id);
                fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/BaseRedisService.php');
                fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
                $user_redis = new UserRedisService();
                $user_redis->update_db($user_data['id'],$user_data);
                return $id;
            }else{
                return array('error'=>'用户不存在！');
            }


        }
    }

   //********************************
    public function index2(){
        if(IS_DEBUG||1){
            $html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>数据操作</title>
</head>
<body>
<script type="text/javascript" src="'.SITE_DOMAIN.'/admin/Tpl/default/Common/js/jquery.js"></script>
<div align="center" style="padding-top: 50px;">
<form action="'.SITE_DOMAIN.'/syn/index1.php?ctl=syn&act=do_action2" method="get" id="form_type">
    清除信息：<select name="type" id="type" onchange="change_type();">
    <option value="0">选择数据的操作方式</option>
    <option value="3">修改等级为1（积分清零）</option>
    <option value="4">用户的钻石、消费钻石、印票、可用印票清零</option>
    <option value="5">用户的粉丝、关注数量清零</option>
    <option value="6">清空所有记录（包含用户、直播、提现、充值等除配置外所有数据，不含机器人）</option>
</select>
    <br/>
    <div style="display: none;padding-top: 20px;" id="user_module">
        主播ID：<input type = "text" value="" name="user_id" id="user_id"/>(不填则操作所有主播)
    </div>
    <br/>
    <input type="button" class="submit button" value="提交" onclick="submit_type();"/>
</form>
</div>
<script type="text/javascript">
    $(function(){
        var type = $("#type option:selected") .val();
        if(type==3 || type==4 || type==5){
            $("#user_module").show();
        }else{
            $("#user_module").hide();
            $("#user_id").val("");
        }
    });
        function change_type(){
            var type = $("#type option:selected") .val();
            if(type==3 || type==4 || type==5){
                $("#user_module").show();
            }else{
                $("#user_module").hide();
                $("#user_id").val("");
            }
        }

        function submit_type(){
            var type = $("#type option:selected") .val();
            if(type>0){
                var confirm_str = "确定将";
                var user_id = $("#user_id").val();
                var user_str = "所有";
                if(user_id){
                    user_str = user_id;
                }
                confirm_str = confirm_str+user_str+$("#type option:selected") .text() +"吗？";
                if(confirm(confirm_str)){
                    var url = $("#form_type").attr("action");
                    var query = $("#form_type").serialize();
                    $.ajax({
                        url:url,
                        data:query,
                        dataType:"json",
                        type:"post",
                        success:function(result){
                            alert(result.error);
                            func();
                            function func(){
                                if(result.status==1){
                                    location.href=location.href;
                                }
                            }
                        }
                    });
                }
            }else{
                alert("请选择数据的操作方式！！");
            }
        }
        </script>
</body>
</html>';
            echo $html;
        }else{
            print_r("请开启debug模式");exit;
        }
    }
    //3、4、5、6
    public function do_action2(){
        $root = array('status'=>0,'error'=>'');
        if(IS_DEBUG){
            $type = $_REQUEST['type'];
            $root['error'] = '请选择数据的操作方式！！';
            if($type){
                $user_id = intval($_REQUEST['user_id']);
                if($type == 3 || $type==4 || $type==5){
                    $user_id = $GLOBALS['db']->getOne("select id from " . DB_PREFIX . "user where  id = " . intval($user_id),true,true);
                    if(intval($user_id)<=0){
                        $root['error'] = '主播ID不存在';
                        admin_ajax_return($root);
                    }
                }
                if($type == 3){
                    $this->syn_user_level(1,$user_id);
                }elseif($type==4){
                    $sql = "";
                    if($user_id>0){
                        $sql = " and id=".$user_id;
                    }
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set diamonds=0,use_diamonds=0,ticket=0,refund_ticket=0 where is_robot = 0 ".$sql);
                    $user_data = $GLOBALS['db']->getAll("select id,diamonds,use_diamonds,ticket,refund_ticket from ".DB_PREFIX."user where 1 = 1 ".$sql,true,true);
                    if(count($user_data)>0){
                        fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/BaseRedisService.php');
                        fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
                        $user_redis = new UserRedisService();
                        foreach($user_data as $k=>$v){
                            $user_redis->update_db($v['id'],$v);
                            $ret[] = $v['id'];
                        }
                    }
                    $root = array('status'=>0,'error'=>'清除失败！');
                    if(count($user_data) == count($ret)){
                        $root['status'] = 1;
                        $root['error'] = '清除成功！';
                    }
                    admin_ajax_return($root);
                }elseif($type==5){
                    $sql = "";
                    if($user_id>0){
                        $sql = " and id=".$user_id;
                    }
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set fans_count=0,focus_count=0 where is_robot = 0 ".$sql);
                    $user_data = $GLOBALS['db']->getAll("select id,fans_count,focus_count from ".DB_PREFIX."user where is_robot = 0 ".$sql,true,true);
                    if(count($user_data)>0){
                        fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/BaseRedisService.php');
                        fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
                        fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserFollwRedisService.php');
                        $user_redis = new UserRedisService();
                        foreach($user_data as $k=>$v){
                            $user_redis->update_db($v['id'],$v);
                            $ret[] = $v['id'];
                            $user_follow_redis = new UserFollwRedisService($user_id);
                            $user_follow_redis->redis->delete($user_follow_redis->user_follow_db.$v['id']);
                            $user_follow_redis->redis->delete($user_follow_redis->user_followed_by_db.$v['id']);
                        }
                    }
                    $root = array('status'=>0,'error'=>'清除失败！');
                    if(count($user_data) == count($ret)){
                        $root['status'] = 1;
                        $root['error'] = '清除成功！';
                    }
                    admin_ajax_return($root);
                }elseif($type==6){
                    $root = $this->clear_all();
                    admin_ajax_return($root);
                }
            }
        }else{
            $root['error'] = '请开启debug模式';
        }
        print_r($root);
    }

    //所有用户等级改为1,其他与等级相关字段清零
    public function syn_user_level($json =0,$user_id=0){
        if(IS_DEBUG){
            $sql = "";
            if($user_id>0){
                $sql = " and id=".$user_id;
            }
            $GLOBALS['db']->query("update ".DB_PREFIX."user set score=0,online_time=0,user_level=1 where is_robot = 0 ".$sql);
            $user_data = $GLOBALS['db']->getAll("select id,score,online_time,user_level from ".DB_PREFIX."user where is_robot = 0 ".$sql,true,true);
            if(count($user_data)>0){
                fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/BaseRedisService.php');
                fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
                $user_redis = new UserRedisService();
                foreach($user_data as $k=>$v){
                    $user_redis->update_db($v['id'],$v);
                    $ret[] = $v['id'];
                }
            }
            if($json){
                $root = array('status'=>0,'error'=>'修改失败！');
                if(count($user_data) == count($ret)){
                    $root['status'] = 1;
                    $root['error'] = '修改成功！';
                }
                admin_ajax_return($root);
            }
            print_r($ret);exit;
        }

    }

    //清空用户数据
    public function clear_all(){
        $result = array();
        $result[DB_PREFIX.'api_log'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."api_log");
        $result[DB_PREFIX.'black'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."black");
        $result[DB_PREFIX.'deal_msg_list'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."deal_msg_list");
        $result[DB_PREFIX.'mobile_verify_code'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."mobile_verify_code");
        $result[DB_PREFIX.'exchange_log'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."exchange_log");
        $result[DB_PREFIX.'flow_statistics'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."flow_statistics");

        $result[DB_PREFIX.'log'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."log");
        $result[DB_PREFIX.'payment_notice'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."payment_notice");

        $result[DB_PREFIX.'push_anchor'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."push_anchor");
        $result[DB_PREFIX.'slb_group'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."slb_group");
        $result[DB_PREFIX.'tipoff'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."tipoff");

        //清空主播
        $result[DB_PREFIX.'user'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."user where is_robot = 0");
        $result[DB_PREFIX.'user_admin'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."user_admin");
        $result[DB_PREFIX.'user_id'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."user_id");
        $result[DB_PREFIX.'user_log'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."user_log");
        $result[DB_PREFIX.'user_music'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."user_music");
        $result[DB_PREFIX.'user_refund'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."user_refund");
        $result[DB_PREFIX.'login_log'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."login_log");

        //清空直播记录
        $result[DB_PREFIX.'room_id'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."room_id");
        $result[DB_PREFIX.'video'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."video");
        $result[DB_PREFIX.'video_history'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."video_history");
        $result[DB_PREFIX.'video_cate'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."video_cate");
        $result[DB_PREFIX.'video_lianmai'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."video_lianmai");
        $result[DB_PREFIX.'video_lianmai_history'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."video_lianmai_history");
        $result[DB_PREFIX.'video_monitor'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."video_monitor");
        $result[DB_PREFIX.'video_monitor_history'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."video_monitor_history");
        $result[DB_PREFIX.'video_red'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."video_red");
        $result[DB_PREFIX.'video_share'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."video_share");
        $result[DB_PREFIX.'video_share_history'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."video_share_history");
        $result[DB_PREFIX.'video_prop'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."video_prop");

        if(defined('OPEN_LIVE_PAY')&&OPEN_LIVE_PAY){
            $result[DB_PREFIX.'live_pay_log'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."live_pay_log");
            $result[DB_PREFIX.'live_pay_log_history'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."live_pay_log_history");
        }

        //家族
        if(defined('OPEN_FAMILY_MODULE')&&OPEN_FAMILY_MODULE==1){
            $result[DB_PREFIX.'family'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."family");
            $result[DB_PREFIX.'family_join'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."family_join");
            $result[DB_PREFIX.'family_level'] = $GLOBALS['db']->query("delete from ".DB_PREFIX."family_level");
        }
        if(defined('OPEN_GAME_MODULE')&&OPEN_GAME_MODULE==1){
            $result = array_merge($result,$this->clear_game_data());
        }
        if(defined('OPEN_GAME_MODULE')&&OPEN_PAI_MODULE==1){
            $result = array_merge($result,$this->pai_delete_data());
        }
        if(defined('OPEN_GAME_MODULE')&&SHOPPING_GOODS==1){
            $result = array_merge($result,$this->shop_delete_data());
        }
        if(defined('OPEN_GAME_MODULE')&&OPEN_PODCAST_GOODS==1){
            $result = array_merge($result,$this->podcast_goods_delete_data());
        }

        $root = array();
        $root['status'] = 1;
        $root['error'] = print_r($result,1);
        return $root;
    }





    public function clear_game_data()
    {
        require_once APP_ROOT_PATH . 'mapi/lib/core/Model.class.php';
        Model::$lib = dirname(__FILE__);
        $result     = array();
        $variable   = [
            'coin_log',
            'game_log',
            'game_log_history',
            'user_game_log',
            'user_game_log_history',
            'banker_log',
            'banker_log_history',
            'game_distribution',
        ];
        foreach ($variable as $value) {
            $result[$value] = Model::build($value)->delete(['id' => ['>', 0]]);
        }
        return $result;
    }

    //清空竞拍数据
    public function pai_delete_data(){
        require_once APP_ROOT_PATH . 'mapi/lib/core/Model.class.php';
        Model::$lib = dirname(__FILE__);
        $result = array();
        $variable   = [
            'pai_goods',
            'pai_join',
            'goods_order',
            'user_address',
            'user_notice',
            'pai_tags',
            'user_diamonds_log',
            'pai_log',
            'pai_violations',
            'goods',
            'user_goods',
            'courier',
            'goods_cate',
            'goods_tags',
        ];
        foreach($variable as $key => $value){
            $result[$value] = Model::build($value)->delete(['id'=>['>',0]]);
        }
        return $result;
    }

    //清空购物数据
    public function shop_delete_data(){
        require_once APP_ROOT_PATH . 'mapi/lib/core/Model.class.php';
        Model::$lib = dirname(__FILE__);
        $result = array();
        $variable   = [
            'goods_order',
            'user_address',
            'user_notice',
            'pai_tags',
            'user_diamonds_log',
            'goods',
            'user_goods',
            'courier',
            'shopping_cart',
            'goods_cate',
            'goods_tags',
        ];
        foreach($variable as $key => $value){
            $result[$value] = Model::build($value)->delete(['id'=>['>',0]]);
        }
        return $result;
    }

    //清空小店数据
    public function podcast_goods_delete_data(){
        require_once APP_ROOT_PATH . 'mapi/lib/core/Model.class.php';
        Model::$lib = dirname(__FILE__);
        $result = array();
        $result['podcast_goods'] = Model::build('podcast_goods')->delete(['id'=>['>',0]]);

        return $result;
    }


    public function clearBmPromoter()
    {
        require_once APP_ROOT_PATH . 'mapi/lib/core/Model.class.php';
        Model::$lib = dirname(__FILE__);
        $result = array();
        $result[] = Model::build('bm_config')->update(['val'=>0],['code'=>'bm_pid']);
        $variable   = [
            'bm_promoter',
            'bm_promoter_game_log',
            'bm_qrcode',
        ];
        foreach($variable as $key => $value){
            $result[$value] = Model::build($value)->delete(['id'=>['>',0]]);
        }
        return $result;
    }

    //更新2.5版本后，填充旧用户的认证图标
    public function modify_v_icon()
    {
        $authentication_list = $GLOBALS['db']->getAll("select name,icon from ".DB_PREFIX."authent_list");
        if ($authentication_list)
        {
            $sql = "UPDATE ".DB_PREFIX."user SET v_icon = CASE authentication_type ";
            foreach($authentication_list as $k => $v)
            {
                $sql .= "WHEN '{$v['name']}' THEN '{$v['icon']}' ";
            }
            $sql .= "END where is_authentication = 2";
        }
        $GLOBALS['db']->query($sql);
    }
}
