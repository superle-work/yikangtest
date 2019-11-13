<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/wechatConfig.php";//导入微信配置

include_once $_SERVER['DOCUMENT_ROOT'].'/SpeedPHP/Drivers/Smarty/Smarty.class.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/db.php';

include_once $_SERVER['DOCUMENT_ROOT']."/include/wechatUtil/Curl.php";
include_once $_SERVER['DOCUMENT_ROOT']."/include/wechatUtil/Msg.php";
include_once $_SERVER['DOCUMENT_ROOT']."/include/wechatUtil/MsgConstant.php";
include_once $_SERVER['DOCUMENT_ROOT']."/include/wechatUtil/AccessToken.php";
include_once $_SERVER['DOCUMENT_ROOT']."/include/wechatUtil/WeChatOAuth.php";

error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );

$smarty = new Smarty();
$smarty->left_delimiter = "<!--{";
$smarty->right_delimiter = "}-->";
define('TEMPLATE_PATH',"../../template");
define('THEMES_PATH',"../../themes");
define('JS_PATH',"../../js");

$db = new db();
$configInfo = $db->find("SELECT * FROM base_config WHERE item_key = 'trip_theme'");
$curTemplate = $configInfo['item_value'];
$smarty->assign('theme', $curTemplate);
$authInfo =  WeChatOAuth::getAccessTokenAndOpenId($_GET['code']);
//订单用户对象
$orderInfo = $db->find("SELECT * FROM trip_order WHERE id = {$_GET['state']}");
//获取用户对象
$userInfo = $db->find("SELECT * FROM base_user WHERE account = {$orderInfo['account']}");
//设置支付方式-微信支付
//$db->update("cg_mall_order", array('pay_method'=>1), "id = {$_GET['state']}");
$orderInfo['goods_list'] = json_decode($orderInfo['goods_list']);
$url = explode('./', $orderInfo['goods_list']->thumb );
$orderInfo['goods_list']->thumb =  WECHAT_URL . $url[1];
session_start();
$orderInfo['joiner_info'] = json_decode($orderInfo['joiner_info']);
//微信支付标题
$wechatPayTitleSql = "SELECT * FROM trip_config WHERE item_key = 'wechat_pay_title'";
$wechatPayTitle = $db->find($wechatPayTitleSql);

//微信支付接口
include_once $_SERVER['DOCUMENT_ROOT']."/pay/notify/lib/WxPay.Api.php";
$orderArgsObj = new WxPayUnifiedOrder();
//设置必填参数
if($orderInfo['price_times']){
    $orderInfo['order_num'] = $orderInfo['order_num'] . "_" . $orderInfo['price_times'];
}
//设置必填参数
$orderArgsObj->SetOut_trade_no( $orderInfo['order_num']);
$orderArgsObj->SetBody($wechatPayTitle['item_value']);
$orderArgsObj->SetTotal_fee($orderInfo['total_price'] * 100);//单位是分
$orderArgsObj->SetTrade_type('JSAPI');
$orderArgsObj->SetOpenid($authInfo['openid']);
 
$orderArgsObj->SetTime_start(date("YmdHis"));
$orderArgsObj->SetTime_expire(date("YmdHis", time() + 30000));
//异步通知url未设置，则使用配置文件中的url
$orderArgsObj->SetNotify_url(WxPayConfig::TRIP_NOTIFY_URL);//异步通知url
 
$payResult = WxPayApi::unifiedOrder($orderArgsObj);

include_once $_SERVER['DOCUMENT_ROOT']."/pay/notify/lib/WxPay.JsApiPay.php";
$pay = new JsApiPay();
//$jsApiParameters = $pay->GetJsApiParameters($payResult);
try{
	$jsApiParameters = $pay->GetJsApiParameters($payResult);
}catch (Exception $ex){//发生异常重定向
	header("location: {$configInfo['wechat_url']}/index.php?c=trip&a=index");
}
$smarty->assign('jsApiParameters', $jsApiParameters);

$smarty->assign('orderInfo', $orderInfo);

$smarty->display($_SERVER['DOCUMENT_ROOT']."/template/front/trip/{$curTemplate}/page/order/paymentOrder.html");
