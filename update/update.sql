2.5;

update `%DB_PREFIX%m_config` set `desc` = '用于网页应用微信登录' where `code` = 'wx_web_appid';
update `%DB_PREFIX%m_config` set `desc` = '用于网页应用微信登录' where `code` = 'wx_web_secrit';
update `%DB_PREFIX%m_config` set `desc` = '用于网页应用微博登录' where `code` = 'sina_web_app_key';
update `%DB_PREFIX%m_config` set `desc` = '用于网页应用微博登录' where `code` = 'sina_web_app_secret';

UPDATE `%DB_PREFIX%conf` SET `value`='2.24' WHERE (`name`='DB_VERSION');

INSERT INTO `%DB_PREFIX%role_node` (`action`, `name`, `is_effect`, `is_delete`, `group_id`, `module_id`) VALUES ('index', '列表', '1', '0', '0', (select id from `%DB_PREFIX%role_module` where module='PlugIn'));
INSERT INTO `%DB_PREFIX%role_node` (`action`, `name`, `is_effect`, `is_delete`, `group_id`, `module_id`) VALUES ('edit', '编辑', '1', '0', '0', (select id from `%DB_PREFIX%role_module` where module='PlugIn'));

INSERT INTO `%DB_PREFIX%m_config` VALUES (null, 'pc_has_private_chat', 'PC端允许私信', 'PC端设置', '1', 4, 0, '0,1', '否,是', '是否开启私信功能，1开启 0关闭');

ALTER TABLE `%DB_PREFIX%recharge_rule`
ADD COLUMN `iap_diamonds`  int(11) NULL DEFAULT 0 COMMENT '苹果支付获取钻石';

UPDATE `%DB_PREFIX%conf` SET `value`='2.25' WHERE (`name`='DB_VERSION');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('distribution_wx', '显示微信登录', '分销模块', '0', 4, 0, '0,1', '否,是', '屏蔽微信登录方式，不影响分享等其他微信相关功能 1是 0否');
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('distribution_qq', '显示QQ登录', '分销模块', '0', 4, 0, '0,1', '否,是', '屏蔽QQ登录方式，不影响分享等其他QQ相关功能 1是 0否');
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('distribution_sina', '显示微博登录', '分销模块', '0', 4, 0, '0,1', '否,是', '屏蔽微博登录方式，不影响分享等其他微博相关功能 1是 0否');

UPDATE `%DB_PREFIX%conf` SET `value`='2.26' WHERE (`name`='DB_VERSION');

ALTER TABLE `%DB_PREFIX%family`
ADD COLUMN `user_count` int(11) NOT NULL DEFAULT 1 COMMENT '成员数量' after `user_id`;

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('live_page_size', '监控界面分页', '基础配置', '10', 0, 0, NULL, NULL, '(条) 监控界面分页数量，默认为10，最低数量不能低于10条，否则取默认值');
CREATE TABLE `%DB_PREFIX%pc_goods` (
`id`  int(11) NOT NULL AUTO_INCREMENT COMMENT '自增字段' ,
`user_id`  int(11) NOT NULL COMMENT '主播ID' ,
`name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '商品名称' ,
`imgs`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '图片（JSON数据）' ,
`price`  decimal(20,2) NOT NULL COMMENT '商品价钱' ,
`url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品详情URL地址' ,
`description`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品描述' ,
`is_delete`  tinyint(1) NOT NULL COMMENT '商品状态 0为正常,1为删除（值为1时不在前端展示）' ,
`kd_cost`  decimal(20,2) NOT NULL COMMENT '快递费用' ,
PRIMARY KEY (`id`)
)
;
UPDATE `%DB_PREFIX%m_config` SET val=NULL WHERE `code`='pc_download_slogan';
UPDATE `%DB_PREFIX%m_config` SET val=NULL WHERE `code`='pc_logo';
UPDATE `%DB_PREFIX%conf` SET `value`='2.27' WHERE (`name`='DB_VERSION');

ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `is_hot_on`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '禁热门 0-正常；1-禁止';

CREATE TABLE `%DB_PREFIX%warning_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL COMMENT '警告内容',
  `is_effect` tinyint(1) DEFAULT '1' COMMENT '是否有效 0:无效;1:有效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

UPDATE `%DB_PREFIX%conf` SET `value`='2.29' WHERE (`name`='DB_VERSION');

INSERT INTO `%DB_PREFIX%conf` (`name`, `value`, `group_id`, `input_type`, `value_scope`, `is_effect`, `is_conf`, `sort`) VALUES ('COPYRIGHT', 'Copyright 2016-2017 sjzhsd.cn All rights reserved.', 1, 0, '', 1, 1, 24);

UPDATE `%DB_PREFIX%conf` SET `is_effect`='0' WHERE (`name`='USER_VERIFY_STATUS');

INSERT INTO `%DB_PREFIX%conf` (`name`, `value`, `group_id`, `input_type`, `value_scope`, `is_effect`, `is_conf`, `sort`) VALUES ('BG_PAGE', '', 1, 2, '', 1, 1, 24);

INSERT INTO `%DB_PREFIX%conf` (`name`, `value`, `group_id`, `input_type`, `value_scope`, `is_effect`, `is_conf`, `sort`) VALUES ('BG_APP', '', 1, 2, '', 1, 1, 24);

UPDATE `%DB_PREFIX%conf` SET `value`='2.291' WHERE (`name`='DB_VERSION');

UPDATE `%DB_PREFIX%m_config` SET `desc`='默认：否；IOS版本只有苹果支付；（开启其他支付(微信,支付宝等)选择：是；注：若开启，则IOS会有被苹果下架风险）' WHERE (`code`='ios_open_pay');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('domain_list', '备用域名', '应用设置', '', 3, 100, NULL, NULL, '备用域名列表，每行填写一个域名');

UPDATE `%DB_PREFIX%conf` SET `value`='2.292' WHERE (`name`='DB_VERSION');

ALTER TABLE `%DB_PREFIX%video`
ADD COLUMN `len_time`  int(11) NOT NULL COMMENT '直播的时长';

ALTER TABLE `%DB_PREFIX%video_history`
ADD COLUMN `len_time`  int(11) NOT NULL COMMENT '直播的时长';

UPDATE `%DB_PREFIX%conf` SET `value`='2.3' WHERE (`name`='DB_VERSION');

ALTER TABLE `%DB_PREFIX%video`
ADD COLUMN `is_concatvideo`  tinyint(1) NOT NULL COMMENT '视频是否合并 0 未合并，1 已合并';

ALTER TABLE `%DB_PREFIX%video_history`
ADD COLUMN `is_concatvideo`  tinyint(1) NOT NULL COMMENT '视频是否合并 0 未合并，1 已合并';

UPDATE `%DB_PREFIX%m_config` SET `desc`='备用域名列表，每行填写一个域名，头部要包含http或https，例如 http://www.xx.com' WHERE (`code`='domain_list');

CREATE TABLE `%DB_PREFIX%login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `create_time` varchar(20) NOT NULL COMMENT '登录时间',
  `ip` varchar(20) NOT NULL COMMENT 'ip',
  `login_time` int(11) NOT NULL COMMENT '登录时间',
  `login_date` datetime NOT NULL COMMENT '登录时间',
  `login_type` tinyint(1) NOT NULL COMMENT '登录方式',
  `request` text NOT NULL COMMENT '请求参数',
  `ctl_act` varchar(20) NOT NULL COMMENT '请求接口',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

UPDATE `%DB_PREFIX%conf` SET `value`='2.31' WHERE (`name`='DB_VERSION');

ALTER TABLE `%DB_PREFIX%video`
ADD COLUMN `stick`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶 0 不置顶 1 置顶';

ALTER TABLE `%DB_PREFIX%video_history`
ADD COLUMN `stick`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶 0 不置顶 1 置顶';

ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `investor_time`  int(10) NOT NULL COMMENT '审核失败时间';

ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `alone_ticket_ratio`  varchar(255) NOT NULL COMMENT '设置主播提现比例,如果为空,则使用后台通用比例';

ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `open_game`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启游戏  0为 不禁用 1 为禁用';
ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `open_pay`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启付费  0为 不禁用 1 为禁用';
ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `open_auction`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启竞拍  0为 不禁用 1 为禁用';
ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `family_recom`  varchar(6) NOT NULL COMMENT '家族推荐号 填写后审核通过自动加入相对应的家族';
ALTER TABLE `%DB_PREFIX%family`
ADD COLUMN `family_recom`  varchar(6) NOT NULL COMMENT '家族推荐号 创建家族后随机生成，用于主播审核时填写';

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('attestation_time', '认证审核时间', '应用设置', '0', 0, 2, '', '', '审核失败后下次可申请的时间（单位：秒）');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('top_weight', '置顶权重值', '应用设置', '1', 0, 2, '', '', '设置视频置顶时增加的权重(单位：亿)');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('refund_explain', '提现说明', '提现设置', '', 3, 100, '', '', '');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('speak_level', '发言等级', '应用设置', '0', 0, 2, '', '', '设置多少级才可以发言');

CREATE TABLE `%DB_PREFIX%video_classified` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) NOT NULL COMMENT '分类名称',
  `is_effect` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效 1-有效 0-无效',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '从大到小排',
  PRIMARY KEY (`id`),
  KEY `idx_vc_001` (`title`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='分类表';

ALTER TABLE `%DB_PREFIX%video`
ADD COLUMN `classified_id`  int(11) NOT NULL DEFAULT 0 COMMENT '分类id';

ALTER TABLE `%DB_PREFIX%video_history`
ADD COLUMN `classified_id`  int(11) NOT NULL DEFAULT 0 COMMENT '分类id';

ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `classified_id`  int(11) NOT NULL DEFAULT 0 COMMENT '分类id';

ALTER TABLE `%DB_PREFIX%user_refund`
ADD COLUMN `confirm_cash_ip`  varchar(255) NOT NULL  COMMENT '确认提现操作IP';

UPDATE `%DB_PREFIX%conf` SET `value`='2.32' WHERE (`name`='DB_VERSION');

ALTER TABLE `%DB_PREFIX%login_log`
MODIFY COLUMN `id`  int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID' FIRST ;

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('forced_upgrade', '客户端是否强制升级', 'APP版本管理', '0', 4, 99, '0,1', '否,是', '开启强制升级，不升级无法进入直播间 0:否;1:是');
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('forced_upgrade_tips', '强制升级', 'APP版本管理', '请升级后,观看视频【我的==>设置==>检查版本】', 3, 100, '', '', '开启强制升级的提醒');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('agora_app_id', '声网AppID', '应用设置', '', 0, 111, '', '', '声网AppID');
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('agora_app_certificate', '声网AppCertificate', '应用设置', '', 0, 112, '', '', '声网AppCertificate');
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('agora_anchor_resolution', '主播分辨率', '应用设置', '0', 4, 113, '0,1,2,3', '240*424,360*640,480*848,720*1280', '主播分辨率');
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('agora_audience_resolution', '连麦观众分辨率', '应用设置', '0', 4, 114, '0,1,2,3', '180*320,240*424,360*640,480*848', '连麦观众分辨率');

ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `allinpay_user_id`  VARCHAR (20) NOT NULL COMMENT '通联支付的用户ID(在通联网站的注册的userID)';

UPDATE `%DB_PREFIX%conf` SET `value`='2.33' WHERE (`name`='DB_VERSION');


UPDATE `%DB_PREFIX%m_config` SET `title`='腾讯云直播appid', `desc`='腾讯云直播APP_ID' WHERE (`code`='vodset_app_id');

UPDATE `%DB_PREFIX%m_config` SET `sort`='1',`desc`='腾讯云云通信SdkAppId' WHERE (`code`='tim_sdkappid');
UPDATE `%DB_PREFIX%m_config` SET `sort`='2',`desc`='腾讯云云通信账号管理员' WHERE (`code`='tim_identifier');
UPDATE `%DB_PREFIX%m_config` SET `sort`='3',`desc`='腾讯云云通信accountType' WHERE (`code`='tim_account_type');

UPDATE `%DB_PREFIX%m_config` SET `sort`='10',`desc`='腾讯云直播管理推流防盗key' WHERE (`code`='qcloud_security_key');
UPDATE `%DB_PREFIX%m_config` SET `sort`='11',`desc`='腾讯云直播管理API鉴权key' WHERE (`code`='qcloud_auth_key');
UPDATE `%DB_PREFIX%m_config` SET `sort`='12',`desc`='腾讯云直播APP_ID' WHERE (`code`='vodset_app_id');
UPDATE `%DB_PREFIX%m_config` SET `sort`='13',`desc`='腾讯云直播bizid' WHERE (`code`='qcloud_bizid');

UPDATE `%DB_PREFIX%m_config` SET `title`='云API帐户SecretId', `sort`='37',`desc`='腾讯【云API帐户SecretId】' WHERE (`code`='qcloud_secret_id');
UPDATE `%DB_PREFIX%m_config` SET `title`='云API密钥SecretKey', `sort`='37',`desc`='腾讯【云API密钥SecretKey】' WHERE (`code`='qcloud_secret_key');
UPDATE `%DB_PREFIX%m_config` SET `sort`='38',`desc`='保存视频（可用于回播）;0:否;1:是' WHERE (`code`='has_save_video');
UPDATE `%DB_PREFIX%m_config` SET `sort`='38',`desc`='清晰度越高,流量费用越高' WHERE (`code`='video_resolution_type');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('open_usersig_cache', '强制更新usersig', '腾讯直播', '0', 4, 4, '0,1', '否,是', '开启强制更新usersig缓存，0关闭 1开启 默认不开启');

INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'mission_switch', '每日在线任务开关', '基础配置', '1', '4', '0', '0,1', '关,开', '');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'mission_money', '每日在线任务每次领取金额', '基础配置', '10', '0', '0', null, null, '每日在线任务每次领取金额');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'mission_max_times', '每日在线任务最大领取次数', '基础配置', '10', '0', '0', null, null, '每日在线任务最大领取次数');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'mission_time', '每日在线任务每次领取间隔', '基础配置', '600', '0', '0', null, null, '每日在线任务每次领取间隔，单位秒');

INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'mission_name', '每日在线任务奖励标题', '基础配置', '免费领取10钻石', '0', '0', null, null, '每日在线任务奖励标题');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'mission_desc', '每日在线任务奖励说明', '基础配置', '等级越高免费领取钻石越多', '0', '0', null, null, '每日在线任务奖励说明');
DELETE FROM `%DB_PREFIX%m_config` WHERE `code` = 'mission_desc';
DELETE FROM `%DB_PREFIX%m_config` WHERE `code` = 'mission_name';
DELETE FROM `%DB_PREFIX%m_config` WHERE `code` = 'mission_time';
DELETE FROM `%DB_PREFIX%m_config` WHERE `code` = 'mission_max_times';
DELETE FROM `%DB_PREFIX%m_config` WHERE `code` = 'mission_money';

CREATE TABLE `%DB_PREFIX%mission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '任务标题',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `money` int(255) DEFAULT NULL COMMENT '奖励数',
  `time` int(11) DEFAULT NULL COMMENT '间隔时间',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `is_effect` tinyint(4) DEFAULT NULL COMMENT '是否有效',
  `is_order` tinyint(4) DEFAULT NULL COMMENT '是否排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
INSERT INTO `%DB_PREFIX%mission` VALUES ('1', '领取10钻石', '', '10', '0', '1', '1', '1');
INSERT INTO `%DB_PREFIX%mission` VALUES ('2', '领取20钻石', null, '20', '10', '2', '1', '1');
INSERT INTO `%DB_PREFIX%mission` VALUES ('3', '领取30钻石', null, '30', '20', '3', '1', '1');


INSERT INTO `%DB_PREFIX%m_config` VALUES (null, 'index_recommend', 'PC端首页推荐主播', 'PC端设置', '', 0, 12, NULL, NULL, 'PC端首页推荐主播,输入主播ID(多个主播以，分开，最多6个)，当主播直播时，推荐至首页直播');
INSERT INTO `%DB_PREFIX%m_config` VALUES (null, 'tourist_chat', '直播间游客发言', 'PC端设置', '', 4, 1,'0,1', '否，是', '是否开启游客发言，开启后未登录游客可在直播间发言');

UPDATE `%DB_PREFIX%conf` SET `value`='2.35' WHERE (`name`='DB_VERSION');

UPDATE `%DB_PREFIX%m_config` SET `desc`='手机端配置版本号格式(yyyymmddnn)(用接口初始化)' WHERE (`code`='init_version');

INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'robot_prop_num', '机器人送礼个数', '基础配置', '1', '0', '5', null, null, '机器人送礼个数');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'robot_prop_diamonds', '机器人每个礼物的价值', '基础配置', '50', '0', '5', null, null, '机器人送礼价值');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'robot_prop_total_diamonds', '机器人所有礼物价值', '基础配置', '200', '0', '5', null, null, '机器人送礼总价值');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'robot_prop_interval', '机器人送礼间隔', '基础配置', '300', '0', '5', null, null, '机器人送礼间隔时间，单位（秒）');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'robot_prop_real_interval', '机器人真人送礼间隔', '基础配置', '600', '0', '5', null, null, '机器人送礼与真人送礼间隔时间，单位（秒）');



UPDATE `%DB_PREFIX%conf` SET `value`='2.36' WHERE (`name`='DB_VERSION');

CREATE TABLE `%DB_PREFIX%key_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` varchar(255) NOT NULL COMMENT '手机端类型',
  `aes_key` text NOT NULL COMMENT '加密KEY',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 1：是 0：否',
  `is_init` tinyint(1) NOT NULL COMMENT '是否打包填写 1是 、0否',
  `version` varchar(255) NOT NULL COMMENT '版本',
  `is_effect` varchar(255) NOT NULL COMMENT '是否有效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='加密KET列表';

UPDATE `%DB_PREFIX%conf` SET `value`='2.36' WHERE (`name`='DB_VERSION');

ALTER TABLE `%DB_PREFIX%video_lianmai`
ADD COLUMN `channelid`  varchar(255) NOT NULL COMMENT '有些早期提供的API中直播码参数被定义为channel_id，新的API则称直播码为stream_id，仅历史原因而已';
ALTER TABLE `%DB_PREFIX%video_lianmai`
ADD COLUMN `play_rtmp`  varchar(255) NOT NULL COMMENT '小主播的rtmpAcc地址';
ALTER TABLE `%DB_PREFIX%video_lianmai`
ADD COLUMN `play_rtmp_acc`  varchar(255) NOT NULL COMMENT '';
ALTER TABLE `%DB_PREFIX%video_lianmai`
ADD COLUMN `v_play_rtmp_acc`  varchar(255) NOT NULL COMMENT '';

ALTER TABLE `%DB_PREFIX%video_lianmai_history`
ADD COLUMN `channelid`  varchar(255) NOT NULL COMMENT '有些早期提供的API中直播码参数被定义为channel_id，新的API则称直播码为stream_id，仅历史原因而已';
ALTER TABLE `%DB_PREFIX%video_lianmai_history`
ADD COLUMN `play_rtmp`  varchar(255) NOT NULL COMMENT '小主播的rtmpAcc地址';
ALTER TABLE `%DB_PREFIX%video_lianmai_history`
ADD COLUMN `play_rtmp_acc`  varchar(255) NOT NULL COMMENT '';
ALTER TABLE `%DB_PREFIX%video_lianmai_history`
ADD COLUMN `v_play_rtmp_acc`  varchar(255) NOT NULL COMMENT '';

INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'obs_download', '直播工具下载地址', 'PC端设置', null, '0', '12', null, null, '直播工具下载地址');

UPDATE `%DB_PREFIX%conf` SET `value`='2.37' WHERE (`name`='DB_VERSION');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES
('open_visitors_login', '游客登录', '第三方帐户', '0', 4, 4, '0,1', '否,是', '是否开启游客登录 0:否;1:是');

ALTER TABLE `%DB_PREFIX%user`
MODIFY COLUMN `login_type`  tinyint(1) NOT NULL COMMENT '0：微信；1：QQ；2：手机；3：微博 ;4 : 游客登录';

UPDATE `%DB_PREFIX%conf` SET `value`='2.38' WHERE (`name`='DB_VERSION');

INSERT INTO `%DB_PREFIX%m_config` VALUES ('', 'search_change', 'APP搜索类型', '应用设置', '0', '4', '150', '0,1', '精确搜索,模糊搜索', '设置APP搜索类型 0精确 1模糊');

ALTER TABLE `%DB_PREFIX%mission`
ADD COLUMN `type`  tinyint DEFAULT 0 COMMENT '任务类型：0在线任务,1玩游戏任务,2打赏主播任务,3分享主播任务,4关注主播任务',
ADD COLUMN `target`  int DEFAULT 1 COMMENT '目标数量';

ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `no_ticket` int(11) NOT NULL COMMENT '特权机器人送出的亲贝（不可提现）';

ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `roboter` tinyint(1) NOT NULL COMMENT '0未开启机器人礼物账号特权，1开启机器人礼物账号特权';

UPDATE `%DB_PREFIX%conf` SET `value`='2.39' WHERE (`name`='DB_VERSION');

UPDATE `%DB_PREFIX%m_config` SET `value_scope`= '1,2,5', `title_scope`= '腾讯云直播,金山云,阿里云' WHERE (`code`='video_type');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('aliyun_access_key', 'Access Key ID', '阿里云', '', 0, 0, NULL, NULL, NULL);
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('aliyun_access_secret', 'Access Key Secret', '阿里云', '', 0, 0, NULL, NULL, NULL);
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('aliyun_region', '阿里云节点', '阿里云', 'cn-shanghai', 0, 0, NULL, NULL, NULL);
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('aliyun_private_key', '推流鉴权key', '阿里云', '', 0, 0, NULL, NULL, NULL);
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('aliyun_vhost', '加速域名', '阿里云', '', 3, 0, NULL, NULL, '一行一个域名');

CREATE TABLE `%DB_PREFIX%video_aliyun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vhost` varchar(255) NOT NULL,
  `stream_id` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `video_num` (`stream_id`),
  KEY `vhost` (`vhost`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

UPDATE `%DB_PREFIX%conf` SET `value`='2.40' WHERE (`name`='DB_VERSION');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('account_ip', '后台登录IP', '基础配置', '', 3, 120, '', '', '后台允许登录的ip,每行填写一个ip');
UPDATE `%DB_PREFIX%m_config` SET `type`='3',`desc`= '后台允许登录的ip,每行填写一个ip' WHERE (`code`='account_ip' and `type`<>'3');


INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('account_mobile', '后台登录绑定手机号', '基础配置', '', 0, 121, '', '', '后台登录接收验证码手机');
ALTER TABLE `%DB_PREFIX%plugin`
ADD COLUMN `price`  int NOT NULL DEFAULT 0 COMMENT '价格';
CREATE TABLE `%DB_PREFIX%plugin_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) NOT NULL COMMENT '插件id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `create_time` int(11) NOT NULL COMMENT '创建时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
UPDATE `%DB_PREFIX%user_log` SET user_id = podcast_id WHERE type = 7 AND user_id = 1;
UPDATE `%DB_PREFIX%conf` SET `value`='2.41' WHERE (`name`='DB_VERSION');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('alipay_cache_time', '支付宝更换缓存', '应用设置', '60', 0, 130, '', '', '（秒）多支付宝账号更新间隔，时间最少不能低于60秒');

UPDATE `%DB_PREFIX%conf` SET `value`='2.41' WHERE (`name`='DB_VERSION');

ALTER TABLE `%DB_PREFIX%video_aliyun`
ADD COLUMN `create_time`  int(11) NOT NULL AFTER `stream_id`;

INSERT INTO `%DB_PREFIX%m_config` VALUES (null, 'pc_is_open_recharge', 'PC充值开关', 'PC端设置', '1', 4, 0, '0,1', '否,是', '是否开启充值功能，1开启 0关闭');
INSERT INTO `%DB_PREFIX%m_config` VALUES (null, 'pc_is_open_exchange', 'PC提现开关', 'PC端设置', '1', 4, 0, '0,1', '否,是', '是否开启提现功能，1开启 0关闭');

insert into `%DB_PREFIX%role_module` values('','WarningMsg','警告列表',1,0);
insert into `%DB_PREFIX%role_node` values('','index','首页',1,0,0,(select id from `%DB_PREFIX%role_module` where module='WarningMsg'));
insert into `%DB_PREFIX%role_node` values('','add','添加',1,0,0,(select id from `%DB_PREFIX%role_module` where module='WarningMsg'));
insert into `%DB_PREFIX%role_node` values('','edit','编辑',1,0,0,(select id from `%DB_PREFIX%role_module` where module='WarningMsg'));
insert into `%DB_PREFIX%role_node` values('','foreverdelete','删除',1,0,0,(select id from `%DB_PREFIX%role_module` where module='WarningMsg'));
insert into `%DB_PREFIX%role_node` values('','set_effect','设置有效',1,0,0,(select id from `%DB_PREFIX%role_module` where module='WarningMsg'));

UPDATE `%DB_PREFIX%conf` SET `value`='2.42' WHERE (`name`='DB_VERSION');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('send_msg_lv', '发言等级限制', '应用设置', '1', 0, 160, NULL, NULL, '会员等级>=当前设定的等级时,才能进行发言');
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('is_show_identify_number', '是否需要身份验证', '应用设置', '1', 4, 160, '0,1', '否,是', '认证时是否需要输入身份证号码 0否 1是');
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('identify_hold_example', '手持身份证示例图片', '应用设置', '', 2, 160, NULL, NULL, '手持身份证示例图片');
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('show_follow_msg', '是否显示关注提示信息', '应用设置', '1', 4, 165, '0,1', '否,是', '是否发送用户关注提示信息到直播间 0否 1是');
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('show_follow_msg_lv', '显示关注提示所需等级', '应用设置', '1', 0, 166, NULL, NULL, '会员等级>=当前设定的等级时,才显示关注信息到直播间');



INSERT INTO `%DB_PREFIX%m_config` (`id`, `code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('', 'is_change_name', '更改昵称付费', '基础配置', '0', '4', '0', '0,1', '关,开', '更改昵称付费开关，用户可免费更改一次昵称');
INSERT INTO `%DB_PREFIX%m_config` (`id`, `code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('', 'change_name', '更改昵称收费值', '基础配置', '100', '0', '0', '', '', '更改昵称收费值，用户可免费更改一次昵称（钻石）');

ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `is_change_name` int(1) NOT NULL DEFAULT '0' COMMENT '是否修改过昵称（1=是，0否）';

INSERT INTO `%DB_PREFIX%m_config` VALUES (null, 'ksyun_app', 'Ksyun App', '金山云', '', '0', '0', null, null, null);
INSERT INTO `%DB_PREFIX%m_config` VALUES (null, 'ksyun_domain', 'Ksyun Domain', '金山云', '', '0', '0', null, null, null);
INSERT INTO `%DB_PREFIX%m_config` VALUES (null, 'ks3_accesskey', 'ks3 Accesskey', '金山云', '', '0', '0', null, null, null);
INSERT INTO `%DB_PREFIX%m_config` VALUES (null, 'ks3_secretkey', 'ks3 Secretkey', '金山云', '', '0', '0', null, null, null);

ALTER TABLE `%DB_PREFIX%video_aliyun`
ADD COLUMN `create_time` int(11) NOT NULL;

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('app_theme', '手机端主题', '应用设置', '0', 4, 180, '0,1', '默认,绿色', '手机端主题');

UPDATE `%DB_PREFIX%conf` SET `value`='2.43' WHERE (`name`='DB_VERSION');

ALTER TABLE `%DB_PREFIX%video_lianmai`
ADD COLUMN `push_rtmp`  varchar(255) NOT NULL COMMENT '小主播推流地址' AFTER `channelid`;

ALTER TABLE `%DB_PREFIX%video_lianmai_history`
ADD COLUMN `push_rtmp`  varchar(255) NOT NULL COMMENT '小主播推流地址' AFTER `channelid`;

UPDATE `%DB_PREFIX%conf` SET `value`='2.42' WHERE (`name`='DB_VERSION');

UPDATE `%DB_PREFIX%m_config` SET `desc`='家族收取主播收益的比例(如 10% 则填10)' WHERE (`code`='profit_ratio');

UPDATE `%DB_PREFIX%conf` SET `value`='2.42' WHERE (`name`='DB_VERSION');

CREATE TABLE `%DB_PREFIX%video_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id,也是房间room_id',
  `title` varchar(255) NOT NULL COMMENT '直播标题',
  `user_id` int(11) NOT NULL COMMENT '项目id',
  `live_in` tinyint(1) DEFAULT '0' COMMENT '是否直播中 1-直播中 0-已停止;2:正在创建直播;3:回看',
  `watch_number` int(11) DEFAULT '0' COMMENT '当前实时观看人数（实际,不含虚拟人数,不包含机器人)',
  `virtual_watch_number` int(10) NOT NULL DEFAULT '0' COMMENT '当前虚拟观看人数',
  `vote_number` int(11) DEFAULT '0' COMMENT '获得票数',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '话题id',
  `province` varchar(20) NOT NULL COMMENT '省份',
  `city` varchar(20) NOT NULL COMMENT '城市',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `begin_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `end_date` date NOT NULL COMMENT '结束日期',
  `group_id` varchar(50) NOT NULL COMMENT '群组ID,通过create_group后返回的值;直播结束后解散群',
  `destroy_group_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：未解散;0:已解散;其它为ErrorCode错码',
  `long_polling_key` varchar(255) NOT NULL COMMENT '通过create_group后返回的LongPollingKey值',
  `max_watch_number` int(10) NOT NULL DEFAULT '0' COMMENT '最大观看人数(每进来一人次加1）',
  `room_type` tinyint(1) NOT NULL COMMENT '房间类型 : 1私有群（Private）,0公开群（Public）,2聊天室（ChatRoom）,3互动直播聊天室（AVChatRoom）',
  `is_playback` tinyint(1) DEFAULT '0' COMMENT '是否可回放 0-否 ；1-是',
  `video_vid` varchar(255) NOT NULL COMMENT '视频地址',
  `monitor_time` datetime NOT NULL COMMENT '最后心跳监听时间；如果超过监听时间，则说明主播已经掉线了',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:删除;0:未删除;私有聊天或小于5分钟的视频，不保存',
  `robot_num` int(10) NOT NULL DEFAULT '0' COMMENT '聊天群中机器人数量',
  `robot_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加机器人时间（每隔20秒左右加几个人）',
  `channelid` varchar(50) NOT NULL COMMENT '旁路直播,频道ID',
  `is_aborted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:被服务器异常终止结束(主要是心跳超时)',
  `is_del_vod` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:表示已经清空了,录制视频;0:未做清空操作',
  `online_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '主播在线状态;1:在线(默认); 0:离开',
  `tipoff_count` int(10) NOT NULL DEFAULT '0' COMMENT '举报次数',
  `private_key` varchar(32) NOT NULL COMMENT '私密直播key',
  `share_type` varchar(30) NOT NULL COMMENT '分享类型WEIXIN,WEIXIN_CIRCLE,QQ,QZONE,SINA',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '热门排序',
  `pai_id` int(11) NOT NULL DEFAULT '0' COMMENT '竞拍id',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 0:未知, 1-男，2-女',
  `video_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:腾讯云互动直播;1:腾讯云直播',
  `sort_num` int(10) NOT NULL DEFAULT '0' COMMENT 'sort_init + share_count * 分享权重 + like_count * 点赞权重 + fans_count * 关注权重 + sort * 排序权重 + ticket(本场收到的印票) * 印票权重',
  `create_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:APP端创建的直播;1:PC端创建的直播',
  `max_robot_num` int(10) NOT NULL DEFAULT '0' COMMENT '默认最大机器人头像数',
  `share_count` int(10) NOT NULL DEFAULT '0' COMMENT '分享数',
  `like_count` int(10) NOT NULL DEFAULT '0' COMMENT '点赞数,每个用户只记录一次',
  `fans_count` int(10) NOT NULL DEFAULT '0' COMMENT '本场直播净添加的粉丝数即：被关注数，关注加1，取消减1',
  `sort_init` int(10) NOT NULL DEFAULT '0' COMMENT 'sort_init(初始排序权重) = (用户可提现印票：fanwe_user.ticket - fanwe_user.refund_ticket) * 保留印票权重+ 直播/回看[回看是：0; 直播：9000000000 直播,需要排在最上面 ]+ fanwe_user.user_level * 等级权重+ fanwe_user.fans_count * 当前有的关注数权重',
  `push_rtmp` varchar(255) NOT NULL COMMENT '推流地址',
  `play_flv` varchar(255) NOT NULL COMMENT '播放地址；当video_type=0时，记录：傍路直播地址',
  `play_rtmp` varchar(255) NOT NULL COMMENT '播放地址；当video_type=0时，记录：傍路直播地址',
  `play_mp4` varchar(255) NOT NULL COMMENT '播放地址；当video_type=0时，记录：傍路直播地址',
  `play_hls` varchar(255) NOT NULL COMMENT '播放地址；当video_type=0时，记录：傍路直播地址',
  `xpoint` decimal(10,6) NOT NULL DEFAULT '0.000000' COMMENT 'x座标(用来计算：附近)',
  `ypoint` decimal(10,6) NOT NULL DEFAULT '0.000000' COMMENT 'y座标(用来计算：附近)',
  `head_image` varchar(255) NOT NULL COMMENT '直播时，可自定义封面图; 如果不存在,则取会员头像',
  `thumb_head_image` varchar(255) NOT NULL COMMENT '模糊图片',
  `play_url` varchar(255) NOT NULL COMMENT '播放地址',
  `live_image` varchar(255) NOT NULL COMMENT '视频封面',
  `is_recommend` int(1) NOT NULL COMMENT '推荐视频 0不推荐、1推荐',
  `virtual_number` int(11) NOT NULL COMMENT '最大虚拟人数',
  `room_title` varchar(100) DEFAULT NULL COMMENT '直播间名称',
  `live_pay_time` int(11) NOT NULL COMMENT '开始收费时间',
  `is_live_pay` tinyint(1) NOT NULL COMMENT '是否收费模式  1是 0否',
  `live_fee` int(11) NOT NULL COMMENT '付费直播 收取多少费用； 每分钟收取多少钻石，主播端设置',
  `live_is_mention` tinyint(1) NOT NULL COMMENT '是否已经提档 1是、0否',
  `live_pay_count` tinyint(1) NOT NULL COMMENT '付费人数',
  `pay_room_id` int(11) NOT NULL COMMENT '付费直播的ID , 用于标示直播间付费 模式 ',
  `prop_table` varchar(255) NOT NULL DEFAULT 'fanwe_video_prop' COMMENT '直播礼物表',
  `live_pay_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '收费类型 0按时收费，1按场次收费,未开启收费模式,默认为2',
  `len_time` int(11) NOT NULL COMMENT '直播的时长',
  `is_concatvideo` tinyint(1) NOT NULL COMMENT '视频是否合并 0 未合并，1 已合并',
  `stick` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶 0 不置顶 1 置顶',
  `classified_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `tags` text NOT NULL COMMENT '标签',
  `video_status` tinyint(1) NOT NULL COMMENT '//视频当前状态, 0:无状态 、1：已保存、2：有分片、3：分片已合并、4：合并完成已删除分片，5：拉取完成',
  `vodtaskid` varchar(50) NOT NULL COMMENT '//任务id，用户根据此字段匹配服务端事件通知',
  `file_id` varchar(50) NOT NULL COMMENT '//腾讯云 的 视频ID',
  `source_url` varchar(255) NOT NULL COMMENT '//拉流原视频地址',
  PRIMARY KEY (`id`),
  KEY `idx_v_001` (`user_id`) USING BTREE,
  KEY `idx_v_003` (`live_in`) USING BTREE,
  KEY `idx_v_002` (`group_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='直播历史表';

INSERT INTO `%DB_PREFIX%video_check` (`id`, `title`, `user_id`, `live_in`, `watch_number`, `virtual_watch_number`, `vote_number`, `cate_id`, `province`, `city`, `create_time`, `begin_time`, `end_time`, `end_date`, `group_id`, `destroy_group_status`, `long_polling_key`, `max_watch_number`, `room_type`, `is_playback`, `video_vid`, `monitor_time`, `is_delete`, `robot_num`, `robot_time`, `channelid`, `is_aborted`, `is_del_vod`, `online_status`, `tipoff_count`, `private_key`, `share_type`, `sort`, `pai_id`, `sex`, `video_type`, `sort_num`, `create_type`, `max_robot_num`, `share_count`, `like_count`, `fans_count`, `sort_init`, `push_rtmp`, `play_flv`, `play_rtmp`, `play_mp4`, `play_hls`, `xpoint`, `ypoint`, `head_image`, `thumb_head_image`, `play_url`, `live_image`, `is_recommend`, `virtual_number`, `room_title`, `live_pay_time`, `is_live_pay`, `live_fee`, `live_is_mention`, `live_pay_count`, `pay_room_id`, `prop_table`, `live_pay_type`, `len_time`, `is_concatvideo`, `stick`, `classified_id`, `tags`, `video_status`, `vodtaskid`, `file_id`, `source_url`) VALUES (1, '新人直播', 167222, 0, 0, 0, 0, 740, '其他', '福州', 1472663047, 1472663047, 1472663149, '2016-9-1', '@TGS#aG6EBTBEO', 0, '', 23, 3, 0, '', '0000-0-0 00:00:00', 0, 23, 0, '9896587163584396075', 0, 0, 1, 0, '', '', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', '', 0.000000, 0.000000, './public/attachment/test/noavatar_2.JPG', '', './public/flash/2.mp4', '', 0, 0, '', 0, 0, 0, 0, 0, 0, 'fanwe_video_prop', 2, 0, 0, 0, 0, '', 0, '', '', '');
INSERT INTO `%DB_PREFIX%video_check` (`id`, `title`, `user_id`, `live_in`, `watch_number`, `virtual_watch_number`, `vote_number`, `cate_id`, `province`, `city`, `create_time`, `begin_time`, `end_time`, `end_date`, `group_id`, `destroy_group_status`, `long_polling_key`, `max_watch_number`, `room_type`, `is_playback`, `video_vid`, `monitor_time`, `is_delete`, `robot_num`, `robot_time`, `channelid`, `is_aborted`, `is_del_vod`, `online_status`, `tipoff_count`, `private_key`, `share_type`, `sort`, `pai_id`, `sex`, `video_type`, `sort_num`, `create_type`, `max_robot_num`, `share_count`, `like_count`, `fans_count`, `sort_init`, `push_rtmp`, `play_flv`, `play_rtmp`, `play_mp4`, `play_hls`, `xpoint`, `ypoint`, `head_image`, `thumb_head_image`, `play_url`, `live_image`, `is_recommend`, `virtual_number`, `room_title`, `live_pay_time`, `is_live_pay`, `live_fee`, `live_is_mention`, `live_pay_count`, `pay_room_id`, `prop_table`, `live_pay_type`, `len_time`, `is_concatvideo`, `stick`, `classified_id`, `tags`, `video_status`, `vodtaskid`, `file_id`, `source_url`) VALUES (2, '新人直播', 167222, 0, 0, 0, 0, 740, '其他', '福州市', 1472663136, 1472663136, 1472663151, '2016-9-1', '@TGS#aALFBTBEA', 0, '', 24, 3, 0, '', '0000-0-0 00:00:00', 0, 24, 0, '9896587163584396381', 0, 0, 1, 0, '', '', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', '', 0.000000, 0.000000, './public/attachment/test/noavatar_2.JPG', '', './public/flash/3.mp4', '', 0, 0, '', 0, 0, 0, 0, 0, 0, 'fanwe_video_prop', 2, 0, 0, 0, 0, '', 0, '', '', '');
INSERT INTO `%DB_PREFIX%video_check` (`id`, `title`, `user_id`, `live_in`, `watch_number`, `virtual_watch_number`, `vote_number`, `cate_id`, `province`, `city`, `create_time`, `begin_time`, `end_time`, `end_date`, `group_id`, `destroy_group_status`, `long_polling_key`, `max_watch_number`, `room_type`, `is_playback`, `video_vid`, `monitor_time`, `is_delete`, `robot_num`, `robot_time`, `channelid`, `is_aborted`, `is_del_vod`, `online_status`, `tipoff_count`, `private_key`, `share_type`, `sort`, `pai_id`, `sex`, `video_type`, `sort_num`, `create_type`, `max_robot_num`, `share_count`, `like_count`, `fans_count`, `sort_init`, `push_rtmp`, `play_flv`, `play_rtmp`, `play_mp4`, `play_hls`, `xpoint`, `ypoint`, `head_image`, `thumb_head_image`, `play_url`, `live_image`, `is_recommend`, `virtual_number`, `room_title`, `live_pay_time`, `is_live_pay`, `live_fee`, `live_is_mention`, `live_pay_count`, `pay_room_id`, `prop_table`, `live_pay_type`, `len_time`, `is_concatvideo`, `stick`, `classified_id`, `tags`, `video_status`, `vodtaskid`, `file_id`, `source_url`) VALUES (3, '新人直播', 167222, 0, 0, 0, 0, 740, '其他', '福州', 1472663779, 1472663779, 1472663848, '2016-9-1', '639857', 1, '', 22, 3, 0, '', '0000-0-0 00:00:00', 0, 22, 0, '9896587163584398658', 0, 0, 1, 0, '', '', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', '', 0.000000, 0.000000, './public/attachment/test/noavatar_2.JPG', '', './public/flash/5.mp4', '', 0, 0, '', 0, 0, 0, 0, 0, 0, 'fanwe_video_prop', 2, 0, 0, 0, 0, '', 0, '', '', '');
INSERT INTO `%DB_PREFIX%video_check` (`id`, `title`, `user_id`, `live_in`, `watch_number`, `virtual_watch_number`, `vote_number`, `cate_id`, `province`, `city`, `create_time`, `begin_time`, `end_time`, `end_date`, `group_id`, `destroy_group_status`, `long_polling_key`, `max_watch_number`, `room_type`, `is_playback`, `video_vid`, `monitor_time`, `is_delete`, `robot_num`, `robot_time`, `channelid`, `is_aborted`, `is_del_vod`, `online_status`, `tipoff_count`, `private_key`, `share_type`, `sort`, `pai_id`, `sex`, `video_type`, `sort_num`, `create_type`, `max_robot_num`, `share_count`, `like_count`, `fans_count`, `sort_init`, `push_rtmp`, `play_flv`, `play_rtmp`, `play_mp4`, `play_hls`, `xpoint`, `ypoint`, `head_image`, `thumb_head_image`, `play_url`, `live_image`, `is_recommend`, `virtual_number`, `room_title`, `live_pay_time`, `is_live_pay`, `live_fee`, `live_is_mention`, `live_pay_count`, `pay_room_id`, `prop_table`, `live_pay_type`, `len_time`, `is_concatvideo`, `stick`, `classified_id`, `tags`, `video_status`, `vodtaskid`, `file_id`, `source_url`) VALUES (4, '新人直播', 167222, 0, 0, 0, 0, 740, '其他', '福州市', 1472629428, 1472629428, 1472629706, '2016-8-31', '639863', 1, '', 22, 3, 0, '', '0000-0-0 00:00:00', 0, 22, 0, '9896587163584309061', 0, 0, 1, 0, '', '', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', '', 0.000000, 0.000000, './public/attachment/test/noavatar_2.JPG', '', './public/flash/1.mp4', '', 0, 0, '', 0, 0, 0, 0, 0, 0, 'fanwe_video_prop', 2, 0, 0, 0, 0, '', 0, '', '', '');
INSERT INTO `%DB_PREFIX%video_check` (`id`, `title`, `user_id`, `live_in`, `watch_number`, `virtual_watch_number`, `vote_number`, `cate_id`, `province`, `city`, `create_time`, `begin_time`, `end_time`, `end_date`, `group_id`, `destroy_group_status`, `long_polling_key`, `max_watch_number`, `room_type`, `is_playback`, `video_vid`, `monitor_time`, `is_delete`, `robot_num`, `robot_time`, `channelid`, `is_aborted`, `is_del_vod`, `online_status`, `tipoff_count`, `private_key`, `share_type`, `sort`, `pai_id`, `sex`, `video_type`, `sort_num`, `create_type`, `max_robot_num`, `share_count`, `like_count`, `fans_count`, `sort_init`, `push_rtmp`, `play_flv`, `play_rtmp`, `play_mp4`, `play_hls`, `xpoint`, `ypoint`, `head_image`, `thumb_head_image`, `play_url`, `live_image`, `is_recommend`, `virtual_number`, `room_title`, `live_pay_time`, `is_live_pay`, `live_fee`, `live_is_mention`, `live_pay_count`, `pay_room_id`, `prop_table`, `live_pay_type`, `len_time`, `is_concatvideo`, `stick`, `classified_id`, `tags`, `video_status`, `vodtaskid`, `file_id`, `source_url`) VALUES (5, '新人直播', 167222, 0, 0, 40, 0, 740, '其他', '福州', 1472663301, 1472663301, 1472663409, '2016-9-1', '639858', 1, '', 67, 3, 0, '', '0000-0-0 00:00:00', 0, 26, 0, '9896587163584396075', 0, 0, 1, 0, '', '', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', '', 0.000000, 0.000000, './public/attachment/test/noavatar_2.JPG', '', './public/flash/4.mp4', '', 0, 0, '', 0, 0, 0, 0, 0, 0, 'fanwe_video_prop', 2, 0, 0, 0, 0, '', 0, '', '', '');

UPDATE `%DB_PREFIX%conf` SET `value`='2.44' WHERE (`name`='DB_VERSION');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('family_profit_platform', '是否平台支付家族收益', '应用设置', '0', 4, 170, '0,1', '否,是', '家族长收益是否由平台支付 0否 1是；由平台支付则不从家族的主播进行抽成');

INSERT INTO `%DB_PREFIX%role_module` (`module`, `name`, `is_effect`, `is_delete`) VALUES ('Indexs', '网站数据统计', '1', '0');
INSERT INTO `%DB_PREFIX%role_module` (`module`, `name`, `is_effect`, `is_delete`) VALUES ('Index', '快速导航', '1', '0');
INSERT INTO `%DB_PREFIX%role_node` (`action`, `name`, `is_effect`, `is_delete`, `group_id`, `module_id`) VALUES ('main', '导航信息', '1', '0', '0', (select id from `fanwe_role_module` where module='Index'));
INSERT INTO `%DB_PREFIX%role_node` (`action`, `name`, `is_effect`, `is_delete`, `group_id`, `module_id`) VALUES ('statistics', '网站统计信息', '1', '0', '0', (select id from `fanwe_role_module` where module='Indexs'));

INSERT INTO `%DB_PREFIX%m_config` VALUES (null, 'pc_live_fee', 'PC端付费直播收费', '付费直播配置', '', '0', '12', null, null, '(钻石)PC付费直播默认值，需不低于按场付费最低收费，不高于按场付费最高收费');

ALTER TABLE `%DB_PREFIX%user`
MODIFY COLUMN `nick_name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户昵称';

ALTER TABLE `%DB_PREFIX%user`
MODIFY COLUMN `signature`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '个性签名';

UPDATE `%DB_PREFIX%conf` SET `value`='2.45' WHERE (`name`='DB_VERSION');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('is_prop_notify', '是否进行大礼物赠送的全服飞屏通告', '应用设置', '1', 4, 181, '0,1', '否,是', '赠送大礼物时是否要进行全服通告 0否 1是');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('private_letter_lv', '私信等级限制', '应用设置', '1', 0, 182, NULL, NULL, '等级>=当前设定的等级时,才能发送私信');

UPDATE `%DB_PREFIX%conf` SET `value`='2.491' WHERE (`name`='DB_VERSION');

UPDATE `%DB_PREFIX%m_config` SET `group_id`='第三方帐户', `sort`='5' WHERE (`code`='open_visitors_login');

UPDATE `%DB_PREFIX%m_config` SET `group_id`='排序权重', `sort`='21' WHERE (`code`='top_weight');

UPDATE `%DB_PREFIX%conf` SET `value`='2.492' WHERE (`name`='DB_VERSION');

ALTER TABLE `%DB_PREFIX%user_log` ADD COLUMN `contribution_id` int(11) default 0 COMMENT '公会贡献成员ID';

ALTER TABLE `%DB_PREFIX%video_prop` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201610` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201611` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201612` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201701` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201702` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201703` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201704` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201705` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201706` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201707` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201708` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201709` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201710` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201711` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201712` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201801` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201802` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201803` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';
ALTER TABLE `%DB_PREFIX%video_prop_201804` ADD COLUMN `is_private` int(4) default 0 COMMENT '判断是否为私信送礼 1表示私信 2表示不是私信';

UPDATE `%DB_PREFIX%conf` SET `value`='2.493' WHERE (`name`='DB_VERSION');

ALTER TABLE `%DB_PREFIX%distribution_log`
ADD COLUMN `diamonds`  decimal(13,2) NOT NULL COMMENT '邀请奖励的钻石';

ALTER TABLE `%DB_PREFIX%distribution_log`
ADD COLUMN `type`  tinyint(1) NOT NULL COMMENT '功能类型 0 分销功能 1邀请奖励';

INSERT INTO `%DB_PREFIX%m_config` (`code`, `val`, `type`, `sort`) VALUES ('reward_point_diamonds', '0', '0', '90');
UPDATE `%DB_PREFIX%m_config` SET `title`='邀请奖励钻石', `group_id`='分享设置', `desc`='邀请奖励功能 0代表关闭；大于0的整数代表开启,并且奖励对应数量的钻石' WHERE (`code`='reward_point_diamonds');

UPDATE `%DB_PREFIX%conf` SET `value`='2.494' WHERE (`name`='DB_VERSION');


UPDATE `%DB_PREFIX%conf` SET `value`='2.495' WHERE (`name`='DB_VERSION');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('is_no_light', '是否关闭点赞', '应用设置', '0', 4, 190, '0,1', '否,是', '直播页面是否关闭点赞功能');

ALTER TABLE `%DB_PREFIX%prop`
ADD COLUMN `gif_gift_show_style` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'GIF礼物模式 0:按像素显示模式 1:全屏显示模式 2:至少两条边贴边模式';

UPDATE `%DB_PREFIX%conf` SET `value`='2.496' WHERE (`name`='DB_VERSION');

ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `is_replace_qq`  tinyint(1) NOT NULL COMMENT '是否过更换QQ头像 ，0 否；1是';

ALTER TABLE `%DB_PREFIX%user`
ADD COLUMN `is_replace_wx`  tinyint(1) NOT NULL COMMENT '是否过更换微信头像 ，0 否；1是';

UPDATE `%DB_PREFIX%conf` SET `value`='2.497' WHERE (`name`='DB_VERSION');


CREATE TABLE `%DB_PREFIX%weibo` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '动态ID',
  `user_id` int(11) NOT NULL COMMENT '产生动态的用户UID',
  `type` char(50) NOT NULL COMMENT 'imagetext 图文 red_photo 红包图片 weixin 出售微信  video 视频动态  goods 商品 photo 写真',
  `content` varchar(255) NOT NULL COMMENT '文字内容',
  `photo_image` varchar(255) NOT NULL COMMENT '写真封面',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '售价',
  `sale_num` int(11) NOT NULL DEFAULT '0' COMMENT '购买数量',
  `data` text NOT NULL COMMENT '链接序列化，存储 图片列表和视频',
  `create_time` datetime NOT NULL COMMENT '产生时间戳',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0为下架 1为上架',
  `from` tinyint(2) NOT NULL DEFAULT '0' COMMENT '客户端类型，0：网站；1：手机网页版；2：android；3：iphone',
  `comment_count` int(10) NOT NULL DEFAULT '0' COMMENT '评论数',
  `repost_count` int(10) NOT NULL DEFAULT '0' COMMENT '分享数',
  `video_count` int(10) NOT NULL DEFAULT '0' COMMENT '视频点击数',
  `red_count` int(10) NOT NULL DEFAULT '0' COMMENT '红包数量',
  `tipoff_count` int(10) NOT NULL DEFAULT '0' COMMENT '被举报次数',
  `comment_all_count` int(10) NOT NULL DEFAULT '0' COMMENT '全部评论数目',
  `digg_count` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `is_repost` int(2) NOT NULL DEFAULT '0' COMMENT '是否转发 0-否  1-是',
  `is_audit` int(2) NOT NULL DEFAULT '1' COMMENT '是否已审核 0-未审核 1-已审核',
  `xpoint` varchar(25) NOT NULL DEFAULT '0' COMMENT '纬度',
  `ypoint` varchar(25) NOT NULL DEFAULT '0' COMMENT '经度',
  `address` varchar(255) NOT NULL COMMENT '发布地址',
  `province` varchar(50) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `is_recommend` tinyint(2) DEFAULT '1',
  `recommend_time` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '推荐时间',
  `sort_num` int(11) NOT NULL DEFAULT '0' COMMENT '排序权重',
  `is_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶 0 未置顶 1置顶',
  PRIMARY KEY (`id`),
  KEY `is_del` (`status`,`create_time`) USING BTREE,
  KEY `uid` (`user_id`,`status`,`create_time`) USING BTREE
) COMMENT='动态列表';

CREATE TABLE `%DB_PREFIX%weibo_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键，评论编号',
  `type` tinyint(1) DEFAULT '1' COMMENT '类型 1-评论 2- 点赞',
  `weibo_id` int(11) NOT NULL DEFAULT '0' COMMENT '评论的微博',
  `weibo_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '微博会员ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '评论者编号',
  `content` text NOT NULL COMMENT '评论内容',
  `to_comment_id` int(11) NOT NULL DEFAULT '0' COMMENT '被回复的评论的编号',
  `to_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '被回复的评论的作者的编号',
  `data` text NOT NULL COMMENT '所评论的内容的相关参数（序列化存储）',
  `create_time` datetime NOT NULL COMMENT '评论发布的时间',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标记删除（0：没删除，1：已删除）',
  `is_audit` tinyint(1) DEFAULT '1' COMMENT '是否已审核 0-未审核 1-已审核',
  `storey` int(11) DEFAULT '0' COMMENT '评论绝对楼层',
  `client_ip` char(15) DEFAULT NULL,
  `client_port` char(5) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0' COMMENT '读取时间',
  PRIMARY KEY (`comment_id`)
) COMMENT='评论和回复';

CREATE TABLE `%DB_PREFIX%weibo_distribution_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `from_user_id` int(11) NOT NULL COMMENT '用户ID',
  `to_user_id` int(11) NOT NULL COMMENT '获得抽成的 用户ID',
  `create_date` date NOT NULL COMMENT '日期字段,按日期归档',
  `weibo_money` double(20,4) DEFAULT '0.0000' COMMENT '动态金额',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `create_ym` varchar(12) NOT NULL COMMENT '年月 如:201610',
  `create_d` tinyint(2) NOT NULL COMMENT '日',
  `create_w` tinyint(2) NOT NULL COMMENT '周',
  `memo` varchar(50) NOT NULL COMMENT '消费描述',
  `type` tinyint(1) DEFAULT NULL,
  `type_cate` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`to_user_id`,`from_user_id`,`weibo_money`) USING BTREE
) ;

ALTER TABLE `%DB_PREFIX%user` ADD COLUMN weibo_count int(11) NOT NULL DEFAULT '0' COMMENT '动态数';
ALTER TABLE `%DB_PREFIX%user` ADD COLUMN weibo_sort_num int(11) NOT NULL DEFAULT '0' COMMENT '动态权重';
ALTER TABLE `%DB_PREFIX%user` ADD COLUMN weibo_recommend_weight int(11) NOT NULL DEFAULT '0' COMMENT '推荐权重';
ALTER TABLE `%DB_PREFIX%user` ADD COLUMN weixin_account varchar(100) NULL COMMENT '微信账号';
ALTER TABLE `%DB_PREFIX%user` ADD COLUMN weixin_price decimal(10,2) NOT NULL DEFAULT '0' COMMENT '微信价格';
ALTER TABLE `%DB_PREFIX%user` ADD COLUMN xpoint varchar(50) NOT NULL DEFAULT '0' COMMENT '经度' , ADD COLUMN ypoint varchar(50) NOT NULL DEFAULT '0' COMMENT '纬度';
ALTER TABLE `%DB_PREFIX%user` ADD COLUMN show_image text NOT NULL COMMENT '展示图片列表';
ALTER TABLE `%DB_PREFIX%user` ADD COLUMN weibo_refund_money decimal(20,2) NOT NULL DEFAULT 0 COMMENT '已提现金额' ;
ALTER TABLE `%DB_PREFIX%user` ADD COLUMN weibo_money decimal(20,2) NOT NULL DEFAULT 0 COMMENT '主播获得的金额';
ALTER TABLE `%DB_PREFIX%user` ADD COLUMN tipoff_count int(11) NOT NULL DEFAULT '0' COMMENT '被举报的次数';
ALTER TABLE `%DB_PREFIX%user` ADD COLUMN weibo_photo_img varchar(255) NOT NULL COMMENT '会员中心海报' ;
ALTER TABLE `%DB_PREFIX%user` ADD COLUMN weibo_chat_price decimal(10,2) NOT NULL DEFAULT 0 COMMENT '聊天价格' ;
ALTER TABLE `%DB_PREFIX%user` ADD COLUMN weixin_account_time datetime NULL COMMENT '微信更新时间' ;
ALTER TABLE `%DB_PREFIX%tipoff` ADD COLUMN weibo_id int(11) NOT NULL DEFAULT 0 COMMENT '被举报的动态ID';

CREATE TABLE `%DB_PREFIX%qk_svideo_favor` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '用户ID',
  `weibo_id` varchar(255) NOT NULL COMMENT '收藏的ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_001` (`user_id`,`weibo_id`)
) COMMENT='我收藏的小视屏';

ALTER TABLE `%DB_PREFIX%weibo` ADD COLUMN `unlike_count` int(11) NOT NULL DEFAULT '0' COMMENT '踩一下数' AFTER `comment_all_count`;
INSERT INTO `%DB_PREFIX%m_config` VALUES (null, 'diamonds_name', '钻石名称', '基础配置', '钻石', '0', '1', null, null, '名称');

insert into `%DB_PREFIX%role_module` values('','PropStatistics','消耗统计',1,0);
INSERT INTO `%DB_PREFIX%role_node` (`id`, `action`, `name`, `is_effect`, `is_delete`, `group_id`, `module_id`) VALUES (301, 'consume_statistics', '道具消耗统计', 1, 0, 0, (select id from `%DB_PREFIX%role_module` where module='PropStatistics'));
INSERT INTO `%DB_PREFIX%role_node` (`id`, `action`, `name`, `is_effect`, `is_delete`, `group_id`, `module_id`) VALUES (302, 'detail', '道具消耗明细', 1, 0, 0, (select id from `%DB_PREFIX%role_module` where module='PropStatistics'));
INSERT INTO `%DB_PREFIX%role_node` (`id`, `action`, `name`, `is_effect`, `is_delete`, `group_id`, `module_id`) VALUES (303, 'export_csv', '导出', 1, 0, 0, (select id from `%DB_PREFIX%role_module` where module='PropStatistics'));

INSERT INTO `%DB_PREFIX%role_node` (`id`, `action`, `name`, `is_effect`, `is_delete`, `group_id`, `module_id`) VALUES (305, 'statistics', '公会长收益统计', 1, 0, 0, (select id from `%DB_PREFIX%role_module` where module='Society'));

insert into `%DB_PREFIX%role_module` values('','UserStatistics','私信收礼统计',1,0);
INSERT INTO `%DB_PREFIX%role_node` (`id`, `action`, `name`, `is_effect`, `is_delete`, `group_id`, `module_id`) VALUES (306, 'private_statistics', '统计列表', 1, 0, 0, (select id from `%DB_PREFIX%role_module` where module='UserStatistics'));
INSERT INTO `%DB_PREFIX%role_node` (`id`, `action`, `name`, `is_effect`, `is_delete`, `group_id`, `module_id`) VALUES (307, 'private_detail', '私信收礼明细', 1, 0, 0, (select id from `%DB_PREFIX%role_module` where module='UserStatistics'));
INSERT INTO `%DB_PREFIX%role_node` (`id`, `action`, `name`, `is_effect`, `is_delete`, `group_id`, `module_id`) VALUES (308, 'export_csv', '导出', 1, 0, 0, (select id from `%DB_PREFIX%role_module` where module='UserStatistics'));

CREATE TABLE `%DB_PREFIX%ban_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
	`user_id` int(11) NOT NULL COMMENT '用户ID',
	`ban_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '禁播类型  0 禁止单账号直播, 1 禁止同IP直播，2 禁止同设备直播',
	`ban_ip` varchar(50) NOT NULL COMMENT '禁播时用户登陆的IP',
	`apns_code` varchar(64) NOT NULL COMMENT '友盟消息推送服务对设备的唯一标识。Android的device_token是44位字符串, iOS的device-token是64位。',
  `ban_time` int(11) NOT NULL DEFAULT '0' COMMENT '设置禁播时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='禁播名单';

UPDATE `%DB_PREFIX%conf` SET `value`='2.498' WHERE (`name`='DB_VERSION');

INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('view_page_size', '观众列表数量', '应用设置', '50', 0, 195, NULL, NULL, '直播间观众列表返回数量');
INSERT INTO `%DB_PREFIX%m_config` (`code`, `title`, `group_id`, `val`, `type`, `sort`, `value_scope`, `title_scope`, `desc`) VALUES ('is_classify', '是否需要强制选择分类', '应用设置', '0', 4, 191, '0,1', '否,是', '创建直播时是否需要强制选择分类');

UPDATE `%DB_PREFIX%conf` SET `value`='2.499' WHERE (`name`='DB_VERSION');

UPDATE `%DB_PREFIX%m_config` SET `desc`=' (%)IOS默认美颜度(10%则填10,不能小于 1)' WHERE (`code`='beauty_ios');
UPDATE `%DB_PREFIX%m_config` SET `desc`=' (%)ANDROID默认美颜度(10%则填10,不能小于 1)' WHERE (`code`='beauty_android');

INSERT INTO `%DB_PREFIX%m_config` VALUES (null, 'sts_video_limit', '小视频时间定义(秒)', '应用设置', '60', '0', '0', null, null, '小视频允许上传的最大秒数');

UPDATE `%DB_PREFIX%conf` SET `value`='2.5' WHERE (`name`='DB_VERSION');