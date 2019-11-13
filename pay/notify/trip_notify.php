<?php
/**
 *支付成功后通知
 * @name notify.php
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
			include $_SERVER['DOCUMENT_ROOT'] . '/pay/notify/lib/WxPay.BankCard.php';
			
			$orderInfoData = array(
					'state' => 1,//设置已付款
					'transaction_id' => $postObj->transaction_id,
					'pay_card' => BankCard::GetBankCard($postObj->bank_type),
					'pay_method'=>1
			);
			include_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';
			$db = new db();
			$rawOrderNum = $postObj->out_trade_no;
			$orderNumList = explode('_', $rawOrderNum);
			if($orderNumList[1]){//该订单修改过,去处传入的修改次数
			    $orderNum = $orderNumList[0];
			}else{
			    $orderNum = $postObj->out_trade_no;
			}
			$orderInfo = $db->find("SELECT * FROM trip_order WHERE order_num =  '{$orderNum}'");
			if($orderInfo['transaction_id']){//订单已处理直接结束
				echo 'success';
				exit;
			}
			//更新订单信息
			$db->update("trip_order", $orderInfoData, "order_num = '{$orderNum}'");
			//更新商品信息
			$goodsList = json_decode($orderInfo['goods_list']);
			$oriGoodsInfo = $db->find("SELECT * FROM trip_goods_activity WHERE id =  {$orderInfo['gaid']}");
			$goodsInfo = array(
			    'inventory' => $oriGoodsInfo['inventory'] -  $goodsList->joiner_counts,//减库存 剩余名额
			    'joiner_amount' => $oriGoodsInfo['joiner_amount'] +  $goodsList->joiner_counts//已报名人数
			);
			$db->update("trip_goods_activity", $goodsInfo, "id = {$orderInfo['gaid']}");
			//更新用户信息
			$userInfo = $db->find("SELECT * FROM base_user WHERE account =  '{$orderInfo['account']}'");
			$userInfoData = array(
						//'points' =>$postObj->total_fee /100 + $userInfo['points'],
						'total_fee' =>$postObj->total_fee / 100 + $userInfo['total_fee']
					);
			$db->update("cg_user", $userInfoData, "account = '{$orderInfo['account']}'");
			//订单支付成功通知 与微商城公用秘钥
			$keyInfo = $db->find("SELECT * FROM trip_config WHERE item_key =  'order_notify_key'");//获取密钥
			$url = "http://{$_SERVER['HTTP_HOST']}/index.php?c=service&a=orderNotify&key={$keyInfo['item_value']}&oid={$orderInfo['id']}";
			file_get_contents($url);
		}
}
echo 'success';