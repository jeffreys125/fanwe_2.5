<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/30
 * Time: 8:28
 */
class focus_video_auto_cache extends auto_cache
{
    private $key = "focus:video:";

    public function load($param)
    {
        $sex = intval($param['sex_type']);
        $city = strim($param['area_type']);
        $cate_id = intval($param['cate_id']);
        $has_private = intval($param['has_private']);//1：包括私密直播

        if ($city == '热门' || $city == 'null') {
            $city = '';
        }
        if ($sex == null || $sex == '') {
            $sex = 0;
        }
        $this->key .= $sex . '_' . $city . '_' . $cate_id . '_' . $has_private;


        $key_bf = $this->key . '_bf';

        $list = $GLOBALS['cache']->get($this->key, true);

        if ($list === false) {

                $m_config = load_auto_cache("m_config");//初始化手机端配置
                $has_is_authentication = intval($m_config['has_is_authentication']) ? 1 : 0;
                if ($has_is_authentication && $m_config['ios_check_version'] == '') {
                    $sql = "SELECT v.id AS room_id, v.sort_num, v.group_id, v.user_id, v.city, v.title, v.cate_id, v.live_in, v.video_type, v.room_type,v.private_key,
						(v.robot_num + v.virtual_watch_number + v.watch_number) as watch_number,v.live_image, u.head_image,u.thumb_head_image, v.xpoint,v.ypoint,
						u.v_type, u.v_icon, u.nick_name,u.user_level FROM " . DB_PREFIX . "video v
					LEFT JOIN " . DB_PREFIX . "user u ON u.id = v.user_id where v.live_in in (1,3) and u.is_authentication = 2 ";
                } else {
                    $sql = "SELECT v.id AS room_id, v.sort_num, v.group_id, v.user_id, v.city, v.title, v.cate_id, v.live_in, v.video_type, v.room_type,v.private_key,
						(v.robot_num + v.virtual_watch_number + v.watch_number) as watch_number,v.live_image, u.head_image,u.thumb_head_image, v.xpoint,v.ypoint,
						u.v_type, u.v_icon, u.nick_name,u.user_level FROM " . DB_PREFIX . "video v
					LEFT JOIN " . DB_PREFIX . "user u ON u.id = v.user_id where v.live_in in (1,3) ";
                }


                if ($has_private == 1) {
                    $sql .= ' and v.room_type in (1,3)'; //1:私密直播;3:直播
                } else {
                    $sql .= ' and v.room_type = 3'; //1:私密直播;3:直播
                }


                if ($sex == 1 || $sex == 2) {
                    $sql .= ' and v.sex = ' . $sex;
                }

                if ($city != '') {
                    $sql .= " and v.province = '" . $city . "'";
                }

                if ($cate_id > 0) {
                    $sql .= " and v.cate_id = '" . $cate_id . "'";
                }

                $sql .= "  order by v.live_in, v.sort_num desc,v.sort desc";


                $list = $GLOBALS['db']->getAll($sql, true, true);

                foreach ($list as $k => $v) {
                    if(strpos($v['head_image'],'wx.qlogo.cn')){
                        $list[$k]['thumb_head_image'] = strtr($v['head_image'],array('/96'=>'/46'));
                    }else{
                        if ($v['thumb_head_image'] == ''){
                            $list[$k]['thumb_head_image'] = get_spec_image($v['head_image'],150,150);
                        }else{
                            //$list[$k]['thumb_head_image'] = get_abs_img_root($v['thumb_head_image']);
                            $list[$k]['thumb_head_image'] = get_spec_image($v['thumb_head_image'],150,150);
                        }
                    }
                    if(empty($v['live_image'])) {
                        if(strpos($v['head_image'],'wx.qlogo.cn')){
                            $list[$k]['live_image'] = strtr($v['headimgurl'],array('/96'=>'/0'));
                        }elseif(strpos($v['head_image'],'http://q.qlogo.cn/')) {
                            $list[$k]['live_image'] = strtr($v['headimgurl'],array('/40'=>'/100'));
                        }else{
                            $list[$k]['live_image'] = get_spec_image($v['head_image'],320,180,1);
                        }
                    }else{
                        $list[$k]['live_image']=get_spec_image($v['live_image'],320,180,1);
                    }
                    $list[$k]['head_image'] = get_spec_image($v['head_image'],40,40,1);
                    if($v['live_in']==3){
                        $list[$k]['video_url']=url('live#show',array('room_id'=>$list[$k]['room_id'],'is_vod'=>1));
                    }else{
                        $list[$k]['video_url']=url('live#show',array('room_id'=>$list[$k]['room_id']));
                    }

                    $list[$k]['nick_name'] = emoji_decode($v['nick_name']);
                }

                $GLOBALS['cache']->set($this->key, $list, 10, true);

                $GLOBALS['cache']->set($key_bf, $list, 86400, true);//备份

            }


        if ($list == false) $list = array();
        return $list;
    }

    public function rm()
    {

        $GLOBALS['cache']->clear_by_name($this->key);
    }

    public function clear_all()
    {

        $GLOBALS['cache']->clear_by_name($this->key);
    }
}

?>