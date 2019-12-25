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

$configInfo = $db->find("SELECT * FROM base_config WHERE item_key = 'store_theme'");
// var_dump($configInfo);
$curTemplate = $configInfo['item_value'];

$smarty->assign('theme', $curTemplate);





//$authInfo =  WeChatOAuth::getAccessTokenAndOpenId($_GET['code']);



/*

//获取订单对象

$orderInfo = $db->find("SELECT * FROM base_balance WHERE id = {$_GET['state']}");

$module = $orderInfo['module'];

//微信支付标题

$wechatPayTitleSql = "SELECT * FROM {$module}_config WHERE item_key = 'wechat_pay_title'";

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

$orderArgsObj->SetTotal_fee($orderInfo['money'] * 100);//单位是分

$orderArgsObj->SetTrade_type('JSAPI');

$orderArgsObj->SetOpenid($authInfo['openid']);

$orderArgsObj->SetTime_start(date("YmdHis"));

$orderArgsObj->SetTime_expire(date("YmdHis", time() + 30000));

//异步通知url未设置，则使用配置文件中的url

$orderArgsObj->SetNotify_url(WxPayConfig::BALANCE_NOTIFY_URL);//异步通知url

$payResult = WxPayApi::unifiedOrder($orderArgsObj);

include_once $_SERVER['DOCUMENT_ROOT']."/pay/notify/lib/WxPay.JsApiPay.php";

$pay = new JsApiPay();

try{

	$jsApiParameters = $pay->GetJsApiParameters($payResult);

}catch (Exception $ex){//发生异常重定向

    

	header("location: {$configInfo['wechat_url']}/index.php?c={$module}&a=index");

}

$smarty->assign('jsApiParameters', $jsApiParameters);

//用户优惠券信息

// if($orderInfo['user_coupon_id']){

// 	$userCouponSql = "SELECT * FROM market_coupon_user WHERE id = {$orderInfo['user_coupon_id']}";

// 	$couponResult = $db->find($userCouponSql);

// 	$orderInfo['coupon'] = $couponResult;

// }



$smarty->assign('orderInfo', $orderInfo);

//余额支付类型

// $wechatPayTypeSql = "SELECT * FROM duo_config WHERE item_key = 'pay_type'";

// $wechatPayType = $db->find($wechatPayTypeSql);

 * 

 */

// var_dump($_GET);

if($_GET['oid']){

	//获取订单信息

	$oid=$_GET['oid'];

	$orderInfo=$db->find("select * from store_order where id={$oid}");
	// var_dump($orderInfo);
	$orderInfo['goods_info']=json_decode($orderInfo['goods_list'],true);

	foreach($orderInfo['goods_info'] as &$val){

		$goodInfo=$db->find("select * from store_goods where id={$val['gid']}");

		$val['sale_quantity']=$goodInfo['sale_quantity'];

	}

	

	//获取体检人员信息

	$memberInfo=$db->find("select * from store_check_member where id={$orderInfo['checkMember_id']}");

	

	$smarty->assign('orderInfo', $orderInfo);

	$smarty->assign('memberInfo', $memberInfo);

	$smarty->display($_SERVER['DOCUMENT_ROOT']."/template/front/store/{$curTemplate}/page/order/payOrderDetail.html");

} 

else{

	//购物车id

	$cgids=$_GET['cgids'];

	//商品id

	$gids=$_GET['gids'];

	//数量

	$counts=$_GET['counts'];

	//购买用户id

	$uid=$_GET['uid'];
	// 用户信息
	$member=$db->find("select * from base_user where id={$uid}");
	// 用户折扣
	$membertype=$db->find("select * from store_discount where user_type={$member['user_type']}");
	
	$goodsInfo=$db->findAll("select * from store_goods where id in ($gids)");

	$goodsCountList = explode(',',$counts);

	//商品信息和总价		

	$sum = 0;

	//$counts = 0;

	for($i = 0;$i < count($goodsCountList);$i++){

	    $goodsInfo[$i]['goods_count'] = $goodsCountList[$i];

	    $sum += $goodsInfo[$i]['price'] * $goodsCountList[$i];

	}
	// 乘以折扣百分百比
	// 百分百修改成%
	if($membertype['discount']!='1.00'&&$membertype['discount']!='0.00'){
		
		$sum=bcmul($sum,$membertype['discount'],2);
		
		$membertype['discount']=bcmul(100,$membertype['discount'],0).'%';
		
	}
	// 加上其他费用
	$sum=$membertype['blood_fee']+$membertype['transport_fee']+$sum;
	
	//体检人员信息

	$memberInfo=$db->find("select * from store_check_member where state=1 and uid={$uid}");
	
	$smarty->assign('membertype',$membertype);

	$smarty->assign('goodsList', $goodsInfo);

	$smarty->assign('memberInfo', $memberInfo);

	$smarty->assign('totalPrice', $sum);

	$smarty->assign('cgids', $cgids);

	$smarty->assign('gids', $gids);

	$smarty->assign('counts', $counts);

	$smarty->assign('uid', $uid);

	$smarty->display($_SERVER['DOCUMENT_ROOT']."/template/front/store/{$curTemplate}/page/order/payOrder.html");	

}