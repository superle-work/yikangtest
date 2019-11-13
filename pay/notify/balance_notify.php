<?php
/**
 *支付成功后通知
 * @name balance_notify.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author zyd
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-11-11
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
            'status' => 1,//设置为交易成功
//             'transaction_id' => $postObj->transaction_id,
//             'pay_card' => BankCard::GetBankCard($postObj->bank_type),
//             'pay_method'=> 1,
//             'end_time' => date("Y-m-d H:i:s",time())
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
        $orderInfo = $db->find("SELECT * FROM base_balance WHERE order_num =  '{$orderNum}'");
        if($orderInfo['transaction_id']){//订单已处理直接结束
            echo 'success';
            exit;
        }
        //更新订单信息
        $db->update("base_balance", $orderInfoData, "order_num = '{$orderNum}'");
        $table = $orderInfo['module'].'config';
        
        //增加用户余额
        $db->execute("update base_user set balance = balance + {$orderInfo['get_real_coin']} where id={$orderInfo['user_id']}");
        
        //订单支付成功通知
        $key = $db->find("SELECT * FROM {$table} WHERE item_key =  'order_notify_key'");//获取密钥
        $keyInfo = $orderInfo['module'].'|'.$key['item_value'];
        $url = "http://{$_SERVER['HTTP_HOST']}/index.php?c=server&a=orderNotify&key={$keyInfo}&oid={$orderInfo['id']}&module=balance";
        file_get_contents($url);
    }
}
echo 'success';
