<?php
/**
 *支付成功后通知
 * @name notify.php
 * @package cwms
 * @category controller
 * @link /controller/notify.php
 * @author leon
 * @version 1.0
 * @since 2016-7-25
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
			$orderNum = $postObj->out_trade_no;
			$orderInfo = $db->find("SELECT * FROM donate_record WHERE order_num =  '{$orderNum}'");
			if($orderInfo['transaction_id']){//订单已处理直接结束
				echo 'success';
				exit;
			}
			//更新订单信息
			$db->update("donate_record", $orderInfoData, "order_num = '{$orderNum}'");
			//修改项目信息
			$projectInfo = $db->find("SELECT * FROM donate_project WHERE id =  '{$orderInfo['pid']}'");
			$open_id = $postObj->openid;
			$userInfo = $db->find("SELECT * FROM base_user WHERE open_id =  '{$open_id}'");
			$projectValue = array(
			    'donate_sum' => $projectInfo['donate_sum'] + $postObj->total_fee/100,
			    'donate_quantity' => $projectInfo['donate_quantity'] +1
			);
			$db->update("donate_project", $projectValue, "id = {$orderInfo['pid']}");
			//更新个人募捐数据
			$userValue = array(
			    'donate_money' => $userInfo['donate_money'] + $postObj->total_fee/100,
			    'donate_quantity' => $userInfo['donate_quantity'] +1
			);
			$db->update("base_user", $userValue, "open_id =  '{$open_id}'");
			//订单支付成功通知
			$keyInfo = $db->find("SELECT * FROM donate_config WHERE name =  'order_notify_key'");//获取密钥
			$url = "http://{$_SERVER['HTTP_HOST']}/index.php?c=server&a=orderNotify&key={$keyInfo['item_value']}&oid={$orderInfo['id']}&module=donate";
			file_get_contents($url);
		}
}
echo 'success';