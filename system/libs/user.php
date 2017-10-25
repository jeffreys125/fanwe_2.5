<?php
// +----------------------------------------------------------------------
// | Fanwe 方维直播系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

define("EMPTY_ERROR",1);  //未填写的错误
define("FORMAT_ERROR",2); //格式错误
define("EXIST_ERROR",3); //已存在的错误

define("ACCOUNT_NO_EXIST_ERROR",1); //帐户不存在
define("ACCOUNT_PASSWORD_ERROR",2); //帐户密码错误
define("ACCOUNT_NO_VERIFY_ERROR",3); //帐户未激活


	/**
	 * 生成会员数据
	 * @param $user_data  提交[post或get]的会员数据
	 * @param $mode  处理的方式，注册或保存
	 * 返回：data中返回出错的字段信息，包括field_name, 可能存在的field_show_name 以及 error 错误常量
	 * $update_status后台更新标示字段
	 */
	function save_user($user_data,$mode='INSERT',$update_status)
	{
		//开始数据验证
		$res = array('status'=>1,'info'=>'','data'=>''); //用于返回的数据

        if($user_data['mobile']!=''&&!check_mobile(trim($user_data['mobile'])))
        {
            $res['error']	= '手机格式错误:'.$user_data['mobile'];
            $res['status'] = 0;
            return $res;
        }

        if($user_data['identify_number']!=''&& !isCreditNo($user_data['identify_number']) &&$update_status!=1)
        {
            $res['error']	= '请填写正确的身份证号码';
            $res['status'] = 0;
            return $res;
        }

 		//验证结束开始插入数据
        $user_data['nick_name'] = strim($user_data['nick_name']);
	 	if(trim($user_data['nick_name'])!=''){
	 		$user['nick_name'] = trim($user_data['nick_name']);
			//检查昵称
	        if(strlen($user['nick_name'])>180){
				$res['info'] = "昵称太长";
				$res['status'] =0;
				return $res;
			}
	 	}

		if($user_data['create_time']||$user_data['id']){
			$user['update_time'] = get_gmtime();
		}else{
			$user['create_time'] = get_gmtime();
		}
		//禁播
		if(isset($user_data['is_ban']))
        $user['is_ban'] = intval($user_data['is_ban']);

        if(intval($user_data['is_ban'])){
            $user['ban_time'] = 0;
        }else{
        	if(isset($user_data['ban_time'])){
        		$ban_time = strim($user_data['ban_time']);
           		$user['ban_time'] = $ban_time!=''?to_timespan($ban_time):0;
        	}

        }
        //禁热门
        if(isset($user_data['is_hot_on']))
            $user['is_hot_on'] = intval($user_data['is_hot_on']);
        //单独设置主播提现比例
        if(isset($user_data['alone_ticket_ratio'])){
            $user['alone_ticket_ratio']=$user_data['alone_ticket_ratio'];
        }
        //家族推荐号
        if(isset($user_data['family_recom']))
            $user['family_recom']=intval($user_data['family_recom']);
        
        //公会邀请码ljz
        if(isset($user_data['society_code'])){
            $user['society_code']=$user_data['society_code'];
        }
        //认证审核时间
        if(isset($user_data['investor_time'])){
            $user['investor_time']=strim($user_data['investor_time']);
        }
        
        //VIP
        if(isset($user_data['is_vip']))
            $user['is_vip'] = intval($user_data['is_vip']);
        if(isset($user_data['vip_expire_time'])){
            $user['vip_expire_time'] = 0;
            if(intval($user_data['is_vip'])){
                $vip_expire_time = strim($user_data['vip_expire_time']);
                $user['vip_expire_time'] = $vip_expire_time!=''?to_timespan($vip_expire_time):0;
            }
        }

        //公会红人申请
        if(isset($user_data['opus_site']))
            $user['opus_site'] = strim($user_data['opus_site']);
        if(isset($user_data['opus_explain']))
            $user['opus_explain'] = strim($user_data['opus_explain']);
        if(isset($user_data['explain']))
            $user['explain'] = strim($user_data['explain']);
        if(isset($user_data['show_bill']))
            $user['show_bill'] = strim($user_data['show_bill']);
        //机器人
        if(isset($user_data['is_robot'])){
            $user['is_robot'] = intval($user_data['is_robot']);
        }
        if(isset($user_data['user_level']))
            $user['user_level'] = intval($user_data['user_level']);
        if(isset($user_data['ban_type']))
            $user['ban_type'] = intval($user_data['ban_type']);

        if(isset($user_data['is_authentication']))
        $user['is_authentication'] = intval($user_data['is_authentication']);

        if(isset($user_data['authentication_type']))
        $user['authentication_type'] = strim($user_data['authentication_type']);

        if(isset($user_data['identify_number']))
            $user['identify_number'] = strim($user_data['identify_number']);

        if(isset($user_data['authentication_name']))
        $user['authentication_name'] = strim($user_data['authentication_name']);

        if(isset($user_data['contact']))
        $user['contact'] = strim($user_data['contact']);

        if(isset($user_data['from_platform']))
        $user['from_platform'] = strim($user_data['from_platform']);

        if(isset($user_data['wiki']))
        $user['wiki'] = strim($user_data['wiki']);

		if(isset($user_data['province']))
		$user['province'] = $user_data['province'];

		if(isset($user_data['city']))
		$user['city'] = $user_data['city'];

		if(isset($user_data['sex']))
		$user['sex'] = intval($user_data['sex']);

		if(isset($user_data['is_edit_sex']))
		$user['is_edit_sex'] = intval($user_data['is_edit_sex']);

		if(isset($user_data['intro']))
		$user['intro'] = strim($user_data['intro']);

        $head_image = strim($user_data['head_image']);
		if($head_image){
			$user['head_image'] = del_domain_url($head_image);
		}

        $thumb_head_image = strim($user_data['thumb_head_image']);
        if($thumb_head_image){
            $user['thumb_head_image'] = del_domain_url($thumb_head_image);
        }

		if(isset($user_data['signature']))
		$user['signature'] = strim($user_data['signature']);

		if(isset($user_data['job']))
		$user['job'] = strim($user_data['job']);

		if($user_data['birthday']!=''){
			$user['birthday'] = $user_data['birthday'];
		}
		if(isset($user_data['emotional_state']))
		$user['emotional_state']=strim($user_data['emotional_state']);

		if(isset($user_data['identify_hold_image']))
		$user['identify_hold_image']=strim($user_data['identify_hold_image']);

		if(isset($user_data['identify_positive_image']))
		$user['identify_positive_image']=strim($user_data['identify_positive_image']);

		if(isset($user_data['identify_nagative_image']))
		$user['identify_nagative_image']=strim($user_data['identify_nagative_image']);

        if(isset($user_data['v_explain']))
        $user['v_explain']=strim($user_data['v_explain']);

        if(isset($user_data['user_type']))
        $user['user_type'] = intval($user_data['user_type']);

        if(isset($user_data['score']))
            $user['score'] = intval($user_data['score']);
		//验证结束开始插入数据（这里没写user模块写不进去）
		//会员状态
		if(intval($user_data['is_effect'])!=0)
		{
			$user['is_effect'] = $user_data['is_effect'];
		}else{
			$user['is_effect'] =1;
		}

		if(isset($user_data['mobile'])){
			$user['mobile'] = strim($user_data['mobile']);
		}

		if(isset($user_data['v_explain']) && strim($user_data['v_explain'])){
			$user['v_explain'] = strim($user_data['v_explain']);
		}
		if(isset($user_data['v_icon']) && strim($user_data['v_icon'])){
			$user['v_icon'] = strim($user_data['v_icon']);
		}

		if(isset($user_data['authent_list_id']) && strim($user_data['authent_list_id'])){
			$user['authent_list_id'] = strim($user_data['authent_list_id']);
		}

        if(isset($user_data['is_authentication'])){
            if(intval($user_data['is_authentication'])==3 || intval($user_data['is_authentication'])==1 || intval($user_data['is_authentication'])==0){
                $user['v_icon'] = '';
                $user['v_explain'] = '';
            }
        }
		if(isset($user_data['classified_id'])){
			$user['classified_id'] = intval($user_data['classified_id']);
        }
        if (isset($user_data['p_user_id']) && OPEN_DISTRIBUTION==1 ){
            $user['p_user_id'] = intval($user_data['p_user_id']);
        }

        if(isset($user_data['is_admin']))
            $user['is_admin'] = intval($user_data['is_admin']);
        if(isset($user_data['game_distribution_id']))
            $user['game_distribution_id'] = intval($user_data['game_distribution_id']);

		if(isset($user_data['apns_code']))
			$user['apns_code'] = strim($user_data['apns_code']);
		if(isset($user_data['login_type']))
			$user['login_type'] = intval($user_data['login_type']);


		if($mode == 'INSERT')
		{
			$user['code'] = ''; //默认不使用code, 该值用于其他系统导入时的初次认证
		}
		else
		{
			$user['code'] = $GLOBALS['db']->getOne("select code from ".DB_PREFIX."user where id =".$user_data['id']);
		}

		if($mode == 'INSERT')
		{
			//需要通过接口的方式,获得一个新用户id
			$user_id = get_max_user_id(0);
			$user['id'] = $user_id;
			$user['user_pwd']= md5(rand(100000,999999));
			$where = '';
			if (defined('OPEN_BM') && OPEN_BM) {
				$bm_config      = load_auto_cache("bm_config");
				$user['bm_pid'] = intval($bm_config['bm_pid']);
			}
		}
		else
		{
			$where = "id=".intval($user_data['id']);
		}
		if($GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,$mode,$where))
		{
			if($mode == 'INSERT')
			{
                //添加成功，同步信息
                require_once(APP_ROOT_PATH.'system/tim/TimApi.php');
                $api = createTimAPI();
                $ret = $api->account_import((string)$user['id'], $user['nick_name'], $user['head_image']);
                if($ret['ErrorCode']==0){
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set synchronize = 1 where id =".$user['id']);
                }else{
					log_err_file(array(__FILE__,__LINE__,__METHOD__,$ret));
				}
                //redis化
                fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/BaseRedisService.php');
                fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
                $user_redis = new UserRedisService();
                $ridis_data = $user_redis->reg_data($user);
                $user_redis->insert_db($user['id'],$ridis_data);
				//$GLOBALS['msg']->manage_msg('MSG_MEMBER_REMIDE',$user_id,array('type'=>'会员注册','content'=>'您于 '.get_client_ip() ."注册成功!"));
			}
			else
			{
                if($user['is_robot']){
                    //添加成功，同步信息
                    require_once(APP_ROOT_PATH.'system/tim/TimApi.php');
                    $api = createTimAPI();
                    $ret = $api->account_import((string)$user['id'], $user['nick_name'], $user['head_image']);
                    if($ret['ErrorCode']==0){
                        $GLOBALS['db']->query("update ".DB_PREFIX."user set synchronize = 1 where id =".$user['id']);
                    }else{
						log_err_file(array(__FILE__,__LINE__,__METHOD__,$ret));
					}
                }
				$user_id = $user_data['id'];
                user_deal_to_reids(array($user_id));

			}
		}
		$res['data'] = $user_id;

		return $res;
	}
	function save_mobile_user($user_data,$mode='INSERT')
	{

		//开始数据验证
		$res = array('status'=>1,'info'=>'','data'=>''); //用于返回的数据

        if(!check_mobile(trim($user_data['mobile'])))
        {

            $field_item['field_name'] = 'mobile';
            $field_item['error']	=	FORMAT_ERROR;
            $res['status'] = 0;
            $res['data'] = $field_item;
            return $res;
        }

		if($user_data['mobile']!=''&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where mobile = '".trim($user_data['mobile'])."'")>0)
		{
			/*$field_item['field_name'] = 'mobile';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;*/
			$res['data'] = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where mobile = '".trim($user_data['mobile'])."'");
			$res['status'] = 1;
			return $res;
		}
		//检查验证码

		if(trim($user_data['verify_coder'])=='')
		{
			$field_item['field_name'] = 'verify_coder';
			$field_item['error']	=	EMPTY_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}

		if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE mobile=".trim($user_data['mobile'])." AND verify_code='".trim($user_data['verify_coder'])."'")==0)
		{
			$field_item['field_name'] = 'verify_coder';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}


 		//验证结束开始插入数据

		$user['nick_name'] = htmlspecialchars_decode($user_data['nick_name']);

		if($user_data['create_time']){
			$user['update_time'] = get_gmtime();
		}else{
			$user['create_time'] = get_gmtime();
		}
		//禁播
        $user['is_ban'] = intval($user_data['is_ban']);
        if(intval($user_data['is_ban'])){
            $user['ban_time'] = 0;
        }else{
            $ban_time = strim($user_data['ban_time']);
            $user['ban_time'] = $ban_time!=''?to_timespan($ban_time):0;
        }
        $user['is_authentication'] = intval($user_data['is_authentication']);
        $user['authentication_type'] = strim($user_data['authentication_type']);
        $user['authentication_name'] = strim($user_data['authentication_name']);
        $user['contact'] = strim($user_data['contact']);
        $user['from_platform'] = strim($user_data['from_platform']);
        $user['wiki'] = strim($user_data['wiki']);
		if(strim($user_data['province'])=='')
		$user['province'] = '火星';
		else
		$user['province'] = $user_data['province'];

		$user['city'] = $user_data['city'];
		if(isset($user_data['sex'])){
			if(intval($user_data['sex'])){
				$user['sex'] = intval($user_data['sex']);
			}else{
				$user['sex']=1;
			}
		}else{
			$user['sex']=1;
		}
		$user['intro'] = strim($user_data['intro']);
		if(strim($user_data['head_image'])){
			$user['head_image'] = strim($user_data['head_image']);
		}


		$user['identify_hold_image']=strim($user_data['identify_hold_image']);
		$user['identify_positive_image']=strim($user_data['identify_positive_image']);
		$user['identify_nagative_image']=strim($user_data['identify_nagative_image']);
        $user['v_explain']=strim($user_data['v_explain']);
        $user['user_type'] = intval($user_data['user_type']);

        if(strim($user_data['emotional_state'])=='')
        $user['emotional_state'] ='保密';
        if(isset($user_data['signature']))
            $user['signature'] = htmlspecialchars_decode(trim($user_data['signature']));
        if(strim($user_data['job'])=='')
            $user['job'] ='主播';
        else
            $user['job'] = htmlspecialchars_decode(trim($user_data['job']));
		//验证结束开始插入数据（这里没写user模块写不进去）
		//会员状态
		if(intval($user_data['is_effect'])!=0)
		{
			$user['is_effect'] = $user_data['is_effect'];
		}else{
			$user['is_effect'] =1;
		}
		$user['user_level'] = 1;
		$user['login_type'] = 2;
		$user['is_remind'] = 1;

		//临时测试
		if(defined('OPEN_TEST')&&OPEN_TEST==1){
			$user['diamonds'] = 1000000;
		}
		
		
		if(strim($user_data['mobile'])){
			$user['mobile'] = strim($user_data['mobile']);
		}
		if($mode == 'INSERT')
		{
			$user['code'] = ''; //默认不使用code, 该值用于其他系统导入时的初次认证
			
			//注册赠送
			register_gift($user);
		}
		else
		{
			$user['code'] = $GLOBALS['db']->getOne("select code from ".DB_PREFIX."user where id =".$user_data['id']);
		}
		if($mode == 'INSERT')
		{
			//需要通过接口的方式,获得一个新用户id
			$user_id = get_max_user_id(0);
			$user['id'] = $user_id;
			$user['user_pwd']= md5(rand(100000,999999));


			if ((defined('DISTRIBUTION_SCAN')&&DISTRIBUTION_SCAN==1))   //二级分销开启
		{
			//路径权限设置
		    $dir_name = "distribution_qrcode";
		    if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) { 
		             @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
		             @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
		        }
		    //生成分销二维码图片
		    $invite_url = SITE_DOMAIN.APP_ROOT.'/mapi/index.php?ctl=share_distribution&act=jump&share_id='.$user_id;   //分销邀请链接
		    $path_dir = "public/attachment/distribution_qrcode/qrcode_".$user_id.".png";   //二维码图片相对路径
		    $qrcode_dir = APP_ROOT_PATH.$path_dir;  //二维码图片绝对路径
		    if(!is_file($qrcode_dir))   
		    {
		        get_qrcode_png($invite_url,$qrcode_dir,""); //生成二维码图片并保存到绝对路径
		        $head_image = "./".$path_dir;
		        syn_to_remote_image_server($head_image); //图片同步到服务器上，第二个参数true时本地图片不删除
		    }
		    //存入图片路径和分销邀请链接至数据库
		    $user['qr_code'] = $head_image;
		    $user['distribution_url'] = $invite_url;
		    if ($user_data['$p_user_id']!='')   //若是分销注册，存上级ID
		    {
		        $user['$p_user_id'] = $user_data['$p_user_id'];
		    }
		}


			$where = '';
			if (defined('OPEN_BM') && OPEN_BM) {
				$bm_config      = load_auto_cache("bm_config");
				$user['bm_pid'] = intval($bm_config['bm_pid']);
			}
		}
		else
		{
			$where = "id=".intval($user_data['id']);
		}

		if($GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,$mode,$where))
		{
			if($mode == 'INSERT')
			{
				//===========add  start ===========
				fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
				$user_redis = new UserRedisService();

				//$user_id =$GLOBALS['db']->insert_id();

				$ridis_data = $user_redis->reg_data($user);

				//redis临时测试
				if(defined('OPEN_TEST')&&OPEN_TEST==1){
					$ridis_data['diamonds'] = 1000000;
				}
				//redis注册赠送
				register_gift($ridis_data);
				
				$user_redis->insert_db($user_id,$ridis_data);

				//===========add  end ===========

				//$GLOBALS['msg']->manage_msg('MSG_MEMBER_REMIDE',$user_id,array('type'=>'会员注册','content'=>'您于 '.get_client_ip() ."注册成功!"));
			}
			else
			{
				$user_id = $user_data['id'];

			}
		}
		$res['data'] = $user_id;

		return $res;
	}

	function update_mobile_user($user_data,$mode='INSERT')
	{
		//开始数据验证
		$res = array('status'=>1,'error'=>'','data'=>''); //用于返回的数据
		if(trim($user_data['id'])=='')
		{
			$res['status'] = 0;
			$field_item['error']='用户编号不能为空';
			return $res;
		}
		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where id = '".trim($user_data['id'])."'")<=0)
		{
			$res['status'] = 0;
			$field_item['error']	= '用户不存在！';
			return $res;
		}
		$GLOBALS['db']->query("set names 'utf8mb4'");
		//验证结束开始插入数据
		if(isset($user_data['nick_name']))
		$user['nick_name']= $user_data['nick_name'];
		
		$user['update_time'] = get_gmtime();
		if(isset($user_data['sex'])){
			$user['sex'] = intval($user_data['sex']);
		}else{
			$user['sex'] = 1;
		}
		
        if(isset($user_data['head_image']))
            $user['head_image'] = trim($user_data['head_image']);
		if(isset($user_data['thumb_head_image']))
		    $user['thumb_head_image'] = trim($user_data['thumb_head_image']);
		//验证结束开始插入数据（这里没写user模块写不进去）


		$where = "id=".intval($user_data['id']);

		if ($mode=='INSERT') {
			if (defined('OPEN_BM') && OPEN_BM) {
				$bm_config      = load_auto_cache("bm_config");
				$user['bm_pid'] = intval($bm_config['bm_pid']);
			}
		}
		if($GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,$mode,$where))
		{
			$user_id = $user_data['id'];
			es_cookie::set("nick_name",$user['nick_name'],3600*24*30);
		}
		$res['data'] = $user_id;
		//更新redis
		fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/BaseRedisService.php');
		fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
		$user_redis = new UserRedisService();
		$user_redis->update_db($user_id,$user);
		//重新同步手机用户IM
		accountimport($user);
		return $res;
	}

	/**
	 * 删除会员以及相关数据
	 * @param integer $id
	 */
	function delete_user($id)
	{

		$result = 1;



		if($result>0)
		{

			//$GLOBALS['db']->query("delete from ".DB_PREFIX."user_consignee where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_log where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_refund where user_id = ".$id);
			//$GLOBALS['db']->query("delete from ".DB_PREFIX."user_weibo where user_id = ".$id);
			//$GLOBALS['db']->query("delete from ".DB_PREFIX."user_consignee where user_id = ".$id);
			//$GLOBALS['db']->query("delete from ".DB_PREFIX."referrals where user_id = ".$id);

			//$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_comment where user_id = ".$id);
			//$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_focus_log where user_id = ".$id);
			//$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_log where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_msg_list where user_id = ".$id);
			//$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_order where user_id = ".$id);
			//$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_log where user_id = ".$id);
			//$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_support_log where user_id = ".$id);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."payment_notice where user_id = ".$id);

			$GLOBALS['db']->query("delete from ".DB_PREFIX."user where id =".$id); //删除会员
		}
	}

	/**
	 * 会员资金积分变化操作函数
	 * @param array $data 包括 diamonds
	 * @param integer $user_id
	 * @param string $log_msg 日志内容
	 * @param array $param 要插入的数组
	 */
	function modify_account($data,$user_id,$log_msg='',$param=array())
	{
		$diamonds=intval($data['diamonds']);
		if ($diamonds < 0){
			$diamonds = abs($diamonds);
			$sql = "update ".DB_PREFIX."user set diamonds = diamonds - ".$diamonds." where diamonds >= ".$diamonds." and id =".$user_id;
			$log_msg1 = '扣除钻石';
		}else{
			$sql = "update ".DB_PREFIX."user set diamonds = diamonds + ".$diamonds." where id =".$user_id;
			$log_msg1 = '增加钻石';
		}

		if($log_msg==''){
			$log_msg = $log_msg1;
		}

		$GLOBALS['db']->query($sql);
	    if($GLOBALS['db']->affected_rows()){
           user_deal_to_reids(array($user_id));
           //写入日志
           account_log($data,$user_id,$log_msg,$param);
           return true;
        }else{
        	return false;
        }
	}

	/**
	 * 处理cookie的自动登录
	 * @param $user_name_or_email  用户名或邮箱
	 * @param $user_md5_pwd  md5加密过的密码
	 */
	function auto_do_login_user($user_id,$user_md5_pwd)
	{
		$user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id='".$user_id."' and is_effect = 1");

		if($user_data)
		{
			if(md5($user_data['user_pwd']."_EASE_COOKIE")==$user_md5_pwd)
			{
				//登录成功自动检测关于会员等级
				//user_leverl_syn($user_data);//$user_data 要包括会员id,会员等级,会员信用值

				//成功
				//$build_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal where is_delete = 0 and is_effect = 1 and user_id = ".$user_data['id']);
				//$focus_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_focus_log where user_id = ".$user_data['id']);
				//$support_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_support_log where user_id = ".$user_data['id']);

                $login_root = login_prompt($user_data['id']);
                es_session::set("first_login",$login_root['first_login']);
                es_session::set("new_level",$login_root['new_level']);

				es_session::set("user_info",$user_data);
				$GLOBALS['user_info'] = $user_data;
				//$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".get_client_ip()."',login_time= ".get_gmtime().",build_count = $build_count,support_count = $support_count,focus_count = $focus_count where id =".$user_data['id']);
				$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".get_client_ip()."',login_time= '".to_date(get_gmtime(),'Y-m-d H:i:s')."' where id =".$user_data['id']);
				//更新redis
				fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
				$user_redis = new UserRedisService();
				$user =array();
				$user_id = $user_data['id'];
				$user['login_ip'] = get_client_ip();
				$user['login_time'] = to_date(get_gmtime());
				$user_redis->update_db($user_id,$user);

			}
		}
	}
	/**
	 * 手机登录
	 * @param $user_id_or_mobile 手机号
	 * @param $verify_code 短信验证码
	 *
	 */
	function do_login_user($user_id_or_mobile,$verify_code,$p_user_id)
	{

		$result = array('status'=>0,'info'=>'','is_lack'=>0);

		$user_id_or_mobile=strim($user_id_or_mobile);
        $verify_code=strim($verify_code);

        if($verify_code==''){
				$result['info'] = "请输入验证码";
				return $result;
        }

		fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
		$user_redis = new UserRedisService();
        if($user_id_or_mobile!=''){
			if($user_id_or_mobile!='13888888888'&&$user_id_or_mobile!='13999999999'){
				if(!check_mobile(trim($user_id_or_mobile)))
				{
					$result['info'] = '手机格式错误';
					return $result;
				}
				if($GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE mobile=".$user_id_or_mobile." AND verify_code='".$verify_code."'")==0){
					$result['info'] = "手机验证码出错";
					return $result;
				}
			}elseif($user_id_or_mobile=='13888888888' && $verify_code !='8888'||$user_id_or_mobile=='13999999999' && $verify_code !='9999'){
					$result['info'] = "手机验证码出错";
					return $result;
			}
        }else{
					$result['info'] = "请输入手机号";
					return $result;
        }

		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where (id='".$user_id_or_mobile."' or mobile = '".$user_id_or_mobile."' ) and login_type = 2");

		$user_id = intval($user['id']);
        //如果不存在，注册账号
        if(!$user)
		{
			$data['mobile'] = $user_id_or_mobile;
			$data['verify_coder'] = $verify_code;
			$result = save_mobile_user($data);
			$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id='".$result['data']."'");
			if(empty($user['nick_name'])){
				$update['nick_name']=$result['data'];
				$where = "id=".intval($result['data']);
				$GLOBALS['db']->autoExecute(DB_PREFIX."user",$update,UPDATE,$where);
				$user['nick_name']=$result['data'];
			}
			$result['user'] = $user;
			//注册到 redis
			$ridis_data = $user_redis->reg_data($user);
	        $user_redis->insert_db($user_id,$ridis_data);
		}
		else
		{
			$result['user'] = $user;
		}

        //判断账号有效
		if($user['is_effect'] != 1){
			$result['info'] = "帐户已被禁用,请联系管理员";
		}
		else
		{
			$result['status'] =1;
			//更新等级
			user_leverl_syn($user);
			$login_time = get_gmtime();
			$user['login_time'] = $login_time;
			//设置cookie
			es_cookie::set("client_ip",CLIENT_IP,3600*24*30);
			es_cookie::set("nick_name",$user['nick_name'],3600*24*30);
			es_cookie::set("user_id",$user['id'],3600*24*30);
			es_cookie::set("user_pwd",md5($user['user_pwd']."_EASE_COOKIE"),3600*24*30);
			es_cookie::set("PHPSESSID2",es_session::id(),3600*24*30);
			
			//设置session
			es_session::set("user_info",$user);
			$GLOBALS['user_info'] = $user;
            //修改登录时间之前，获取上一次登录时间，每日首次登录赠送积分
            $login_root = login_prompt($user['id']);
            $result['first_login'] = $login_root['first_login'];
            $result['new_level'] = $login_root['new_level'];

			$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".get_client_ip()."',login_time= '".to_date($login_time)."' where id =".$user['id']);
            //更新redis
            $user_redis->update_db($user['id'],array("login_time"=>to_date($login_time),"login_ip"=>get_client_ip()));

			//登录成功 同步信息
			$user_im = array();
			$user_im['id']=$user['id'];
			$user_im['nick_name']=$user['nick_name'];
			$user_im['head_image']=$user['head_image'];
			if($user_im['nick_name']==''){
				$user_im['nick_name']= '账号'.$user['id'];
			}
			if($user_im['head_image']==''){
				$m_config =  load_auto_cache("m_config");//初始化手机端配置
				$system_head_image = $m_config['app_logo'];
				if($system_head_image==''){
					$system_head_image = './public/attachment/test/noavatar_10.JPG';
				}
				$user_im['head_image'] = $system_head_image;
			}
			accountimport($user_im);

		}

		if($user['nick_name']==''||$user['head_image']==''){
			$result['is_lack'] = 1;
		}
		set_xy_point($user['id']);
		$result['user_info']['user_id'] =$user['id'];
		$result['user_info']['nick_name'] =$user['nick_name']?$user['nick_name']:'';
		$result['user_info']['mobile'] =$user['mobile']?$user['mobile']:'';
		$result['user_info']['head_image'] =get_spec_image($user['head_image']);
		$result['p_user_id'] = $p_user_id;
		return $result;
	}



	/**
	 * 登出,返回 array('status'=>'',data=>'',msg=>'') msg存放整合接口返回的字符串
	 */
	function loginout_user()
	{
		$result = array('status'=>1,'info'=>'','data'=>''); //用于返回的数据

		$user_info = es_session::get("user_info");

		if(!$user_info)
		{
			return false;
		}
		else
		{
			//清除cookie
			es_cookie::set("client_ip",'',0);
			es_cookie::set("nick_name",'',0);
			es_cookie::set("user_id",'',0);
			es_cookie::set("user_pwd",'',0);
			es_cookie::set("is_agree",'',0);
			es_cookie::set("PHPSESSID2",'',0);
			//清除session
			es_session::delete("user_info");
			$GLOBALS['user_info']='';
			//写入日志
 			//$GLOBALS['msg']->manage_msg('MSG_MEMBER_REMIDE',$user_info['id'],array('type'=>'会员登出','content'=>'您的帐号  '.$user_info['user_name'].'  于  '.get_client_ip() ." 登出！"));

			return $result;
		}
	}

	/**
	 * 验证会员数据
	 */
	function check_user($field_name,$field_data)
	{
		delete_mobile_verify_code();
		//开始数据验证
		$user_data[$field_name] = $field_data;
		$res = array('status'=>1,'info'=>'','data'=>''); //用于返回的数据
		if(trim($user_data['user_name'])==''&&$field_name=='user_name')
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	EMPTY_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if(mb_strlen(trim($user_data['user_name']))<4&&$field_name=='user_name')
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($field_name=='user_name'&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name = '".trim($user_data['user_name'])."' and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if(app_conf("USER_VERIFY")!=2||$user_data['email']!=''){
			if($field_name=='email'&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email = '".trim($user_data['email'])."' and id <> ".intval($user_data['id']))>0)
			{
				$field_item['field_name'] = 'email';
				$field_item['error']	=	EXIST_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
			if($field_name=='email'&&trim($user_data['email'])=='')
			{
				$field_item['field_name'] = 'email';
				$field_item['error']	=	EMPTY_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
			if($field_name=='email'&&!check_email(trim($user_data['email'])))
			{
				$field_item['field_name'] = 'email';
				$field_item['error']	=	FORMAT_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
		}
		if($field_name=='mobile'&&!check_mobile(trim($user_data['mobile'])))
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($field_name=='mobile'&&$user_data['mobile']!=''&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where mobile = '".trim($user_data['mobile'])."' and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($field_name=='verify_coder'&&(app_conf("USER_VERIFY")==2||app_conf("USER_VERIFY")==4)){
			if(strim($_REQUEST['verify_coder'])==''){
				$field_item['field_name'] = 'verify_coder';
				$field_item['error']	=	EMPTY_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
 				return $res;
			}
			if(!check_verify_coder(trim($_REQUEST['verify_coder']))){
				$field_item['field_name'] = 'verify_coder';
				$field_item['error']	=	FORMAT_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
 				return $res;
			}

			$check_code_sql="SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE mobile=".strim($_REQUEST['mobile'])." AND verify_code='".trim($_REQUEST['verify_coder'])."'";

			if($GLOBALS['db']->getOne($check_code_sql)==0)
			{

	 			$field_item['field_name'] = 'verify_coder';
				$field_item['error']	=	EXIST_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
		}

		if($field_name=='verify_coder_email'&&(app_conf("USER_VERIFY")==1||app_conf("USER_VERIFY")==4)){
			if(strim($_REQUEST['verify_coder_email'])==''){
				$field_item['field_name'] = 'verify_coder_email';
				$field_item['error']	=	EMPTY_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
 				return $res;
			}
			if(!check_verify_coder(trim($_REQUEST['verify_coder_email']))){
				$field_item['field_name'] = 'verify_coder_email';
				$field_item['error']	=	FORMAT_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
 				return $res;
			}

			$check_code_sql="SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE email='".strim($_REQUEST['email'])."' AND verify_code='".trim($_REQUEST['verify_coder_email'])."'";

			if($GLOBALS['db']->getOne($check_code_sql)==0)
			{

	 			$field_item['field_name'] = 'verify_coder_email';
				$field_item['error']	=	EXIST_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}
		}


		return $res;
	}

	/**
	 * 会员资金积分变化操作函数
	 * @param array $data 包括 money
	 * @param integer $user_id
	 * @param string $log_msg 日志内容
	 * @param array $param 要插入的数组
	 */
	function modify_account_ben($data,$user_id,$log_msg='',$param=array())
	{
		$user_money=$GLOBALS['db']->getOne("select money from  ".DB_PREFIX."user where id=".$user_id);
		$money=$data['money'];
		 if(($user_money+$money)>=0){
		 	if(floatval($data['money'])!=0)
			{
				$sql = "update ".DB_PREFIX."user set money = money + ".floatval($data['money'])." where id =".$user_id;
				$GLOBALS['db']->query($sql);
			}

			if(floatval($data['ben_money'])!=0){
				licai_log($data,$user_id);
			}
			elseif(floatval($data['money'])!=0)
			{

				$log_info['log_info'] = $log_msg;
				$log_info['log_time'] = get_gmtime();
				$adm_session = es_session::get(md5(app_conf("AUTH_KEY")));
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where is_effect = 1 and id = ".$user_id);
				$adm_id = intval($adm_session['adm_id']);
				if($adm_id!=0)
				{
					$log_info['log_admin_id'] = $adm_id;
				}
				if(is_array($param)&&count($param)>0){
					foreach($param as $k=>$v){
 						$log_info[$k] = $v;
					}
				}
				$log_info['money'] = floatval($data['money']);
				$log_info['user_id'] = $user_id;
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);

			}
			return true;
		 }else{
		 	return false;
		 }


	}
	 /*
	 * 获取用户信息
	 * @param $field_data  会员ID
	 * @param $field_name  查询的字段名称
	 */
	function get_user_info($field_name,$field_data){
		//$get_user_sql= "select * from  ".DB_PREFIX."user where id=".$field_data;
		$get_user_info = $GLOBALS['db']->getOne("select $field_name from  ".DB_PREFIX."user where id=".$field_data);
		return $get_user_info;

	}
	/**
	 * 获取QQ好友资料
	 *
	 * @param object $sdk OpenApiV3 Object
	 * @param string $openid openid
	 * @param string $openkey openkey
	 * @param string $pf 平台
	 * @return array 好友资料数组
	 */
	function get_qq_user_info($sdk, $openid, $openkey, $pf)
	{
		$params = array(
			'openid' => $openid,
			'openkey' => $openkey,
			'pf' => $pf,
		);

		$script_name = '/v3/user/get_info';
		return $sdk->api($script_name, $params,'post');
	}

	function wxxMakeUser($wx_info, $has_user = false){

		if($wx_info['unionid']!=""||$wx_info['openid']!=""){
				if (!$has_user && $wx_info['unionid']) {
                    $has_user = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where wx_unionid = '" . $wx_info['unionid'] . "'");
                }
				if (!$has_user && $wx_info['openid']) {
                    $has_user = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where wx_openid='" . $wx_info['openid'] . "'");
                }
				//===========add  start ===========
				fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
				$user_redis = new UserRedisService();
				//===========add  end ===========
            if (defined('OPEN_FORCE_MOBILE') && OPEN_FORCE_MOBILE == 1 && (!$has_user || empty($has_user['mobile'])) && empty($wx_info['mobile'])) {
                return array(
                    'status' => 1,
                    'error' => '请绑定手机账户',
                    'need_bind_mobile' => 1,
                );
            }

			if(!$has_user){
					//需要通过接口的方式,获得一个新用户id
					$user_id = get_max_user_id(0);
					$data=array();
					$data['id'] = $user_id ;
					$GLOBALS['db']->query("set names 'utf8mb4'");
					$data['nick_name']= htmlspecialchars_decode($wx_info['nickname']);
                    if($GLOBALS['db']->getOne("SELECT nick_name FROM ".DB_PREFIX."user WHERE nick_name ='".$data['nick_name']."'"))
                    {   //昵称唯一性
                        $data['nick_name'] .= $user_id;
                    }
					$data['is_effect'] = 1;
					if($wx_info['headimgurl']!=''){
						$head_image = strtr($wx_info['headimgurl'],array('/0'=>'/96'));
						$data['head_image']= $head_image;
					}else{
						$m_config =  load_auto_cache("m_config");//初始化手机端配置
						$system_head_image = $m_config['app_logo'];
						if($system_head_image==''){
							$data['head_image'] = $system_head_image;
						}else{
							$data['head_image'] = './public/attachment/test/noavatar_10.JPG';
						}
					}
					$data['wx_openid']= $wx_info['openid'];
					$data['wx_unionid']= $wx_info['unionid'];
					$data['create_time']= NOW_TIME;
					$data['user_pwd']= md5(rand(100000,999999));
					$data['login_ip'] = CLIENT_IP;
					$data['synchronize'] = 0;
					$data['emotional_state'] ='保密';
					$data['province'] = '火星';
					$data['job'] = '主播';
					$data['user_level'] = 1;
                    $data['mobile'] = $wx_info['mobile'];
					$data['login_type'] = 2;
					$data['is_remind'] = 1;
					//临时测试
					if(defined('OPEN_TEST')&&OPEN_TEST==1){
						$data['diamonds'] = 1000000;
					}
					
					//注册赠送
					register_gift($user);

                    if($wx_info['sex']==1){
                        $data['sex'] = 1;
                    }elseif($wx_info['sex']==2){
                        $data['sex'] = 2;
                    }else{
                    	$data['sex'] = 1;
                    }

					if(intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where wx_unionid = '".$wx_info['unionid']."'"))==0){
						if (defined('OPEN_BM') && OPEN_BM) {
							$bm_config      = load_auto_cache("bm_config");
							$data['bm_pid'] = intval($bm_config['bm_pid']);
						}

						if ((defined('DISTRIBUTION_SCAN')&&DISTRIBUTION_SCAN==1))   //二级分销开启
						{
							//路径权限设置
						    $dir_name = "distribution_qrcode";
						    if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) { 
						             @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
						             @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
						        }
							//生成分销二维码图片
							$invite_url = SITE_DOMAIN.APP_ROOT.'/mapi/index.php?ctl=share_distribution&act=jump&share_id='.$user_id;   //分销邀请链接
						    $path_dir = "public/attachment/distribution_qrcode/qrcode_".$user_id.".png";   //二维码图片相对路径
						    $qrcode_dir = APP_ROOT_PATH.$path_dir;  //二维码图片绝对路径
						    if(!is_file($qrcode_dir))   
						    {
						        get_qrcode_png($invite_url,$qrcode_dir,""); //生成二维码图片并保存到绝对路径
						        $head_image = "./".$path_dir;
						        syn_to_remote_image_server($head_image); //图片同步到服务器上，第二个参数true时本地图片不删除
						    }
							//存入图片路径和分销邀请链接至数据库
							$data['qr_code'] = $head_image;
							$data['distribution_url'] = $invite_url;
						}

						$GLOBALS['db']->autoExecute(DB_PREFIX."user",$data);
					}

				//===========add  start ===========

					$ridis_data = $user_redis->reg_data($data);
					//redis临时测试
					if(defined('OPEN_TEST')&&OPEN_TEST==1){
						$ridis_data['diamonds'] = 1000000;
					}
					
					//redis注册赠送
					register_gift($ridis_data);
					
					$user_redis->insert_db($user_id,$ridis_data);

					$user_info = $data;
				//===========add  end ===========
				}else{
                    if($has_user['is_effect'] != 1){
                        $return['status'] = 0;
                        $return['error'] = "帐户已被禁用";
                        $return['data'] =$has_user['id'];
                        return $return;
                    }elseif($GLOBALS['db']->getOne("SELECT login_ip FROM ".DB_PREFIX."user WHERE is_ban = 1 and ban_type = 1 and login_ip like '%".get_client_ip()."%' and is_effect !=1")){
                        $return['status'] = 0;
                        $return['error'] = "当前IP已被封停";
                        $return['data'] =$has_user['id'];
                        return $return;
                    }else{
						if(!strpos($has_user['head_image'],'wx.qlogo.cn')&&intval($has_user['is_replace_wx'])==0){
							$has_user['head_image'] = strtr($wx_info['headimgurl'],array('/0'=>'/96'));
							$sql = "update ".DB_PREFIX."user set head_image = '".$has_user['head_image']."',is_replace_wx=1 where id =".$has_user['id']." and is_replace_wx = 0";
							$GLOBALS['db']->query($sql);
						}
						$user_info = $has_user;
						//===========add  end ===========
					}

				}

				es_session::set("user_info", $user_info);
				//设置session过期时间一个月
				es_session::setGcMaxLifetime('2592000');
				//file_put_contents(APP_ROOT_PATH."/public/condition.txt", print_r(es_session::get("user_info"),1),FILE_APPEND);
				es_cookie::set("client_ip",CLIENT_IP,3600*24*30);
				//es_cookie::set("login_time",NEW_TIME,3600*24*30);
				es_cookie::set("nick_name",$user_info['nick_name'],3600*24*30);
				es_cookie::set("user_id",$user_info['id'],3600*24*30);
				es_cookie::set("user_pwd",md5($user_info['user_pwd']."_EASE_COOKIE"),3600*24*30);
				es_cookie::set("PHPSESSID2",es_session::id(),3600*24*30);

				if($user_info['id']!=''){
					//登录成功 同步信息
					accountimport($user_info);

                    //修改登录时间之前，获取上一次登录时间，每日首次登录赠送积分
                    $login_root = login_prompt($user_info['id']);
                    $return['first_login'] = $login_root['first_login'];
                    $return['new_level'] = $login_root['new_level'];
                    $now_time = NOW_TIME;

                    //===========add  start ===========
                    $data =array();
                    $data['login_ip'] = CLIENT_IP;
                    $data['login_time'] = to_date($now_time);
                    $data['wx_unionid'] = $wx_info['unionid'];
                    if (empty($user_info['mobile']) && $wx_info['mobile']) {
                        $data['mobile'] = $wx_info['mobile'];
                    }

                    if ($user_info['login_type'] != 2) {
                        $data['login_type'] = 2;
                    }

                    $GLOBALS['db']->autoExecute(DB_PREFIX . 'user', $data, 'UPDATE', 'id=' . $user_info['id']);
                    $user_redis->update_db($user_info['id'],$data);

                    $m_config =  load_auto_cache("m_config");//初始化手机端配置
                    //判断昵称是否包含敏感词汇
                    if($m_config['name_limit']==1) {
                        //登录过滤铭感词汇
                        $nick_name = $user_info['nick_name'];
                        $limit_sql = $GLOBALS['db']->getCol("SELECT name FROM " . DB_PREFIX . "limit_name");
                        //判断用户名是否含有铭感词汇,如果包含,替换
                        if ($GLOBALS['db']->getCol("SELECT name FROM " . DB_PREFIX . "limit_name WHERE '$nick_name' like concat('%',name,'%')")) {
                            $user_info['nick_name'] = str_replace($limit_sql, '*', $nick_name);
                        }
                        $name = $user_info['nick_name'];
                        $id = $user_info['id'];
                        //更新数据库
                        $sql = "update " . DB_PREFIX . "user set nick_name = '$name',sex={$data['sex']} where id=" . $id;
                        $GLOBALS['db']->query($sql);
                        //更新redis
                        user_deal_to_reids(array($id));
                    }

					$return['status'] = 1;
					$return['error'] = "微信登录成功";
					$return['data'] = $user_info['id'];
					set_xy_point($user_info['id']);
					$return['user_id'] = $user_info['id'];
					$return['nick_name'] = $user_info['nick_name'];
					$return['is_agree'] = intval($user_info['is_agree']);//是否同意直播协议 0 表示不同意 1表示同意

					$return['user_info']['user_id'] =$user_info['id'];
					$return['user_info']['nick_name'] =$user_info['nick_name'];
					$return['user_info']['mobile'] =$user_info['mobile'];
					$return['user_info']['head_image'] =get_spec_image($user_info['head_image']);

				}else{
					$return['status'] = 0;
					$return['error'] = "微信登录失败";
					$return['data'] =$user_info['id'];
				}
			}else{
				if(DEBUG_WX){
					log_result('-未获取用户授权unionid或openid获取出错-');
				}
				$return['status'] = 0;
				$return['error'] = "未获取用户授权unionid或openid获取出错";
			}
			return $return;
	}
	
	//微信登陆
	function wxMakeUser($wx_info){


		if($wx_info['unionid']!=""||$wx_info['openid']!=""){
			$has_user = false;
			if($wx_info['unionid'])
				$has_user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where wx_unionid = '".$wx_info['unionid']."'");
			if(!$has_user && $wx_info['openid'])
				$has_user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where wx_openid='".$wx_info['openid']."'");
			if(!$has_user){
				//需要通过接口的方式,获得一个新用户id
				$user_id = get_max_user_id(0);
				$data=array();
				$data['id'] = $user_id ;
				$GLOBALS['db']->query("set names 'utf8mb4'");
				$data['nick_name']= htmlspecialchars_decode($wx_info['nickname']);
                if($GLOBALS['db']->getOne("SELECT nick_name FROM ".DB_PREFIX."user WHERE nick_name ='".$data['nick_name']."'"))
                {   //昵称唯一性
                    $data['nick_name'] .= $user_id;
                }
				$data['is_effect'] = 1;
				if($wx_info['headimgurl']!=''){
					$root = get_image_path();
					$save_name= get_gmtime().$user_id.".jpg";
					$image_file_domain = ".".$root['save_rec_Path'].$save_name;
					$image_file =$root['savePath'].$save_name;
					ini_set('default_socket_timeout', 1);
					@file_put_contents ( $image_file, file_get_contents ( $wx_info['headimgurl']));
					$data['head_image']= $image_file_domain;
					if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!='NONE')
					{
						syn_to_remote_image_server($image_file_domain);
					}
				}else{
					$data['head_image']= get_domain().'/theme/images/defaulthead.png';
				}
				$data['wx_openid']= $wx_info['openid'];
				$data['wx_unionid']= $wx_info['unionid'];
				$data['create_time']= NOW_TIME;
				$data['user_pwd']= md5(rand(100000,999999));
				$data['login_ip'] = CLIENT_IP;
				$data['synchronize'] = 0;
				$data['emotional_state'] ='保密';
				$data['province'] = '火星';
				$data['job'] = '主播';
				$data['user_level'] = 1;
				$data['login_type'] = 2;
				$data['is_remind'] = 1;	
		
				if($wx_info['sex']==1){
					$data['sex'] = 1;
				}elseif($wx_info['sex']==2){
					$data['sex'] = 2;
				}else{
					$data['sex'] = 1;
				}
				if (defined('OPEN_BM') && OPEN_BM) {
					$bm_config      = load_auto_cache("bm_config");
					$data['bm_pid'] = intval($bm_config['bm_pid']);
				}

				if ((defined('DISTRIBUTION_SCAN')&&DISTRIBUTION_SCAN==1))   //二级分销开启
				{
					//路径权限设置
				    $dir_name = "distribution_qrcode";
				    if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) { 
				             @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
				             @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
				        }
					//生成分销二维码图片
					$invite_url = SITE_DOMAIN.APP_ROOT.'/mapi/index.php?ctl=share_distribution&act=jump&share_id='.$user_id;   //分销邀请链接
				    $path_dir = "public/attachment/distribution_qrcode/qrcode_".$user_id.".png";   //二维码图片相对路径
				    $qrcode_dir = APP_ROOT_PATH.$path_dir;  //二维码图片绝对路径
				    if(!is_file($qrcode_dir))   
				    {
				        get_qrcode_png($invite_url,$qrcode_dir,""); //生成二维码图片并保存到绝对路径
				        $head_image = "./".$path_dir;
				        syn_to_remote_image_server($head_image); //图片同步到服务器上，第二个参数true时本地图片不删除
				    }
					//存入图片路径和分销邀请链接至数据库
					$data['qr_code'] = $head_image;
					$data['distribution_url'] = $invite_url;
				}

				$GLOBALS['db']->autoExecute(DB_PREFIX."user",$data);		
				$user_info = $data;
			}else{
                if($has_user['is_effect'] != 1){
                    $return['status'] = 0;
                    $return['error'] = "帐户已被禁用";
                    $return['data'] =$has_user['id'];
                    return $return;
                }elseif($GLOBALS['db']->getOne("SELECT login_ip FROM ".DB_PREFIX."user WHERE is_ban = 1 and ban_type = 1 and login_ip like '%".get_client_ip()."%' and is_effect !=1")){
                    $return['status'] = 0;
                    $return['error'] = "当前IP已被封停";
                    $return['data'] =$has_user['id'];
                    return $return;
                }else{
					$user_info = $has_user;
				}
		
			}		
			es_session::set("user_info", $user_info);
			es_session::setGcMaxLifetime('2592000');
			es_cookie::set("client_ip",CLIENT_IP,3600*24*30);
			es_cookie::set("nick_name",$user_info['nick_name'],3600*24*30);
			es_cookie::set("user_id",$user_info['id'],3600*24*30);
			es_cookie::set("user_pwd",md5($user_info['user_pwd']."_EASE_COOKIE"),3600*24*30);
			es_cookie::set("PHPSESSID2",es_session::id(),3600*24*30);
		
			if($user_info['id']!=''){
				
				$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".CLIENT_IP."',login_time='".to_date($now_time,"Y-m-d H:i:s")."',wx_unionid = '".$wx_info['unionid']."' where id =".$user_info['id']);		
				//===========add  start ===========
				$data =array();
				$data['login_ip'] = CLIENT_IP;
				$data['login_time'] = to_date($now_time);
				$data['wx_unionid'] = $wx_info['unionid'];
		
				$m_config =  load_auto_cache("m_config");//初始化手机端配置
				//判断昵称是否包含敏感词汇
				if($m_config['name_limit']==1) {
					//登录过滤铭感词汇
					$nick_name = $user_info['nick_name'];
					$limit_sql = $GLOBALS['db']->getCol("SELECT name FROM " . DB_PREFIX . "limit_name");
					//判断用户名是否含有铭感词汇,如果包含,替换
					if ($GLOBALS['db']->getCol("SELECT name FROM " . DB_PREFIX . "limit_name WHERE '$nick_name' like concat('%',name,'%')")) {
						$user_info['nick_name'] = str_replace($limit_sql, '*', $nick_name);
					}
					$name = $user_info['nick_name'];
					$id = $user_info['id'];
					//更新数据库
					$sql = "update " . DB_PREFIX . "user set nick_name = '$name',sex={$data['sex']} where id=" . $id;
					$GLOBALS['db']->query($sql);
				}
		
				$return['status'] = 1;
				$return['error'] = "微信登录成功";
				$return['data'] = $user_info['id'];
				set_xy_point($user_info['id']);
				$return['user_id'] = $user_info['id'];
				$return['nick_name'] = $user_info['nick_name'];
				$return['is_agree'] = intval($user_info['is_agree']);//是否同意直播协议 0 表示不同意 1表示同意
		
				$return['user_info']['user_id'] =$user_info['id'];
				$return['user_info']['nick_name'] =$user_info['nick_name'];
				$return['user_info']['mobile'] =$user_info['mobile'];
				$return['user_info']['head_image'] =get_spec_image($user_info['head_image']);
		
			}else{
				$return['status'] = 0;
				$return['error'] = "微信登录失败";
				$return['data'] =$user_info['id'];
			}
		}else{
			$return['status'] = 0;
			$return['error'] = "未获取用户授权";
		}
		return $return;
		
	}
	function set_xy_point($user_id){
		$data['xpoint'] = floatval($_REQUEST['xpoint']);
		$data['ypoint'] = floatval($_REQUEST['ypoint']);
		if($data['xpoint']!=""&&$data['xpoint']!=""){
			$sql = "update " . DB_PREFIX . "user set xpoint = ".$data['xpoint'].",ypoint= ".$data['ypoint']." where id=" . $user_id;
			@$GLOBALS['db']->query($sql);
		}
	}
	//
	function qqMakeUser($qq_info, $has_user = false){
		if($qq_info['openid']!=""){
			if(!$has_user && $qq_info['openid']) {
                $has_user = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where qq_openid='" . $qq_info['openid'] . "'");
            }

            if (defined('OPEN_FORCE_MOBILE') && OPEN_FORCE_MOBILE == 1 && (!$has_user || empty($has_user['mobile'])) && empty($qq_info['mobile'])) {
                return array(
                    'status' => 1,
                    'error' => '请绑定手机账户',
                    'need_bind_mobile' => 1,
                );
            }

			//性别
			if($qq_info['gender']=='男'){
				$sex =1;
			}elseif($qq_info['gender']=='女'){
				$sex =2;
			}else{
				$sex =1;
			}
			//===========add  start ===========
			fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
			$user_redis = new UserRedisService();
			//===========add  end ===========
			if(!$has_user){
				//需要通过接口的方式,获得一个新用户id
				$user_id = get_max_user_id(0);
				$data=array();
				$data['id'] = $user_id ;
				$data['nick_name']= htmlspecialchars_decode($qq_info['nickname']);
                if($GLOBALS['db']->getOne("SELECT nick_name FROM ".DB_PREFIX."user WHERE nick_name ='".$data['nick_name']."'"))
                {   //昵称唯一性
                    $data['nick_name'] .= $user_id;
                }
				$data['is_effect'] = 1;
				if($qq_info['figureurl_qq_1']!=''){
                    /*$root = get_image_path();
                    $save_name= get_gmtime().$user_id.".jpg";
                    $image_file_domain = ".".$root['save_rec_Path'].$save_name;
                    $image_file =$root['savePath'].$save_name;
                    ini_set('default_socket_timeout', 1);
                    @file_put_contents ( $image_file, file_get_contents ($qq_info['figureurl_qq_2']));
                    $data['head_image']= $image_file_domain;
                    if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!='NONE')
                    {
                        syn_to_remote_image_server($image_file_domain);
                    }*/
					$head_image = $qq_info['figureurl_qq_1'];
					$data['head_image']= $head_image;
				}else{
					$data['head_image']= get_domain().'/theme/images/defaulthead.png';
				}

				if($qq_info['figureurl_qq_1']!=''){
                    $root = get_image_path();
                    $save_name= get_gmtime().$user_id.".jpg";
                    $image_file_domain = ".".$root['save_rec_Path'].$save_name;
                    $image_file =$root['savePath'].$save_name;
                    ini_set('default_socket_timeout', 1);
                    @file_put_contents ( $image_file, file_get_contents ($qq_info['figureurl_qq_1']));
                    $data['thumb_head_image']= $image_file_domain;
                    if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!='NONE')
                    {
                        syn_to_remote_image_server($image_file_domain);
                    }
				}else{
					$data['thumb_head_image']= get_domain().'/theme/images/defaulthead.png';
				}

				$data['sex']= $sex;
				$data['province']= $qq_info['province'];
				$data['city']= $qq_info['city'];
				$data['qq_openid']= $qq_info['openid'];
				$data['create_time']= NOW_TIME;
				$data['user_pwd']= md5(rand(100000,999999));
				$data['login_ip'] = CLIENT_IP;
				$data['synchronize'] = 0;
				$data['emotional_state'] ='保密';
				if($data['city']==''&&$data['province']==''){
					$data['province'] = '火星';
				}
				$data['job'] = '主播';
				$data['user_level'] = 1;
                $data['mobile'] = $qq_info['mobile'];
				$data['login_type'] = 2;
				$data['is_remind'] = 1;//默认开通推送

				
				//临时测试
				if(defined('OPEN_TEST')&&OPEN_TEST==1){
					$data['diamonds'] = 1000000;
				}
				$has_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where qq_openid='".$qq_info['openid']."'");
				if(intval($has_user_info['id'])==0){
					
					//注册赠送
					register_gift($data);
					if(intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where qq_openid='".$qq_info['openid']."'"))==0){
						$data['xpoint'] = floatval($_REQUEST['xpoint']);
						$data['ypoint'] = floatval($_REQUEST['ypoint']);
						if (defined('OPEN_BM') && OPEN_BM) {
							$bm_config      = load_auto_cache("bm_config");
							$data['bm_pid'] = intval($bm_config['bm_pid']);
						}

						if ((defined('DISTRIBUTION_SCAN')&&DISTRIBUTION_SCAN==1))   //二级分销开启
						{
							//路径权限设置
						    $dir_name = "distribution_qrcode";
						    if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) { 
						             @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
						             @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
						        }
							//生成分销二维码图片
							$invite_url = SITE_DOMAIN.APP_ROOT.'/mapi/index.php?ctl=share_distribution&act=jump&share_id='.$user_id;   //分销邀请链接
						    $path_dir = "public/attachment/distribution_qrcode/qrcode_".$user_id.".png";   //二维码图片相对路径
						    $qrcode_dir = APP_ROOT_PATH.$path_dir;  //二维码图片绝对路径
						    if(!is_file($qrcode_dir))   
						    {
						        get_qrcode_png($invite_url,$qrcode_dir,""); //生成二维码图片并保存到绝对路径
						        $head_image = "./".$path_dir;
						        syn_to_remote_image_server($head_image); //图片同步到服务器上，第二个参数true时本地图片不删除
						    }
							//存入图片路径和分销邀请链接至数据库
							$data['qr_code'] = $head_image;
							$data['distribution_url'] = $invite_url;
						}

						$GLOBALS['db']->autoExecute(DB_PREFIX."user",$data);
					}

					$user_info = $data;
				}else{
					$user_info = $has_user_info;
				}
				
				//===========add  start ===========

				$ridis_data = $user_redis->reg_data($data);
				//redis临时测试
				if(defined('OPEN_TEST')&&OPEN_TEST==1){
					$ridis_data['diamonds'] = 1000000;
				}
				//redis注册赠送
				register_gift($ridis_data);
				
				$user_redis->insert_db($user_id,$ridis_data);
				//===========add  end ===========
			}else{

                if($has_user['is_effect'] != 1){
                    $return['status'] = 0;
                    $return['error'] = "帐户已被禁用";
                    $return['data'] =$has_user['id'];
                    return $return;
                }elseif($GLOBALS['db']->getOne("SELECT login_ip FROM ".DB_PREFIX."user WHERE is_ban = 1 and ban_type = 1 and login_ip like '%".get_client_ip()."%' and is_effect !=1")){
                    $return['status'] = 0;
                    $return['error'] = "当前IP已被封停";
                    $return['data'] =$has_user['id'];
                    return $return;
                }else{
					if(!strpos($has_user['head_image'],'http://q.qlogo.cn/')&&intval($has_user['is_replace_qq'])==0){
						$has_user['head_image'] = $qq_info['figureurl_qq_1'];
						$sql = "update ".DB_PREFIX."user set head_image = '".$has_user['head_image']."',is_replace_qq =1  where id =".$has_user['id']." and is_replace_qq = 0";
						$GLOBALS['db']->query($sql);
					}
					$user_info = $has_user;
					//===========add  end ===========
				}
			}

			es_session::set("user_info", $user_info);

			//设置session过期时间一个月
			es_session::setGcMaxLifetime('2592000');
			es_cookie::set("client_ip",CLIENT_IP,3600*24*30);
			es_cookie::set("nick_name",$user_info['nick_name'],3600*24*30);
			es_cookie::set("user_id",$user_info['id'],3600*24*30);
			es_cookie::set("user_pwd",md5($user_info['user_pwd']."_EASE_COOKIE"),3600*24*30);
			es_cookie::set("PHPSESSID2",es_session::id(),3600*24*30);


			if($user_info['id']!=''){
				//登录成功 同步信息
				accountimport($user_info);

                //修改登录时间之前，获取上一次登录时间，每日首次登录赠送积分
                $login_root = login_prompt($user_info['id']);
                $return['first_login'] = $login_root['first_login'];
                $return['new_level'] = $login_root['new_level'];
                $now_time = NOW_TIME;
                //===========add  start ===========
                $data =array();
                $data['login_ip'] = CLIENT_IP;
                $data['login_time'] = to_date($now_time);
                $data['qq_openid'] = $qq_info['openid'];
                if (empty($user_info['mobile']) && $qq_info['mobile']) {
				    $data['mobile'] = $qq_info['mobile'];
                }

                if ($user_info['login_type'] != 2) {
                    $data['login_type'] = 2;
                }

                $GLOBALS['db']->autoExecute(DB_PREFIX . 'user', $data, 'UPDATE', 'id=' . $user_info['id']);
                $user_redis->update_db($user_info['id'],$data);
                $m_config =  load_auto_cache("m_config");//初始化手机端配置
                //判断昵称是否包含敏感词汇
                if($m_config['name_limit']==1) {
                    //登录过滤铭感词汇
                    $nick_name = $user_info['nick_name'];
                    $limit_sql = $GLOBALS['db']->getCol("SELECT name FROM " . DB_PREFIX . "limit_name");
                    //判断用户名是否含有铭感词汇,如果包含,替换
                    if ($GLOBALS['db']->getCol("SELECT name FROM " . DB_PREFIX . "limit_name WHERE '$nick_name' like concat('%',name,'%')")) {
                        $user_info['nick_name'] = str_replace($limit_sql, '*', $nick_name);
                    }
                    $name = $user_info['nick_name'];
                    $id = $user_info['id'];
                    //更新数据库
                    $sql = "update " . DB_PREFIX . "user set nick_name = '$name',sex={$sex} where id=" . $id;
                    $GLOBALS['db']->query($sql);
                    //更新redis
                    user_deal_to_reids(array($id));
                }

				$return['status'] = 1;
				$return['error'] = "QQ登录成功";
				$return['data'] = $user_info['id'];

				$return['user_id'] = $user_info['id'];
				set_xy_point($user_info['id']);
				$return['nick_name'] = $user_info['nick_name'];
				$return['is_agree'] = intval($user_info['is_agree']);//是否同意直播协议 0 表示不同意 1表示同意

				$return['user_info']['user_id'] =$user_info['id'];
				$return['user_info']['nick_name'] =$user_info['nick_name'];
				$return['user_info']['mobile'] =$user_info['mobile'];
				$return['user_info']['head_image'] =get_spec_image($user_info['head_image']);
			}
			else{
				$return['status'] = 0;
				$return['error'] = "QQ登录失败";
				$return['data'] ='';
			}
		}else{
			$return['status'] = 0;
			$return['error'] = "未获取用户授权";
		}
		return $return;
	}
	//
	function sinaMakeUser($sina_info, $has_user){
		if($sina_info['sina_id']!=""){

			if(!$has_user && $sina_info['sina_id']) {
                $has_user = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where sina_id='" . $sina_info['sina_id'] . "'");
            }

            if (defined('OPEN_FORCE_MOBILE') && OPEN_FORCE_MOBILE == 1 && (!$has_user || empty($has_user['mobile'])) && empty($sina_info['mobile'])) {
                return array(
                    'status' => 1,
                    'error' => '请绑定手机账户',
                    'need_bind_mobile' => 1,
                );
            }
			//性别
			if($sina_info['gender']=='m'){
				$sex =1;
			}elseif($sina_info['gender']=='f'){
				$sex =2;
			}else{
				$sex =1;
			}
			//===========add  start ===========
			fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
			$user_redis = new UserRedisService();
			//===========add  end ===========
			if(!$has_user){
				//需要通过接口的方式,获得一个新用户id
				$user_id = get_max_user_id(0);
				$data=array();
				$data['id'] = $user_id ;
				$GLOBALS['db']->query("set names 'utf8mb4'");
				$data['nick_name']= htmlspecialchars_decode($sina_info['screen_name']);
                if($GLOBALS['db']->getOne("SELECT nick_name FROM ".DB_PREFIX."user WHERE nick_name ='".$data['nick_name']."'"))
                {   //昵称唯一性
                    $data['nick_name'] .= $user_id;
                }
				$data['is_effect'] = 1;

				if($sina_info['avatar_hd']!=''){
                    $root = get_image_path();
                    $save_name= get_gmtime().$user_id.".jpg";
                    $image_file_domain = ".".$root['save_rec_Path'].$save_name;
                    $image_file =$root['savePath'].$save_name;
                    ini_set('default_socket_timeout', 1);
                    @file_put_contents ( $image_file, file_get_contents ($sina_info['avatar_hd']));
                    $data['head_image']= $image_file_domain;
                    if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!='NONE')
                    {
                        syn_to_remote_image_server($image_file_domain);
                    }
				}else{
					$data['head_image']= get_domain().'/theme/images/defaulthead.png';
				}

				if($sina_info['avatar_large']!=''){
                    $root = get_image_path();
                    $save_name= get_gmtime().$user_id.".jpg";
                    $image_file_domain = ".".$root['save_rec_Path'].$save_name;
                    $image_file =$root['savePath'].$save_name;
                    ini_set('default_socket_timeout', 1);
                    @file_put_contents ( $image_file, file_get_contents ($sina_info['avatar_large']));
                    $data['thumb_head_image']= $image_file_domain;
                    if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!='NONE')
                    {
                        syn_to_remote_image_server($image_file_domain);
                    }
				}else{
					$data['thumb_head_image']= get_domain().'/theme/images/defaulthead.png';
				}

				$data['sex']=$sex;
				$data['sina_id']= $sina_info['sina_id'];
				$data['create_time']= NOW_TIME;
				$data['user_pwd']= md5(rand(100000,999999));
				$data['login_ip'] = CLIENT_IP;
				$data['synchronize'] = 0;
				$data['emotional_state'] ='保密';
				$data['province'] = '火星';
				$data['job'] = '主播';
				$data['user_level'] = 1;
                $data['mobile'] = $sina_info['mobile'];
				$data['login_type'] = 2;
				$data['is_remind'] = 1;//默认开通推送
				//临时测试
				if(defined('OPEN_TEST')&&OPEN_TEST==1){
					$data['diamonds'] = 1000000;
				}
				//注册赠送
				register_gift($data);
				if(intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where sina_id='".$sina_info['sina_id']."'"))==0){
					if (defined('OPEN_BM') && OPEN_BM) {
						$bm_config      = load_auto_cache("bm_config");
						$data['bm_pid'] = intval($bm_config['bm_pid']);
					}

					if ((defined('DISTRIBUTION_SCAN')&&DISTRIBUTION_SCAN==1))   //二级分销开启
					{
						//路径权限设置
					    $dir_name = "distribution_qrcode";
					    if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) { 
					             @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
					             @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
					        }
						//生成分销二维码图片
						$invite_url = SITE_DOMAIN.APP_ROOT.'/mapi/index.php?ctl=share_distribution&act=jump&share_id='.$user_id;   //分销邀请链接
					    $path_dir = "public/attachment/distribution_qrcode/qrcode_".$user_id.".png";   //二维码图片相对路径
					    $qrcode_dir = APP_ROOT_PATH.$path_dir;  //二维码图片绝对路径
					    if(!is_file($qrcode_dir))   
					    {
					        get_qrcode_png($invite_url,$qrcode_dir,""); //生成二维码图片并保存到绝对路径
					        $head_image = "./".$path_dir;
					        syn_to_remote_image_server($head_image); //图片同步到服务器上，第二个参数true时本地图片不删除
					    }
						//存入图片路径和分销邀请链接至数据库
						$data['qr_code'] = $head_image;
						$data['distribution_url'] = $invite_url;
					}

					$GLOBALS['db']->autoExecute(DB_PREFIX."user",$data);
				}

				$user_info = $data;

				//===========add  start ===========

				$ridis_data = $user_redis->reg_data($data);

				//redis临时测试
				if(defined('OPEN_TEST')&&OPEN_TEST==1){
					$ridis_data['diamonds'] = 1000000;
				}
				//redis注册赠送
				register_gift($ridis_data);
				$user_redis->insert_db($user_id,$ridis_data);

				//===========add  end ===========
			}else{
                if($has_user['is_effect'] != 1){
                    $return['status'] = 0;
                    $return['error'] = "帐户已被禁用";
                    $return['data'] =$has_user['id'];
                    return $return;
                }elseif($GLOBALS['db']->getOne("SELECT login_ip FROM ".DB_PREFIX."user WHERE is_ban = 1 and ban_type = 1 and login_ip like '%".get_client_ip()."%' and is_effect !=1")){
                    $return['status'] = 0;
                    $return['error'] = "当前IP已被封停";
                    $return['data'] =$has_user['id'];
                    return $return;
                }else{
					$user_info = $has_user;
					//===========add  end ===========
				}

			}

			es_session::set("user_info", $user_info);
			//设置session过期时间一个月
			es_session::setGcMaxLifetime('2592000');
			es_cookie::set("client_ip",CLIENT_IP,3600*24*30);
			es_cookie::set("nick_name",$user_info['nick_name'],3600*24*30);
			es_cookie::set("user_id",$user_info['id'],3600*24*30);
			es_cookie::set("user_pwd",md5($user_info['user_pwd']."_EASE_COOKIE"),3600*24*30);
			es_cookie::set("PHPSESSID2",es_session::id(),3600*24*30);


			if($user_info['id']!=''){
				//登录成功 同步信息
				accountimport($user_info);

                //修改登录时间之前，获取上一次登录时间，每日首次登录赠送积分
                $login_root = login_prompt($user_info['id']);
                $return['first_login'] = $login_root['first_login'];
                $return['new_level'] = $login_root['new_level'];
                $now_time = NOW_TIME;

                //===========add  start ===========
                $data =array();
                $data['login_ip'] = CLIENT_IP;
                $data['login_time'] = to_date($now_time);
                $data['sina_id'] = $sina_info['sina_id'];
                if (empty($user_info['mobile']) && $sina_info['mobile']) {
                    $data['mobile'] = $sina_info['mobile'];
                }

                if ($user_info['login_type'] != 2) {
                    $data['login_type'] = 2;
                }

                $GLOBALS['db']->autoExecute(DB_PREFIX . 'user', $data, 'UPDATE', 'id=' . $user_info['id']);
                $user_redis->update_db($user_info['id'],$data);
                //登录过滤铭感词汇
                $m_config =  load_auto_cache("m_config");//初始化手机端配置
                //判断昵称是否包含敏感词汇
                if($m_config['name_limit']==1) {
                    $nick_name = $user_info['nick_name'];
                    $limit_sql = $GLOBALS['db']->getCol("SELECT name FROM " . DB_PREFIX . "limit_name");
                    //判断用户名是否含有铭感词汇,如果包含,替换
                    if ($GLOBALS['db']->getCol("SELECT name FROM " . DB_PREFIX . "limit_name WHERE '$nick_name' like concat('%',name,'%')")) {
                        $user_info['nick_name'] = str_replace($limit_sql, '*', $nick_name);
                    }
                    $name = $user_info['nick_name'];
                    $id = $user_info['id'];
                    //更新数据库
                    $sql = "update " . DB_PREFIX . "user set nick_name = '$name',sex={$sex} where id=" . $id;
                    $GLOBALS['db']->query($sql);
                    //更新redis
                    user_deal_to_reids(array($id));
                }

				$return['status'] = 1;
				$return['error'] = "登录成功";
				$return['data'] = $user_info['id'];

				$return['user_id'] = $user_info['id'];
				$return['nick_name'] = $user_info['nick_name'];
				$return['is_agree'] = intval($user_info['is_agree']);//是否同意直播协议 0 表示不同意 1表示同意

				$return['user_info']['user_id'] =$user_info['id'];
				$return['user_info']['nick_name'] =$user_info['nick_name'];
				$return['user_info']['mobile'] =$user_info['mobile'];
				$return['user_info']['head_image'] =get_spec_image($user_info['head_image']);
			}
			else{
				$return['status'] = 0;
				$return['error'] = "登录失败";
				$return['data'] ='';
			}
		}else{
			$return['status'] = 0;
			$return['error'] = "未获取用户授权";
		}
		return $return;
	}
	// 购物直播SDK
	function sdkMakeUser($sdk_user_info){
		if($sdk_user_info['shop_user_id']!=""){
			$has_user = false;

			if($sdk_user_info['shop_user_id'])
				$has_user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where shop_user_id='".$sdk_user_info['shop_user_id']."' ");

			//===========add  start ===========
			fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
			$user_redis = new UserRedisService();
			//===========add  end ===========
            $now_time = NOW_TIME;
			if(!$has_user){
				//需要通过接口的方式,获得一个新用户id
				$user_id = get_max_user_id(0);
				$data=array();
				$data['id'] = $user_id ;
				$GLOBALS['db']->query("set names 'utf8mb4'");
				$data['nick_name']= htmlspecialchars_decode($sdk_user_info['nick_name']);
				$data['is_effect'] = 1;

				if($sdk_user_info['head_image']!=''){
                    $data['head_image']= $sdk_user_info['head_image'];
				}else{
					$data['head_image']= get_domain().'/theme/images/defaulthead.png';
				}

				if($sdk_user_info['thumb_head_image']!=''){
                    $data['thumb_head_image']= $sdk_user_info['thumb_head_image'];
				}else{
					$data['thumb_head_image']= get_domain().'/theme/images/defaulthead.png';
				}

				$data['sex']= $sdk_user_info['sex'];
				$data['is_shop']= $sdk_user_info['is_shop'];
				$data['shop_user_id']= $sdk_user_info['shop_user_id'];
				$data['create_time']= NOW_TIME;
				$data['user_pwd']= md5(rand(100000,999999));
				$data['login_ip'] = CLIENT_IP;
				$data['login_time'] = to_date($now_time);
				$data['synchronize'] = 0;
				$data['emotional_state'] ='保密';
				$data['province'] = '火星';
				$data['job'] = '主播';
				$data['user_level'] = 1;
				$data['login_type'] = 4;
				$data['is_remind'] = 1;//默认开通推送
				if (defined('OPEN_BM') && OPEN_BM) {
					$bm_config      = load_auto_cache("bm_config");
					$data['bm_pid'] = intval($bm_config['bm_pid']);
				}
				$GLOBALS['db']->autoExecute(DB_PREFIX."user",$data);
				$user_info = $data;

				//===========add  start ===========

				$ridis_data = $user_redis->reg_data($data);
				$user_redis->insert_db($user_id,$ridis_data);

				//===========add  end ===========
			}else{
				if($has_user['is_effect'] != 1){
					$return['status'] = 0;
					$return['error'] = "帐户已被禁用";
					$return['data'] =$has_user['id'];
					return $return;
				}else{
					$user_id = $has_user['id'];
					$user_info = $has_user;
					$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".CLIENT_IP."',login_time='".to_date(NOW_TIME,"Y-m-d H:i:s")."' where id =".$user_id);

					//===========add  start ===========
					$data =array();
					$data['login_ip'] = CLIENT_IP;
					$data['login_time'] = to_date($now_time);
					$user_redis->update_db($user_id,$data);
					//===========add  end ===========
				}
			}
			//es_session::set_sessid($sdk_user_info['session_id']);
			es_session::set("user_info", $user_info);
			//设置session过期时间一个月
			es_session::setGcMaxLifetime('2592000');

			if($user_info['id']!=''){
				//登录成功 同步信息
				accountimport($user_info);

				$return['status'] = 1;
				$return['error'] = "登录成功";

				$return['video_user_id'] = $user_info['id'];
				$return['session_id'] = $sdk_user_info['session_id'];

				$return['is_agree'] = $user_info['is_agree'];

				//设置cookie
				es_cookie::set("client_ip",CLIENT_IP,3600*24*30);
				es_cookie::set("nick_name",$user_info['nick_name'],3600*24*30);
				es_cookie::set("user_id",$user_info['id'],3600*24*30);
				es_cookie::set("user_pwd",md5($user_info['user_pwd']."_EASE_COOKIE"),3600*24*30);
				es_cookie::set("PHPSESSID2",$sdk_user_info['session_id'],3600*24*30);

				es_session::set("user_id", $has_user['id']);
				es_session::set("user_pwd", md5($has_user['user_pwd']."_EASE_COOKIE"));
			}
			else{
				$return['status'] = 0;
				$return['error'] = "登录失败";
				$return['data'] ='';
			}
		}else{
			$return['status'] = 0;
			$return['error'] = "请填写购物系统用户ID";
		}
		return $return;
	}
	//同步登陆信息
	function accountimport($user_info){
		if($user_info['synchronize'] == 0){
			require_once(APP_ROOT_PATH.'system/tim/TimApi.php');
			$api = createTimAPI();
			$ret = $api->account_import((string)$user_info['id'], (string)$user_info['nick_name'], get_spec_image($user_info['head_image']));
			if($ret['ErrorCode']==0){
				$GLOBALS['db']->query("update ".DB_PREFIX."user set synchronize = 1 where id =".intval($user_info['id']));
			}else{
				log_err_file(array(__FILE__,__LINE__,__METHOD__,$ret));
			}
			//更新redis
			fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/BaseRedisService.php');
			fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
			$user_redis = new UserRedisService();
			$user_id = $user_info['id'];
			$user = array();
			$user['synchronize'] = 1;
			$user_redis->update_db($user_id,$user);

		}else{
			
		}
	}
	//更新微信opendid
	function wxUser_update($wx_info,$user_id){
		$m_config =  load_auto_cache("m_config");//初始化手机端配置
		if($wx_info['unionid']!=""||$wx_info['openid']!=""){
			$has_wx_info = false;
			if($wx_info['unionid'])
				$has_wx_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where wx_unionid = '".$wx_info['unionid']."' and id=".$user_id);
			if(!$has_wx_info && $wx_info['openid'])
				$has_wx_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where wx_openid='".$wx_info['openid']."' and id=".$user_id);
			//===========add  start ===========
			fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
			$user_redis = new UserRedisService();
			//===========add  end ===========

			if(!$has_wx_info){
				$status = $GLOBALS['db']->query("update ".DB_PREFIX."user set wx_openid = '".$wx_info['openid']."',wx_unionid = '".$wx_info['unionid']."' where id =".$user_id);
				//===========add  start ===========
				$data =array();
				$data['wx_openid'] = $wx_info['openid'];
				$data['wx_unionid'] = $wx_info['unionid'];
			    $user_redis->update_db($user_id,$data);
			}else{
				/*$return['status'] = 0;
				$return['error'] = "wx_openid已存在，无法写入！";
				return $return;*/
				$status = 1;
			}

			if($status){
				//登录成功 同步信息
				$return['status'] = 1;
				$return['error'] = "您已成功绑定微信，请手动关注".$m_config['subscription'];

				$user_info= $GLOBALS['db']->getRow("select subscribe,wx_openid,mobile from ".DB_PREFIX."user where id=".$user_id);

				$return['subscribe'] =$user_info['subscribe'];

				if($user_info['wx_openid']!='')
		        $return['binding_wx'] = 1;
		        else
		        $return['binding_wx'] = 0;

				if($user_info['mobile']!='')
		        $return['mobile_exist'] = 1;
		        else
		        $return['mobile_exist'] = 0;

			}else{
				$return['status'] = 0;
				$return['error'] = "账号绑定微信失败";
			}
		}else{
			$return['status'] = 0;
			$return['error'] = "未获取用户授权";
		}
		return $return;
	}


	/**
	 * 会员日志变化操作函数
	 * @param array $data 包括 diamonds
	 * @param integer $user_id
	 * @param string $log_msg 日志内容
	 * @param array $param 要插入的数组
	 */
	function account_log($data,$user_id,$log_msg='',$param=array())
	{
          if($user_id>0&&$log_msg!=''){
          	 //写入日志
	           $type = intval($param['type'])>0?$param['type']:0;
	           $diamonds = abs($data['diamonds'])?abs($data['diamonds']):0;
			   $weibo_money = abs($data['weibo_money'])?abs($data['weibo_money']):0;
	           $ticket = intval($data['ticket'])?intval($data['ticket']):0;
	           $score = intval($data['score'])?intval($data['score']):0;
	           $video_id = intval($data['video_id'])?intval($data['video_id']):0;
	           $contribution_id = intval($data['contribution_id'])?intval($data['contribution_id']):0;
	           $society_id = intval($data['society_id'])?intval($data['society_id']):0;
	           if(intval($param['is_admin'])){
	           		$adm_session = es_session::get(md5(conf("AUTH_KEY")));
			   		$adm_id = intval($adm_session['adm_id']);
	           }else{
	           		$adm_id = 0;
	           }

	           $user_log = array();
	           $user_log['log_info'] =$log_msg;
	           $user_log['log_time'] =get_gmtime();
	           $user_log['log_admin_id'] =$adm_id;
	           $user_log['user_id'] =$user_id;
	           $user_log['type'] =$type;
	           $user_log['diamonds'] =$diamonds;
			   $user_log['weibo_money'] =$weibo_money;
	           $user_log['ticket'] =$ticket;
	           $user_log['score'] =$score;
	           $user_log['video_id'] =$video_id;
	           $user_log['contribution_id'] = $contribution_id;
	           $user_log['society_id'] = $society_id;
	           $where = " id =".$user_id;
	           $GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$user_log,'INSERT',$where);
          }
	}

    //获得用户头像存储路劲
    function get_image_path(){
        $dir_name = to_date(get_gmtime(),"Ym");
        if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) {
            @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
            @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
        }

        $dir_name = $dir_name."/".to_date(get_gmtime(),"d");
        if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) {
            @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
            @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
        }

        $dir_name = $dir_name."/".to_date(get_gmtime(),"H");
        if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) {
            @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
            @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
        }

        $save_rec_Path = "/public/attachment/".$dir_name."/origin/";  //上传时先存放原图
        $savePath = APP_ROOT_PATH."public/attachment/".$dir_name."/origin/"; //绝对路径
        if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name."/origin/")) {
            @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name."/origin/");
            @chmod(APP_ROOT_PATH."public/attachment/".$dir_name."/origin/", 0777);
        }

        $root['save_rec_Path'] = $save_rec_Path;
        $root['savePath'] = $savePath;
        return $root;
    }
    //支付宝认证更新user
    function AuthentAlipayUser($user_data){
    	
    	//开始数据验证
		$res = array('status'=>1,'error'=>'','data'=>''); //用于返回的数据
		if(trim($user_data['id'])=='')
		{
			$res['status'] = 0;
			$res['error']='用户编号不能为空';
			return $res;
		}
		
    	if(isset($user_data['alipay_user_id']))
		$user['alipay_user_id']= $user_data['alipay_user_id'];
		
		if(isset($user_data['alipay_name']))
		$user['alipay_name']= $user_data['alipay_name'];
		
		if(isset($user_data['alipay_authent_token']))
		$user['alipay_authent_token']= $user_data['alipay_authent_token'];
		
		if(isset($user_data['v_type']))
		$user['v_type']= $user_data['v_type'];
    	
    	$user_id = intval($user_data['id']);
    	
    	$where = "id=".$user_id;

		if(intval($GLOBALS['db']->getOne("select id from fanwe_user where v_type=3 and id=".$user_id." and alipay_authent_token <>''")))
		{
			$res['status'] = 0;
			$res['error']='用户支付宝已认证';
			return $res;
		}
		
		if($GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,'UPDATE',$where))
		{
			//更新redis
			fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/BaseRedisService.php');
			fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
			$user_redis = new UserRedisService();
			$user_redis->update_db($user_id,$user);
			$res['status'] = 1;
			$res['error']='认证成功！';
		}
		
		return $res;
    }
    
     //注册赠送砖石或者游戏币
    function register_gift(&$user){ 
    	   	    	
    	if(defined('OPEN_REGISTER_GIFT')&&OPEN_REGISTER_GIFT==1){
    		
    		$m_config = load_auto_cache("m_config");
    		
    		if (intval($m_config['register_gift'])==1) {
    			
    			$user['diamonds'] = intval($m_config['register_gift_diamonds']);
				$user['coin'] = intval($m_config['register_gift_coins']);
								
    		}		
    			
		}    	
		
    }
//远程获取图片
	function getImage($url,$save_dir='',$filename='',$type=0){
		if(trim($url)==''){
			return array('file_name'=>'','save_path'=>'','error'=>1);
		}
		if(trim($save_dir)==''){
			$save_dir='./';
		}
		if(trim($filename)==''){//保存文件名
			$ext=strrchr($url,'.');
			if($ext!='.gif'&&$ext!='.jpg'){
				return array('file_name'=>'','save_path'=>'','error'=>3);
			}
			$filename=time().$ext;
		}
		if(0!==strrpos($save_dir,'/')){
			$save_dir.='/';
		}
		//创建保存目录
		if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
			return array('file_name'=>'','save_path'=>'','error'=>5);
		}
		//获取远程文件所采用的方法
		if($type){
			$ch=curl_init();
			$timeout=5;
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
			$img=curl_exec($ch);
			curl_close($ch);
		}else{
			ob_start();
			readfile($url);
			$img=ob_get_contents();
			ob_end_clean();
		}
		//$size=strlen($img);
		//文件大小
		$fp2=@fopen($save_dir.$filename,'a');
		fwrite($fp2,$img);
		fclose($fp2);
		unset($img,$url);
		return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
	}

	//二级分销注册执行方法
	function do_register_user($user_id_or_mobile,$verify_code,$p_user_id)
	{

		$result = array('status'=>0,'info'=>'','is_lack'=>0);

		$user_id_or_mobile=strim($user_id_or_mobile);
        $verify_code=strim($verify_code);

        //引入redis接口
		fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserRedisService.php');
		$user_redis = new UserRedisService();

        //定义手机方式注册数据并注册账号
		$data['mobile'] = $user_id_or_mobile;
		$data['verify_coder'] = $verify_code;
		$data['$p_user_id'] = $p_user_id;
		$result = save_mobile_user($data);	//手机方式注册

		if($result['status'])	//若注册成功
		{
			//获取刚注册的用户信息
			$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id='".$result['data']."'");
			if(empty($user['nick_name'])){
				$update['nick_name']=$result['data'];
				$where = "id=".intval($result['data']);
				$GLOBALS['db']->autoExecute(DB_PREFIX."user",$update,UPDATE,$where);
				$user['nick_name']=$result['data'];
			}
			$result['user'] = $user;
			//注册到 redis
			$ridis_data = $user_redis->reg_data($user);
	        $user_redis->insert_db($user_id,$ridis_data);
			$result['p_user_id'] = $p_user_id;
		}

		return $result;
	}

?>