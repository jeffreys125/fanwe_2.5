<?php

class svideo_select_weibo_recommend_auto_cache extends auto_cache
{
    private $key = "select:weibo_recommend:";

    public function load($param)
    {
        fanwe_require(APP_ROOT_PATH . 'mapi/xr/core/common.php');
        $this->key .= md5(serialize($param));
        $page = $param['page'] > 0 ? $param['page'] : 1;
        $user_id = $param['user_id'];
        $page_size = $param['page_size'] > 0 ? $param['page_size'] : 20;
        $limit = (($page - 1) * $page_size) . "," . $page_size;


        $key_bf = $this->key . '_bf';

        $list = $GLOBALS['cache']->get($this->key, true);

        if ($list === false) {
            $is_ok = $GLOBALS['cache']->set_lock($this->key);
            if (!$is_ok) {
                $list = $GLOBALS['cache']->get($key_bf, true);
            } else {
                $sql = "select w.user_id,w.id as weibo_id,u.head_image,u.is_authentication,w.content,w.red_count,w.digg_count,w.comment_count,w.video_count,w.data,u.v_icon,u.nick_name,w.sort_num,w.photo_image ,u.city,w.is_top,w.price,w.type,w.create_time,w.city,w.province,w.address  from " . DB_PREFIX . "weibo as w
					left join " . DB_PREFIX . "user as u on w.user_id = u.id where is_recommend = 1 and status = 1 ";

                if (!empty($param['type'])) {
                    if (!is_array($param['type'])) {
                        $param['type'] = array($param['type']);
                    }
                    $type = implode("','", $param['type']);
                    $sql .= " and w.type in ('{$type}')";
                }

                $sql_black = "select black_user_id from " . DB_PREFIX . "black where  user_id = " . $user_id;
                $black_list = $GLOBALS['db']->getAll($sql_black, true, true);
                if (!empty($black_list)) {
                    $black_list_array = array_column($black_list, 'black_user_id');
                    $sql .= " and w.user_id not in ( " . implode(',', $black_list_array) . ")";
                }

                $sql .= "  order by w.id desc";
                $sql .= " limit " . $limit;
                $list = $GLOBALS['db']->getAll($sql, true, true);
                if ($user_id > 0) {
                    $order_list = $GLOBALS['db']->getAll("select order_id from " . DB_PREFIX . "payment_notice where user_id = " . $user_id . " and type in (11,13,14)");
                    if (count($order_list) > 0) {
                        foreach ($order_list as $k => $v) {
                            $order_list_array[] = $v['order_id'];
                        }
                    } else {
                        $order_list_array = array();
                    }
                } else {
                    $order_list_array = array();
                }

                if (count($list) > 0) {
                    foreach ($list as $k => $v) {
                        $list[$k]['has_black'] = 0;
                        $list[$k]['is_show_weibo_report'] = 1;
                        $list[$k]['is_show_user_report'] = 1;
                        $list[$k]['is_show_user_black'] = 1;
                        $list[$k]['is_show_top'] = 0;
                        $list[$k]['is_show_deal_weibo'] = 0;

                        $list[$k]['left_time'] = $this->time_tran($v['create_time']);
                        if ($v['head_image']) {
                            $list[$k]['head_image'] = deal_weio_image($v['head_image'], 'head_image');
                        }
                        if ($v['photo_image']) {
                            $list[$k]['photo_image'] = deal_weio_image($v['photo_image'], $v['type']);
                        }
                        $address_x = str_replace("福建省", "", $v['address']);
                        $address_x = str_replace("福州市", "", $address_x);
                        $list[$k]['weibo_place'] = $v['province'] . $v['city'] . $address_x;
                        $list[$k]['images_count'] = 0;
                        if ($v['type'] == 'video') {
                            $list[$k]['images'] = array();
                            $url = $v['data'];
                            $list[$k]['video_url'] = get_file_oss_url($url);

                        } else {
                            $images = unserialize($v['data']);
                            if (in_array($v['weibo_id'], $order_list_array)) {
                                $is_pay = 1;
                            } else {
                                $is_pay = 0;
                            }
                            if (count($images) > 0) {
                                foreach ($images as $k1 => $v1) {
                                    if (is_object($v1)) {
                                        $v1 = (array)$v1;
                                    }
                                    if ($v1['url']) {
                                        $is_model = $v1['is_model'];
                                        $images[$k1]['orginal_url'] = '';
                                        if ($is_model) {
                                            if ($is_pay) {
                                                $images[$k1]['url'] = deal_weio_image($v1['url']);
                                                $images[$k1]['is_model'] = 0;
                                                $images[$k1]['orginal_url'] = get_spec_image($v1['url']);
                                            } else {
                                                $images[$k1]['url'] = deal_weio_image($v1['url'], $v['type'], 1);
                                            }
                                        } else {
                                            $images[$k1]['url'] = deal_weio_image($v1['url']);
                                            $images[$k1]['orginal_url'] = get_spec_image($v1['url']);
                                        }

                                    }
                                }
                                $list[$k]['images'] = $images;
                                $list[$k]['images_count'] = count($images);
                            } else {
                                $list[$k]['images'] = array();

                            }
                            $list[$k]['video_url'] = '';

                        }
                        unset($list[$k]['data']);
                    }
                } else {
                    $list = array();
                }


                $GLOBALS['cache']->set($this->key, $list, 10, true);

                $GLOBALS['cache']->set($key_bf, $list, 86400, true);//备份
                //echo $this->key;
            }
        }

        if ($list == false) {
            $list = array();
        }

        return $list;
    }

    public function rm()
    {

        //$GLOBALS['cache']->clear_by_name($this->key);
    }

    public function clear_all()
    {

        //$GLOBALS['cache']->clear_by_name($this->key);
    }

    public function time_tran($the_time)
    {
        $now_time = to_date(NOW_TIME, "Y-m-d H:i:s");
        $now_time = to_timespan($now_time);
        $show_time = to_timespan($the_time);
        $dur = $now_time - $show_time;
        if ($dur < 0) {
            return to_date($show_time, "Y-m-d");
        } else {
            if ($dur < 60) {
                return $dur . '秒前';
            } else {
                if ($dur < 3600) {
                    return floor($dur / 60) . '分钟前';
                } else {
                    if ($dur < 86400) {
                        return floor($dur / 3600) . '小时前';
                    } else {
                        if ($dur < 2592000) {//30天内
                            return floor($dur / 86400) . '天前';
                        } else {
                            return to_date($show_time, "Y-m-d");
                        }
                    }
                }
            }
        }
    }

}

?>