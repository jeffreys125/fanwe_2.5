<?php
// +----------------------------------------------------------------------
// | FANWE 直播系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

class indexModule  extends baseModule
{
    /**
     *  热门页面接口
     */
	public function index()
	{
	    $root = array();
		$sex = intval($_REQUEST['sex']);//性别 0:全部, 1-男，2-女
		$cate_id = intval($_REQUEST['cate_id']);//话题id
		$city = strim($_REQUEST['city']);//城市(空为:热门)
		if($city=='热门' || $city=='null'){
			$city = '';
		}
		
		if ($cate_id ==0){
			//首页 轮播
			$root['banner'] = load_auto_cache("banner_list");
			if($root['banner']==false){
				$root['banner'] = array();
			}
		}else{
			//主题相关内容
			$cate = load_auto_cache("cate_id",array('id'=>$cate_id));
			if ($cate['url'] != '' && $cate['image'] != ''){
				$root['banner'] = $cate['banner'];
				$root['cate'] = $cate;
			}
		}	
		
		$root['sex'] = $sex;//
		$root['cate_id'] = $cate_id;//
		$root['city'] = $city;//

		$m_config =  load_auto_cache("m_config");//初始化手机端配置
		$sdk_version_name = strim($_REQUEST['sdk_version_name']);
		$dev_type = strim($_REQUEST['sdk_type']);
		if($dev_type == 'ios' && $m_config['ios_check_version'] != '' && $m_config['ios_check_version'] == $sdk_version_name){
			$list = $this->check_video_list("select_video_check",array('sex_type'=>$sex,'area_type'=>$city,'cate_id'=>$cate_id));
		}else{
			$list = load_auto_cache("select_video",array('sex_type'=>$sex,'area_type'=>$city,'cate_id'=>$cate_id));
		}
		if (defined('SHOW_IS_GAMING') && SHOW_IS_GAMING) {
			fanwe_require(APP_ROOT_PATH . 'mapi/lib/redis/VideoRedisService.php');
	        fanwe_require(APP_ROOT_PATH . 'mapi/lib/redis/GamesRedisService.php');
			$video_redis = new VideoRedisService();
			$redis       = new GamesRedisService();
			foreach ($list as $key => $value) {
				$video_id = intval($value['room_id']);
				$live_in = $video_redis->getOne_db($video_id, 'live_in') == 1;
				$game_log_id = $video_redis->getOne_db($video_id, 'game_log_id');
				$list[$key]['is_gaming'] = ($game_log_id && $live_in) ? 1 : 0;
				if ($game_log_id) {
					$game = $redis->get($game_log_id,'game_id');
					$game_name = $GLOBALS['db']->getOne("select name from " . DB_PREFIX . "games where id = " . $game['game_id']);
				}
				$list[$key]['game_name'] = intval($video_redis->getOne_db($video_id, 'game_log_id')) && $live_in ? 1 : 0;
			}
		}
		
		
		$root['list'] = $list;
		$root['status'] = 1;
		$root['has_next'] = 0;
		$root['page'] = 1;//
		
		$root['init_version'] = intval($m_config['init_version']);//手机端配置版本号
		
		ajax_return($root);
	}
    /**
     * 最新 &&附近的人
     */
    public function new_video(){
    	
    	$root = array();
    	
    	$root['cate_top'] = load_auto_cache("cate_top");

    	$m_config =  load_auto_cache("m_config");//初始化手机端配置
		$sdk_version_name = strim($_REQUEST['sdk_version_name']);
		$dev_type = strim($_REQUEST['sdk_type']);
		if($dev_type == 'ios' && $m_config['ios_check_version'] != '' && $m_config['ios_check_version'] == $sdk_version_name){
			$list = $this->check_video_list("new_video_check");
		}else{
			$list = load_auto_cache("new_video");
		}

    	$root['list'] = $list;
    	$root['status'] = 1;
    	$root['has_next'] = 0;
    	$root['page'] = 1;//
    	$root['init_version'] = intval($m_config['init_version']);//手机端配置版本号
    	
    	ajax_return($root);
    	
    }

    /**
     * PC端附近的人
     */
    public function new_pc_video(){
    	$m_config =  load_auto_cache("m_config");//初始化手机端配置
		$sdk_version_name = strim($_REQUEST['sdk_version_name']);
		$dev_type = strim($_REQUEST['sdk_type']);
		if($dev_type == 'ios' && $m_config['ios_check_version'] != '' && $m_config['ios_check_version'] == $sdk_version_name){
			$list = $this->check_video_list("new_video_check", array('create_type' => 1));
		}else{
			$list = load_auto_cache("new_video", array('create_type' => 1));
		}

        $root = array();
    	$root['list'] = $list;
    	$root['status'] = 1;
    	$root['has_next'] = 0;
    	$root['page'] = 1;//

    	ajax_return($root);

    }

    /**
     * 我关注的主播 直播
     */
    public function focus_video(){
    	$root['page_title'] = '关注';
    	//$GLOBALS['user_info']['id'] = 320;
    	if(!$GLOBALS['user_info']){
			$root['error'] = "用户未登陆,请先登陆.";
			$root['status'] = 0;
			$root['user_login_status'] = 0;
		}else{
	        //关注
	        $user_id = intval($GLOBALS['user_info']['id']);//登录用户id
	        
	        fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/UserFollwRedisService.php');
	        $userfollw_redis = new UserFollwRedisService($user_id);
	        $user_list = $userfollw_redis->following();

	        //私密直播  video_private,私密直播结束后， 本表会清空
	        fanwe_require(APP_ROOT_PATH.'mapi/lib/redis/VideoPrivateRedisService.php');
	        $video_private_redis = new VideoPrivateRedisService();
	        $private_list = $video_private_redis->get_video_list($user_id);

	        $list = array();
	        
	        if(sizeof($private_list) || sizeof($user_list)){
	        	$m_config =  load_auto_cache("m_config");//初始化手机端配置
				$sdk_version_name = strim($_REQUEST['sdk_version_name']);
				$dev_type = strim($_REQUEST['sdk_type']);
				if($dev_type == 'ios' && $m_config['ios_check_version'] != '' && $m_config['ios_check_version'] == $sdk_version_name){
					$list_all = load_auto_cache("select_video_check",array('has_private'=>1));
				}else{
					$list_all = load_auto_cache("select_video",array('has_private'=>1));
				}
	        	
	        	
		        foreach($list_all as $k=>$v){
		        	if ((($v['room_type'] == 1 && in_array($v['room_id'], $private_list)) || ($v['room_type'] == 3 && in_array($v['user_id'], $user_list)))&&($v['user_id']!='13888888888'||$v['user_id']!='13999999999')) {
		        		$list[] = $v;
		        	}else if($v['user_id']==$user_id&&$v['room_type']==1&&$v['live_in']==1){
		        		$user_video = array();
		        		$user_video = $v;
		        	}
		        }
	        }
	        
	        if($user_video){
	        	array_unshift($list,$user_video);
	        }
	        
	        $root['list'] = $list;
	        
	        $playback = load_auto_cache("playback_list",array('user_id'=>$user_id));
	        foreach ($playback as $k => $v) {
	        	$playback[$k]['nick_name'] = emoji_decode($v['nick_name']);
	        }
	        $root['playback'] = $playback;
	        $root['status'] = 1;
	        
    	}
        ajax_return($root);
    }

    /**
     * 查询话题列表
     */
    function search_video_cate(){
    
    	$page = intval($_REQUEST['p']);//取第几页数据
    	$title = strim($_REQUEST['title']);
    		
    	if($page==0){
    		$page = 1;
    	}
    
    	$page_size=50;
    	$limit = (($page-1)*$page_size).",".$page_size;

    	if ($title){
    		$sql = "select vc.id as cate_id,vc.title,vc.num from ".DB_PREFIX."video_cate as vc
						where vc.is_effect = 1 and vc.title like '%".$title."%' order by vc.sort desc, vc.num desc limit ".$limit;
    
    	}else{
    		$sql = "select vc.id as cate_id,vc.title,vc.num from ".DB_PREFIX."video_cate as vc
						where vc.is_effect = 1  order by vc.sort desc, vc.num desc limit ".$limit;
    	}

    	//查询话题列表,修改成 从只读数据库中取,但不是高效做法;主并发时,可以加入阿里云的搜索服务
    	//https://www.aliyun.com/product/opensearch?spm=5176.8142029.388261.62.tgDxhe
    	$list = $GLOBALS['db']->getAll($sql,true,true);
    	foreach($list as $k=>$v){
    		$list[$k]['title'] ="#".$v['title']."#";
    	}
    	if($page==0){
    		$root['has_next'] = 0;
    	}else{
    		if (count($list) == $page_size)
    			$root['has_next'] = 1;
    		else
    			$root['has_next'] = 0;
    	}
    	 
    	$root['page'] = $page;//
    	 
    	$root['list'] =$list;

    	$root['status'] =1;
    	 
    	ajax_return($root);
    }
    
    //按地区（省份）
    //0:全部;1:男;2:女
    function search_area(){
    	$sex = intval($_REQUEST['sex']);//性别 0:全部, 1-男，2-女
    	$list = load_auto_cache("sex_area",array('sex'=>$sex));
    
    	$root = array();
    	$root['list'] = $list;
    	$root['status'] = 1;
    	$root['total_num'] = count($list);
    
    	ajax_return($root);
    }

    /**
     * IOS 审核版本读取
     * @param string $type
     * @param array $date
     * @return mixed|string
     */
	function check_video_list($type='',$date=array()){
		$list = '';
		if($type!=''){
			if($type=='new_video_check'){
				$list = load_auto_cache("new_video_check");
			}else{
				$list = load_auto_cache("select_video_check",$date);
			}
		}
		return $list;
	}

    /**
     * 分类
     */
    public function classify()
    {
        $root = array();
        $m_config =  load_auto_cache("m_config");//初始化手机端配置
        $sdk_version_name = strim($_REQUEST['sdk_version_name']);
        $dev_type = strim($_REQUEST['sdk_type']);
        $classified_id = intval($_REQUEST['classified_id']);
        if(!$classified_id){
            $classified_id = 1;
        }
        if($dev_type == 'ios' && $m_config['ios_check_version'] != '' && $m_config['ios_check_version'] == $sdk_version_name){
            $list = $this->check_video_list("select_video_check",array('is_classify'=>$classified_id));
        }else {
			$list = load_auto_cache("select_video", array('is_classify' => $classified_id));
		}
		$root['list'] = $list;
        $root['status'] = 1;
        $root['has_next'] = 0;
        $root['page'] = 1;//
        $root['init_version'] = intval($m_config['init_version']);//手机端配置版本号

        ajax_return($root);
    }

    /**
     * 公会列表的显示
     */
	function society(){
	    $root = array();
	    if(!$GLOBALS['user_info']){
	        $root['error'] = "用户未登陆,请先登陆.";
	        $root['status'] = 0;
	    }else{
	        $user_id = intval($GLOBALS['user_info']['id']);//登录用户id
	        //获取用户的公会ID
	        $sql3 = "select society_id from ".DB_PREFIX."user where id=".$user_id.";";
	        $res3 = $GLOBALS['db']->getOne($sql3);
	        $root['error'] = "";
	        $root['status'] = 1;
	        
	        //模糊查询
	        $society_filtrate = $_REQUEST['society_filtrate']?$_REQUEST['society_filtrate']:0;
	        $where = "s.status=1";
	        if($society_filtrate){
	            $where .= " and (s.id like '%".$society_filtrate."%' or s.name like '%".$society_filtrate."%')";
	        }
	        
	        //分页
	        $page = $_REQUEST['page']?$_REQUEST['page']:1;//当前页
	        if($page!=null){
	            $page_size = 20;//分页数量
	            //获取总条数
	            $sql1 = "select count(*) from ".DB_PREFIX."society s where ".$where;
	            $res1 = intval($GLOBALS['db']->getOne($sql1));
	            //总页数
	            $page_total = ceil($res1/$page_size);
	            //分页
	            $limit = (($page - 1) * $page_size) . "," . $page_size;
	            
	            $sql = "select s.id,s.logo,s.name,s.user_count,s.status,u.nick_name,u.id as uid from ".DB_PREFIX."society s inner join ".DB_PREFIX."user u on s.user_id=u.id where ".$where." order by s.society_rank desc,s.create_time desc limit ". $limit.";";
	            $res = $GLOBALS['db']->getAll($sql);
	            if(empty($res)){
	                $root['error'] = "暂无公会可以显示";
	            }else {
	                foreach ($res as $key=>$val){
	                    if($user_id == $val['uid']){//会长
	                        $root['list'][$key]['type'] = 1;
	                    }elseif($res3 == $val['id']){
	                        $sql2 = "select id from ".DB_PREFIX."society_apply where user_id=".$user_id." and society_id=".$val['id']." and apply_type=1;";
	                        $res2 = $GLOBALS['db']->getOne($sql2);
	                        if(empty($res2)){//会员
	                            $root['list'][$key]['type'] = 0;
	                        }else{//申请退出公会人员
	                            $root['list'][$key]['type'] = 5;
	                        }
	                    }else{
	                        if($res3 != 0 && $res3 != $val['id']){//其他公会成员
	                            $root['list'][$key]['type'] = 2; 
	                        }else{
	                            $sql4 = "select id from ".DB_PREFIX."society_apply where user_id=".$user_id." and society_id=".$val['id']." and apply_type=0;";
	                            $res4 = $GLOBALS['db']->getOne($sql4);
	                            if(empty($res4)){//无公会人员
	                                $root['list'][$key]['type'] = 3;
	                            }else{//申请入会人员
	                                $root['list'][$key]['type'] = 4;
	                            }
	                            
	                        }
	                    }
	                    $root['list'][$key]['society_id']    = intval($val['id']);
	                    $root['list'][$key]['society_image'] = get_spec_image($val['logo']);
	                    $root['list'][$key]['society_name']  = $val['name'];
	                    $root['list'][$key]['society_user_count'] = intval($val['user_count']);
	                    $root['list'][$key]['society_chairman']   = emoji_decode($val['nick_name']);
	                    $root['list'][$key]['gh_status']          = intval($val['status']);
	                }
	                $has_next = ($res1 > $page * $page_size) ? '1' : '0';//是否有下一页
	                $root['page'] = array('page'=>$page,'page_total'=>$page_total,'has_next' => $has_next);
	            }
	        }else{
	            $root['error'] = "当前页接收出现问题";
	            $root['status'] = 0;
	        }
	
	    }
	    ajax_return($root);
	}
}

?>