<?php
/**
 *支付成功后通知
 * @name store_notify.php
 * @package cwms
 * @category controller
 * @link /controller/notify.php
 * @author jianfang
 * @version 1.0
 * @since 2015-7-25
 */
/*
 appid:wx63a6e52d04f9ed63
bank_type:CMB_CREDIT 付款银行
cash_fee:1
fee_type:CNY 
is_subscribe :Y 是否关注公众账号
mch_id:1247415201
nonce_str:bv4kv9du5y12olx9w3nff0yjvqfgpic0
openid:ob2eYuNyzq-AgR-vi3llKvIW2h1o
out_trade_no:hhffi8666
result_code:SUCCESS
return_code:SUCCESS
sign:14F44FEF0835B4257FBAC7D807AD06AE
time_end:20150726152935
total_fee:1
trade_type:JSAPI
transaction_id:1007880445201507260482512729
 */
$postStr = file_get_contents('php://input');
$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
if( $postObj->return_code == "SUCCESS"){
		if($postObj->result_code == "SUCCESS"){ 			
			include_once $_SERVER['DOCUMENT_ROOT'] . '/pay/notify/lib/WxPay.BankCard.php';
			$orderInfoData = array(
					'state' => 1,//设置 待发货
					'transaction_id' => $postObj->transaction_id,
					'pay_method'=>1
			);
			include_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';
			$db = new db();
			$orderNum = $postObj->out_trade_no;
			$orderNumList = explode('_', $orderNum);
			if($orderNumList[1]){//该订单修改过,去处传入的修改次数
			  $orderNum = $orderNumList[0];
			}else{
			  $orderNum = $postObj->out_trade_no;
			}

			$orderInfo = $db->find("SELECT * FROM market_coupon_user_order WHERE order_num =  '{$orderNum}'");
			if($orderInfo['transaction_id']){//订单已处理直接结束
				echo 'success';
				exit;
			}
			//更新订单信息
			$db->update("market_coupon_user_order", $orderInfoData, "order_num = '{$orderNum}'");
			//更新商品信息
			$goodsProperty = $db->find("SELECT * FROM market_coupon WHERE id =  {$orderInfo['coupon_id']}");
			//获取新库存
			$get_quantity = $goodsProperty['get_quantity'] + $orderInfo['coupon_num'];
			$spare_quantity = $goodsProperty['spare_quantity'] - $orderInfo['coupon_num'];
			$goodsPropertyInfo = array(
					'get_quantity' => $get_quantity,'spare_quantity' => $spare_quantity
			);
			//修改库存
			$db->update("market_coupon", $goodsPropertyInfo, "id = {$orderInfo['coupon_id']}");
			
			//检查用户是否已有该优惠券
			$result = $db->find("SELECT * FROM market_coupon_user WHERE user_id = {$orderInfo['user_id']} AND coupon_id = {$orderInfo['coupon_id']}");
			if($result > 0){
			    $db->update('market_coupon_user', array('inventory'=>$result['inventory'] + $orderInfo['coupon_num']), "user_id = {$orderInfo['user_id']}");
			}else{
    			$data = date('Y-m-d H:i:s', time());
    			$userCoupon = array(
    			    'user_id' => $orderInfo['user_id'],
    			    'nick_name' => $orderInfo['nick_name'],
    			    'coupon_num' => "NO" . date("YmdHis") . rand(0,99),
    			    'type' => $goodsProperty['type'],
    			    'coupon_id' => $orderInfo['coupon_id'],
    			    'color' => $goodsProperty['color'],
    			    'is_use' => 0,
    			    'add_time' => $data,
    			    'use_scene' => $goodsProperty['use_scene'],
    			    'name' => $orderInfo['coupon_name'],
    			    'value' => $orderInfo['value'],
    			    'condition_value' => $orderInfo['condition_value'],
    			    'description' => $orderInfo['description'],
    			    'discount' => $orderInfo['discount'],
    			    'valid_start' => $goodsProperty['valid_start'],
    			    'valid_end' => $goodsProperty['valid_end'],
    			    'inventory' => $orderInfo['coupon_num'],
    			);
    			//为用户添加优惠券
    			$db->insert('market_coupon_user', $userCoupon);
			}
			
// 			//更新用户信息   移动至交易完成部分
// 			$userInfo = $db->find("SELECT * FROM base_user WHERE account =  '{$orderInfo['account']}'");
// 			$userInfoData = array(
// 						'total_fee' =>$postObj->total_fee / 100 + $userInfo['total_fee']
// 					);
// 			//查看是否开启积分
// 			$openPoint = $db->find("SELECT * FROM store_config WHERE name = 'mall_point'");
// 			if($openPoint['value'] == '1'){
// 			    $exchange = $db->find("SELECT * FROM store_config WHERE name = 'point_exchange_rate'");
// 			    $userInfoData['points'] = $postObj->total_fee * $exchange['value'] + $userInfo['points'];
// 			}
// 			$db->update("base_user", $userInfoData, "account = '{$orderInfo['account']}'");
			//订单支付成功通知
			$keyInfo = $db->find("SELECT * FROM market_config WHERE name =  'coupon_notify_key'");//获取密钥
			$url = "http://{$_SERVER['HTTP_HOST']}/index.php?c=server&a=orderNotify&key={$keyInfo['item_value']}&oid={$orderInfo['id']}&module=coupon";
			file_get_contents($url);
		}
}
echo 'success';