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
			include_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';
			$db = new db();
			$orderInfo = $db->find("SELECT * FROM group_order WHERE order_num =  '{$postObj->out_trade_no}'");
			if($orderInfo['transaction_id']){//订单已处理直接结束
				echo 'success';
				exit;
			}
			include_once $_SERVER['DOCUMENT_ROOT'] . '/pay/notify/lib/WxPay.BankCard.php';
			$orderInfoData = array(
// 			    'state' => 1,//设置待发货
			    'transaction_id' => $postObj->transaction_id,
// 			    'pay_card' => BankCard::GetBankCard($postObj->bank_type),
			    'pay_method'=>1
			);
			if($orderInfo['type'] == 1){//开团
			    //加入团
			    $addId = $db->insert('group_groups',array('goods_id' => $orderInfo['goods_id'],
			                                      'ga_id' => $orderInfo['ga_id'],
			                                      'person_count' => 1,
			                                      'status' => 0,
			                                      'nick_name' => $orderInfo['nick_name'],
			                                      'head_img_url' => $orderInfo['head_img_url'],
			                                      'add_time' => date('Y-m-d H:i:s',time())
			    ));
			    //加入组团记录
			    $db->insert('group_record',array('group_id' => $addId,
			                                             'user_id' => $orderInfo['user_id'],
                                    			         'caption' => 1,
                                    			         'nick_name' => $orderInfo['nick_name'],
                                    			         'head_img_url' => $orderInfo['head_img_url'],
                                    			         'add_time' => date('Y-m-d H:i:s',time())
			    ));
			    $orderInfoData['group_id'] = $addId;//同步更新group_id 字段
			    $orderInfoData['state'] = 6;//组团中
			}else if($orderInfo['type'] == 2){//组团
			    //加入组团记录
			    $db->insert('group_record',array('group_id' => $orderInfo['group_id'],
                                    			         'user_id' => $orderInfo['user_id'],
                                    			         'caption' => 0,
                                    			         'nick_name' => $orderInfo['nick_name'],
                                    			         'head_img_url' => $orderInfo['head_img_url'],
                                    			         'add_time' => date('Y-m-d H:i:s',time())
                                    			    ));
			    //修改组团记录参与人数
			    $groupInfo = $db->find("select a.*,b.group_count-a.person_count as lack_num,b.end_time from (select * from group_groups where id = {$orderInfo['group_id']})as a left Join group_goods_activity as b on a.ga_id = b.id");
			    if($groupInfo['lack_num'] == 1){//购买者为团中最后一个人
			        $updateGroup = array('person_count' => $groupInfo['person_count'] + 1,'status' => 1);
			        $orderInfoData['state'] = 7;//已成团
			        //更新订单信息,同组订单状态置为7已成团
			        $db->update("group_order", array('state' => 7), "group_id = {$orderInfo['group_id']}");
			    }else{//购买者不为团中最后一个人
			        $updateGroup = array('person_count' => $groupInfo['person_count'] + 1);
			        $orderInfoData['state'] = 6;//组团中
			    }
			    $db->update('group_groups',$updateGroup," id= '{$orderInfo['group_id']}'");
			}else{//单独买
			    $orderInfoData['state'] = 1;//已支付、单人
			}
			//更新订单信息
			$db->update("group_order", $orderInfoData, "order_num = '{$postObj->out_trade_no}'");
			//更新商品信息
			//商品库存
			$db->execute("update group_goods set inventory = inventory - {$orderInfo['goods_count']} where id = {$orderInfo['goods_id']}");
			//所有活动的库存
			$db->execute("update group_goods_activity set inventory = inventory - {$orderInfo['goods_count']} where id = {$orderInfo['ga_id']}");
			//更新用户信息   移动至交易完成部分
			$userInfo = $db->find("SELECT * FROM base_user WHERE account =  '{$orderInfo['account']}'");
			$userInfoData = array(
						'total_fee' =>$postObj->total_fee / 100 + $userInfo['total_fee']
					);
			//查看是否开启积分
			$openPoint = $db->find("SELECT * FROM group_config WHERE item_key = 'group_mall_point'");
			if($openPoint['item_value'] == '1'){
			    $exchange = $db->find("SELECT * FROM group_config WHERE item_key = 'point_exchange_rate'");
			    $userInfoData['points'] = $postObj->total_fee * $exchange['item_value'] + $userInfo['points'];
			}
			$db->update("base_user", $userInfoData, "account = '{$orderInfo['account']}'");
			//订单支付成功通知
			$keyInfo = $db->find("SELECT * FROM group_config WHERE item_key =  'order_notify_key'");//获取密钥
			$url = "http://{$_SERVER['HTTP_HOST']}/index.php?c=server&a=orderNotify&key={$keyInfo['item_value']}&oid={$orderInfo['id']}&module=group";
			file_get_contents($url);
		}
}
echo 'success';