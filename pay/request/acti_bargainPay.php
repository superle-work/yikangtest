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
$configInfo = $db->find("SELECT * FROM base_config WHERE item_key = 'bargain_theme'");

$curTemplate = $configInfo['item_value'];
$smarty->assign('theme', $curTemplate);
$authInfo =  WeChatOAuth::getAccessTokenAndOpenId($_GET['code']);
//获取订单对象
$orderInfo = $db->find("SELECT * FROM acti_bargain_order WHERE id = {$_GET['state']}");
//获取用户对象
$userInfo = $db->find("SELECT * FROM base_user WHERE id = {$orderInfo['user_id']}");
//获取商品对象
$goodsInfo = $db->find("SELECT * FROM acti_bargain_goods WHERE id = {$orderInfo['goods_id']}");
$url = explode('./', $goodsInfo['img_url']);
$goodsInfo['img_url'] = WECHAT_URL . $url[1];
//解析商品属性
$orderInfo['goods_list'] = json_decode($orderInfo['goods_list']);
// foreach ($orderInfo['goods_list'] as &$goods ){
// 	$goods->property = json_decode($goods->property );
// 	$url = explode('./', $goods->thumb );
// 	$goods->thumb =  WECHAT_URL . $url[1];
// }
$smarty->assign('addrees_text', $orderInfo['addrees_text']);
//微信支付标题
$wechatPayTitleSql = "SELECT * FROM acti_bargain_config WHERE item_key = 'wechat_pay_title'";
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
$orderArgsObj->SetOpenid($authInfo['openid']);
$orderArgsObj->SetTime_start(date("YmdHis"));
$orderArgsObj->SetTime_expire(date("YmdHis", time() + 30000));
//异步通知url未设置，则使用配置文件中的url
$orderArgsObj->SetNotify_url(WxPayConfig::ACTI_BARGAIN_NOTIFY_URL);//异步通知url

$payResult = WxPayApi::unifiedOrder($orderArgsObj);

include_once $_SERVER['DOCUMENT_ROOT']."/pay/notify/lib/WxPay.JsApiPay.php";
$pay = new JsApiPay();
try{
	$jsApiParameters = $pay->GetJsApiParameters($payResult);
}catch (Exception $ex){//发生异常重定向
	header("location: {$configInfo['wechat_url']}/index.php?c=acti_bargain&a=index");
}
$smarty->assign('jsApiParameters', $jsApiParameters);

$smarty->assign('orderInfo', $orderInfo);
$smarty->assign('userInfo', $userInfo);
$smarty->assign('goodsInfo', $goodsInfo);
//商城支付类型
$wechatPayTypeSql = "SELECT * FROM bargain_config WHERE item_key = 'pay_type'";
$wechatPayType = $db->find($wechatPayTypeSql);
$smarty->assign('payType', $wechatPayType['item_value']);
$smarty->display($_SERVER['DOCUMENT_ROOT']."/template/front/activity/bargain/{$curTemplate}/page/order/paymentOrder.html");

