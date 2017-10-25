<?php
class distributionModule  extends baseModule
{
	/**
	 * 分销/邀请注册奖励模块
	 */
    public function index()
	{
		$root = array('status' => 1,'error'=>'');
		if(!$GLOBALS['user_info']){
			$root['error'] = "用户未登陆,请先登陆." .print_r($_COOKIE,1);
			$root['status'] = 0;
			$root['user_login_status'] = 0;//有这个参数： user_login_status = 0 时，表示服务端未登陆、要求登陆，操作
		}else{
			$user_id = intval($GLOBALS['user_info']['id']);//用户ID
			$page = intval($_REQUEST['p']);//取第几页数据
    	
	    	if($page==0){
	    		$page = 1;
	    	}
	    
	    	$page_size=30;
	    	$limit = (($page-1)*$page_size).",".$page_size;

	    	$sql = "select dl.id,dl.diamonds as ticket,dl.diamonds,dl.from_user_id as user_id,u.head_image,u.nick_name as user_name,u.nick_name from ".DB_PREFIX."distribution_log dl left join ".DB_PREFIX."user as u on u.id = dl.from_user_id where dl.to_user_id = ".$user_id."  group by dl.from_user_id order by dl.ticket desc limit ".$limit ;
	    	$list = $GLOBALS['db']->getAll($sql,true,true);
	    	
	    	foreach($list as $k=>$v){
	    		$m_config =  load_auto_cache("m_config");//初始化手机端配置
	    		if($v['head_image']=='')$v['head_image'] = $m_config['app_logo'];
	    		$list[$k]['head_image'] = get_spec_image($v['head_image'],150,150);
	    		$list[$k]['nick_name'] = emoji_decode($v['nick_name']);
	    		$list[$k]['user_name'] = emoji_decode($v['user_name']);
	    	}
	    	
	    	$rs_count = count($list);
	    	
	    	if ($page == 1) {
                $root['page'] = array('page' => $page, 'has_next' => 0);
            }else {
                $has_next = ($rs_count > $page * $page_size) ? '1' : '0';
                $root['page'] = array('page' => $page, 'has_next' => $has_next);
            }
	    	
	    	$root['data'] = $list;

		}
		ajax_return($root);
	}
	/**
	 * 邀请注册奖励注册——页面
	 */
    public function init_register()
	{
		$root = array('status' => 0,'error'=>'');
        $share_id = intval($_REQUEST['user_id']);
		$root['url'] =SITE_DOMAIN.'/mapi/index.php?ctl=distribution&act=register&user_id='.$share_id;
        $root['page_title'] = '手机注册';
		$root['app_down_url'] = SITE_DOMAIN."/appdown.php";
		api_ajax_return($root);
	}

	/**
	 * 邀请注册奖励——保存
	 */
	public function register()
	{		
		$root = array('status' => 1,'error'=>'');
		if(!$_REQUEST)
		{
			app_redirect(get_domain()."/");
		}
		$user_id = intval($_REQUEST['user_id']);
		$mobile = strim($_REQUEST['mobile']);

		if($mobile==''){
			$root['error'] = "手机号码不能为空！";
			$root['status'] = 0;
			api_ajax_return($root);
		}

		if(!check_mobile($mobile))
		{
			$result['info'] = '手机格式错误';
			$root['status'] = 0;
			api_ajax_return($root);
		}

		$root['url'] = SITE_DOMAIN."/appdown.php";

		$p_user_id = $GLOBALS['db']->getOne("select id from " . DB_PREFIX . "user where id =" . $user_id);
		if(intval($p_user_id)==0){
			$root['error'] = "上级用户不存在！";
			$root['status'] = 0;
			api_ajax_return($root);
		}


		$mobile = $GLOBALS['db']->getOne("select id from " . DB_PREFIX . "user where mobile =" . $mobile);
		if($mobile!=''){
			$root['is_url'] = 1;
			$root['error'] = "手机号已注册！";
			$root['status'] = 0;
		}

		if($root['status']!=0){
			fanwe_require(APP_ROOT_PATH."system/libs/user.php");
			$result = do_login_user($_REQUEST['mobile'],$_REQUEST['verify_coder']);
		}
		
		if($result['status'])
		{
			$root['user_id'] = $result['user']['id'];
			$root['status'] = 1;
			if($result['user']['head_image']==''||$result['user_info']['head_image']==''){
				//头像
				$m_config =  load_auto_cache("m_config");//初始化手机端配置
				$system_head_image = $m_config['app_logo'];

				if($system_head_image==''){
					$system_head_image = './public/attachment/test/noavatar_10.JPG';
					syn_to_remote_image_server($system_head_image,false);
				}
				
				$data = array(
					'head_image' => $system_head_image,
					'thumb_head_image' => get_spec_image($system_head_image,40,40),
					'p_user_id' =>$p_user_id,
					);

				$GLOBALS['db']->autoExecute(DB_PREFIX."user",$data,"UPDATE", "id=".$result['user']['id']);

				fanwe_require(APP_ROOT_PATH . 'mapi/lib/redis/UserRedisService.php');
				$user_redis = new UserRedisService();
				$user_redis->update_db($result['user']['id'],$data);

				//更新session
				$user_info = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where id =" . $result['user']['id']);
				es_session::set("user_info", $user_info);
			}
            $result['user_info']['head_image'] = $system_head_image;
			$root['is_url'] = 1;
			$root['is_lack'] = $result['is_lack'];//是否缺少用户信息
			$root['is_agree'] = intval($result['user']['is_agree']);//是否同意直播协议 0 表示不同意 1表示同意
			$root['user_id'] = intval($result['user']['id']);
			$root['nick_name'] = $result['user']['nick_name'];
			$root['family_id']=intval($result['user']['family_id']);
			$root['family_chieftain']=intval($result['user']['family_chieftain']);
			$root['error'] = "注册成功";
			$root['user_info'] = $result['user_info'];
			if(intval(OPEN_REWARD_POINT)==1){
                $this->do_reward_point($root['user_id']);
			}
		}
		else
		{
            $root['status'] = 0;
			if($root['error']=='')$root['error'] = $result['info'];
		}
		api_ajax_return($root);
	}

    /**
	 * 邀请奖励处理
     * @param $user_id
     * @return array
     */
	private  function do_reward_point($user_id)
    {

        $root = array();
        $m_config = load_auto_cache("m_config");//初始化手机端配置
        $total_diamonds = intval($m_config['reward_point_diamonds']);
        $table = DB_PREFIX . 'distribution_log';
        fanwe_require(APP_ROOT_PATH . 'mapi/lib/redis/UserRedisService.php');
        $user_redis = new UserRedisService();
        $to_user_id = $user_redis->getOne_db($user_id, 'p_user_id');//用户总的：印票数
        $result = 0;
        if (intval($to_user_id) > 0 && $user_id > 0 && $total_diamonds > 0) {
            $sql = "select id from " . $table . " where type = 1 and to_user_id = " . $to_user_id . " and from_user_id = " . $user_id;
            $distribution_id = $GLOBALS['db']->getOne($sql);
            if (intval($distribution_id) > 0) {
                $sql = "update " . $table . " set diamonds = diamonds + " . $total_diamonds . " where id = " . $distribution_id;
                $GLOBALS['db']->query($sql);
                $result = 1;
            } else {
                //插入:邀请奖励日志
                $video_prop = array();
                $video_prop['from_user_id'] = $user_id;
                $video_prop['to_user_id'] = $to_user_id;
                $video_prop['create_date'] = "'" . to_date(NOW_TIME, 'Y-m-d') . "'";
                $video_prop['diamonds'] = $total_diamonds;
                $video_prop['create_time'] = NOW_TIME;
                $video_prop['create_ym'] = to_date($video_prop['create_time'], 'Ym');
                $video_prop['create_d'] = to_date($video_prop['create_time'], 'd');
                $video_prop['create_w'] = to_date($video_prop['create_time'], 'W');
                $video_prop['type'] = 1;

                //将日志写入mysql表中
                $field_arr = array(
                    'from_user_id',
                    'to_user_id',
                    'create_date',
                    'diamonds',
                    'create_time',
                    'create_ym',
                    'create_d',
                    'create_w',
                    'type'
                );
                $fields = implode(",", $field_arr);
                $valus = implode(",", $video_prop);

                $sql = "insert into " . $table . "(" . $fields . ") VALUES (" . $valus . ")";
                $GLOBALS['db']->query($sql);
                $result = $GLOBALS['db']->insert_id();
            }
            if (intval($result) > 0) {
                $sql = "update " . DB_PREFIX . "user set diamonds = diamonds + " . $total_diamonds . " where id = " . $to_user_id;
                $GLOBALS['db']->query($sql);
            }
        }
        return $root;
    }
}
?>
