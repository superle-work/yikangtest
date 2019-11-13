<?php
//pf web 路径配置
define("APP_PATH",dirname(__FILE__));
define("SP_PATH",dirname(__FILE__).'/SpeedPHP');
define("BASE_PATH",'.');
define("TEMPLATE_PATH",BASE_PATH."/template");//模板基路径
define("THEMES_PATH",BASE_PATH."/themes");//主题基路径
define("JS_PATH",BASE_PATH."/js");//js基路径
define("ROOT_URL","http://yikang.chuyuanshengtai.com");//服务器根目录
define("pageTitle", "微商城");//微商城页头标题
define("pointsPageTitle", "积分商城");//积分商城页头标题
//客服头像
define("SERVICE_HEAD_IMG_qq_1",BASE_PATH."/themes/image/service_head_img/qq_1.png");//客服头像1
define("SERVICE_HEAD_IMG_qq_2",BASE_PATH."/themes/image/service_head_img/qq_2.png");//客服头像2
define("SERVICE_HEAD_IMG_we_1",BASE_PATH."/themes/image/service_head_img/we_1.png");//客服头像3
define("SERVICE_HEAD_IMG_we_2",BASE_PATH."/themes/image/service_head_img/we_2.png");//客服头像4
define("SERVICE_HEAD_IMG_tel_1",BASE_PATH."/themes/image/service_head_img/tel_1.png");//客服头像3
define("SERVICE_HEAD_IMG_tel_2",BASE_PATH."/themes/image/service_head_img/tel_2.png");//客服头像4

include_once 'db.php';//导入数据库配置
include_once 'wechatConfig.php';//导入微信配置

define("CNAME","易享健信息技术有限公司+多商家优惠券");//客户项目名


