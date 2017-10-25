<?php

class WeiboCommentAction extends CommonAction
{
    public function index()
    {
        $map = array('type' => 1, 'is_del' => 0);
        $title = strim($_REQUEST['title']);
        if (!empty($title)) {
            $map['content'] = array('like', '%' . $title . '%');
        }
        $this->assign("default_map", $map);

        parent::index();
    }

    public function delete()
    {
        //删除指定记录
        $ajax = intval($_REQUEST['ajax']);
        $id = $_REQUEST ['id'];
        if (isset ($id)) {
            $condition = array('comment_id' => array('in', explode(',', $id)));
            $rel_data = M(MODULE_NAME)->where($condition)->findAll();
            foreach ($rel_data as $data) {
                $info[] = $data['content'];
            }
            if ($info) {
                $info = implode(",", $info);
            }
            $list = M(MODULE_NAME)->where($condition)->setField('is_del', 1);
            if ($list !== false) {
                $sql = "UPDATE " . DB_PREFIX . "weibo SET comment_count =( SELECT count(*) FROM " . DB_PREFIX . "weibo_comment WHERE weibo_id = id AND type = 1 AND is_del = 0) WHERE id IN( SELECT weibo_id FROM fanwe_weibo_comment WHERE comment_id IN({$id}))";
                $GLOBALS['db']->query($sql);
                save_log($info . l("DELETE_SUCCESS"), 1);
                $this->success(l("DELETE_SUCCESS"), $ajax);
            } else {
                save_log($info . l("DELETE_FAILED"), 0);
                $this->error(l("DELETE_FAILED"), $ajax);
            }
        } else {
            $this->error(l("INVALID_OPERATION"), $ajax);
        }
    }
}