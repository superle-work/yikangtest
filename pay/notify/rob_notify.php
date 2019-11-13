<?php
/**
 *支付成功后通知
 * @name group_notify.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-09-05
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
					'state' => 1,//设置待发货
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
			$orderInfo = $db->find("SELECT * FROM rob_order WHERE order_num =  '{$orderNum}'");
			if($orderInfo['transaction_id']){//订单已处理直接结束
				echo 'success';
				exit;
			}
			//更新订单信息
			$db->update("rob_order", $orderInfoData, "order_num = '{$orderNum}'");
			//更新商品信息
			$goodsInfo = json_decode($orderInfo['goods_info']);
			//更新库存
		    if($goodsInfo->standard == 1){//多规格
		        //获取商品属性信息
		        $goodsProperty = $db->find("SELECT * FROM rob_goods_property WHERE id =  {$goodsInfo->gpid}");
		        //获取新库存
		        $newInventory = $goodsProperty['inventory'] -  $goodsInfo->goods_count;
		        $propertyInfo = array(
		            'inventory' => $newInventory
		        );
		        //修改库存
		        $db->update("rob_goods_property", $propertyInfo, "id = {$goodsInfo->gpid}");
		    }else{//统一规格
		        //获取最新商品信息
		        $oriGoodsInfo = $db->find("SELECT * FROM rob_goods WHERE id =  {$goodsInfo->id}");
		        $info = array(
		            'inventory' => $oriGoodsInfo['inventory'] -  $goodsInfo->goods_count//减库存
		        );
		        $db->update("rob_goods", $goodsInfo, "id = {$goodsInfo->id}");
		    }
			$goods = $db->find("SELECT * FROM rob_goods WHERE id =  {$goodsInfo->id}");
			//更新销量
			$newSaleQuantity = $goods['sale_quantity'] + $goodsInfo->goods_count;
			$goodsRow = array(
			    'sale_quantity' => $newSaleQuantity
			);
			$db->update("rob_goods", $goodsRow, "id = {$goodsInfo->id}");
// 			//更新用户信息
// 			$userInfo = $db->find("SELECT * FROM cg_user WHERE id =  '{$orderInfo['user_id']}'");
// 			$userInfoData = array(
// 						'total_fee' =>$postObj->total_fee / 100 + $userInfo['total_fee']
// 					);
// 			//查看是否开启抢购积分
// 			$openPoint = $db->find("SELECT * FROM cg_mall_config WHERE name = 'rob_mall_point'");
// 			if($openPoint['value'] == '1'){
// 			    $exchange = $db->find("SELECT * FROM cg_mall_config WHERE name = 'point_exchange_rate'");
// 			    $userInfoData['points'] = $postObj->total_fee * $exchange['value'] + $userInfo['points'];
// 			}
// 			$db->update("cg_user", $userInfoData, "id = '{$orderInfo['user_id']}'");
			//订单支付成功通知
			$keyInfo = $db->find("SELECT * FROM rob_config WHERE item_key =  'order_notify_key'");//获取密钥
			$url = "http://{$_SERVER['HTTP_HOST']}/index.php?c=server&a=orderNotify&key={$keyInfo['item_value']}&oid={$orderInfo['id']}&module=rob";
			file_get_contents($url);
		}
}
echo 'success';