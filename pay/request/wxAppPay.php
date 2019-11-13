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

$db = new db();
//$authInfo =  WeChatOAuth::getAccessTokenAndOpenId($_GET['code']);
//获取订单对象
$orderInfo = $db->find("SELECT * FROM store_order WHERE id = {$_GET['oid']}");
//获取用户对象
//$userInfo = $db->find("SELECT * FROM base_user WHERE account = {$orderInfo['account']}");
//解析商品属性
$orderInfo['goods_list'] = json_decode($orderInfo['goods_list']);
foreach ($orderInfo['goods_list'] as &$goods ){
	$goods->property = json_decode($goods->property );
	$url = explode('./', $goods->thumb );
	$goods->thumb =  WECHAT_URL . $url[1];
}
$myData['addrees_text'] = $orderInfo['addrees_text'];
//微信支付标题
$wechatPayTitleSql = "SELECT * FROM store_config WHERE item_key = 'wechat_pay_title'";
$wechatPayTitle = $db->find($wechatPayTitleSql);
//微信支付接口
include $_SERVER['DOCUMENT_ROOT']."/pay/notify/lib/WxPay.Api.php";
$orderArgsObj = new WxPayUnifiedOrder();
//设置必填参数
if($orderInfo['price_times']){
    $orderInfo['order_num'] = $orderInfo['order_num'] . "_" . $orderInfo['price_times'];
}
$orderArgsObj->SetOut_trade_no( $orderInfo['order_num']);
$orderArgsObj->SetBody($wechatPayTitle['item_value']);
$orderArgsObj->SetTotal_fee($orderInfo['total_price'] * 100);//单位是分
$orderArgsObj->SetTrade_type('JSAPI');
$orderArgsObj->SetOpenid($orderInfo['account']);
$orderArgsObj->SetTime_start(date("YmdHis"));
$orderArgsObj->SetTime_expire(date("YmdHis", time() + 30000));
//是否开启微信小程序
//$orderArgsObj->SetWXapp_state(IS_WXAPP);
$wxappState = IS_WXAPP;
//异步通知url未设置，则使用配置文件中的url
$orderArgsObj->SetNotify_url(WxPayConfig::STORE_NOTIFY_URL);//异步通知url
$payResult = WxPayApi::unifiedOrder($orderArgsObj,'',$wxappState);
include_once $_SERVER['DOCUMENT_ROOT']."/pay/notify/lib/WxPay.JsApiPay.php";
$pay = new JsApiPay();
try{
	$jsApiParameters = $pay->GetJsApiParameters($payResult);
}catch (Exception $ex){//发生异常重定向
// 	header("location: {$configInfo['wechat_url']}/index.php?c=store&a=index");
}
echo $jsApiParameters;
