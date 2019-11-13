<?php
error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
include_once 'db.php';
$db = new db();
$sql = "SELECT * FROM weixin_config WHERE item_group = 'wechat'";
$rawConfigInfo = $db->findAll($sql);
foreach($rawConfigInfo as  $perConfig){
	$configInfo[$perConfig['item_key']] = $perConfig['item_value'];
}
//微信配置
define('DEBUG', $configInfo['is_debug']);////1、调试模式 0、生产环境
define('WECHAT_TYPE', $configInfo['wechat_type']);//0服务号1订阅号 2企业号

define("WECHAT_URL", $configInfo['wechat_url']);//授权后重定向的回调链接地址，请使用urlencode对链接进行处理 后面加反斜杠/
define('WECHAT_ENCODING_AES_KEY', $configInfo['wechat_encoding_aes_key']);
define('WECHAT_TOKEN', $configInfo['wechat_token']);
define("WECHAT_NUMBER", $configInfo['wechat_number'] );//微信号

define('IS_WXAPP', $configInfo['is_wxapp']);//0 不开启小程序 1 开启小程序
$is_wxapp = $configInfo['is_wxapp'];//0 不开启小程序 1 开启小程序
define("WXAPP_APPID", $configInfo['wxapp_appid']);
define("WXAPP_APPSECRET", $configInfo['wxapp_appsecret'] );
define("WXAPP_MCHID", $configInfo['wxapp_mchid']);//商户号（必须配置，开户邮件中可查看）
define("WXAPP_PAY_KEY", $configInfo['wxapp_pay_key']);//商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）

define("WECHAT_APPID", $configInfo['wechat_appid']);
define("WECHAT_APPSECRET", $configInfo['wechat_appsecret'] );
define("WECHAT_MCHID", $configInfo['wechat_mchid']);//商户号（必须配置，开户邮件中可查看）
define("WECHAT_PAY_KEY", $configInfo['wechat_pay_key']);//商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）

define("WECHAT_ROB_NOTIFY_URL", $configInfo['wechat_rob_notify_url']);//微信抢购支付通知url
define("WECHAT_GROUP_NOTIFY_URL", $configInfo['wechat_group_notify_url']);//微信团购支付通知url
define("WECHAT_DONATE_NOTIFY_URL", $configInfo['wechat_donate_notify']);//微信募捐支付通知url
define("WECHAT_DUO_NOTIFY_URL", $configInfo['wechat_duo_notify_url']);//微信夺宝支付通知url
define("WECHAT_DUO_COIN_NOTIFY_URL", $configInfo['wechat_duo_coin_notify_url']);//微信夺宝币充值通知url
define("WECHAT_NOTIFY_URL", $configInfo['wechat_notify_url']);//微信支付通知url
define("WECHAT_STORE_NOTIFY_URL", $configInfo['wechat_store_notify_url']);//微商城支付通知url
define("WECHAT_HOTEL_NOTIFY_URL", $configInfo['wechat_hotel_notify_url']);//微酒店支付通知url
define("WECHAT_DINING_NOTIFY_URL", $configInfo['wechat_dining_notify_url']);//微订餐支付通知url
define("WECHAT_TRIP_NOTIFY_URL", $configInfo['wechat_trip_notify_url']);//微旅行支付通知url
define("WECHAT_DRIVE_NOTIFY_URL", $configInfo['wechat_drive_notify_url']);//微驾考支付通知url
define("WECHAT_COUPON_NOTIFY_URL", $configInfo['wechat_coupon_notify_url']);//微优惠券支付通知url
define('WECHAT_BARGAIN_NOTIFY_URL', $configInfo['wechat_bargain_notify_url']);//微信砍价商城支付通知url
define('WECHAT_ACTI_BARGAIN_NOTIFY_URL', $configInfo['wechat_acti_bargain_notify_url']);//微砍价活动支付通知url
define('WECHAT_BALANCE_NOTIFY_URL', $configInfo['wechat_balance_notify_url']);//余额支付通知url
define('WECHAT_PHOTOGRAPHY_NOTIFY_URL', $configInfo['wechat_photography_notify_url']);//微摄影支付通知url
define("WECHAT_REGISTER_NOTIFY_URL", $configInfo['wechat_register_notify_url']);//微报名支付通知url

