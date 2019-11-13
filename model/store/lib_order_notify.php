<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';

/**
 * 提供订单通知服务
 * @name lib_order_notify.php
 * @package cwms
 * @category model
 * @link /model/lib_order_notify.php
 * @author jianfang
 * @version 1.0
 * @since 2015-11-19
 */
class lib_order_notify extends base_model {
	/**
	 * 邮件通知
	 * @param array $orderInfoResult
	 * @param array $configInfoResult
	 */
	public function mailNotify($orderInfoResult,$configInfoResult){
		if($configInfoResult['data']['order_pay_mail_notify']){//邮件通知
			include_once 'include/UtilMail.php';
			$UtilMail = new UtilMail();
			$content['url'] = $configInfoResult['data']['order_template_url'] . '&oid=' . $orderInfoResult['data']['id'];
			$content['nick_name'] = $orderInfoResult['data']['nick_name'];
			$content['add_time'] = $orderInfoResult['data']['add_time'];
			$content['order_num'] = $orderInfoResult['data']['order_num'];
			$result = $UtilMail->orderPayNotify($configInfoResult['data']['notify_mail'], "订单支付通知", $content);
			if(!$result){
				$log = Log::getInstance();
				$log->log("订单通知邮件发送失败",'ERROR');
			}
		}
	}
	
	/**
	 * 短信通知
	 * @param array $orderInfoResult
	 * @param array $configInfoResult
	 */
	public function smsNotify($orderInfoResult,$configInfoResult){
		if($configInfoResult['data']['order_sms_notify']){//短信通知
			include_once 'model/lib_sms.php';
			$sms = new lib_sms();
			$smsResult = $sms->findSMS(array('type' => 1));//订单支付通知
			if($smsResult['data']['quantity'] > 0){//判断短信余额是否大于1
				$sms->minusSMS($smsResult['data']['quantity']);//减去1条
				$data = array($orderInfoResult['data']['nick_name'],$orderInfoResult['data']['add_time'],$orderInfoResult['data']['order_num']);
				$tempId = $smsResult['data']['template_id'];
				$result = $sms->sendTemplateSMS($configInfoResult['data']['order_notify_phone'],$data,$tempId);//发送短信
				$recordResult = $sms->addRecord(array(
						"type"=>1,
						"send_phone"=>$configInfoResult['data']['order_notify_phone'],
						"content"=>"您好，客户{$orderInfoResult['data']['nick_name']}已于{$orderInfoResult['data']['add_time']}提交订单，编号{$orderInfoResult['data']['order_num']}，请您及时处理该订单。"
				));//加入记录
				if($result['errorCode'] != 0){
					$log = Log::getInstance();
					$log->log($result['errorInfo'],'ERROR');
				}
			}else{
				$log = Log::getInstance();
				$log->log("服务商短信余额不足",'ERROR');
			}
		}
	}
	
	/**
	 * 小票打印机通知
	 * @param array $orderInfoResult
	 * @param array $configInfoResult
	 */
	public function ticketNotify($orderInfoResult,$configInfoResult){
		if($configInfoResult['data']['order_ticket_notify']){//小票打印机通知
			include_once 'include/PrintTicket.php';
			$printTicket = new PrintTicket();
			//$addressInfo = $orderInfoResult['data']['address'];
			//$addressString = "{$addressInfo->address->province}" . "{$addressInfo->address->city}" . "{$addressInfo->address->area}" ."{$addressInfo->address->detail}";
			//$addressString =$addressInfo->address;
			$mesageInfo = array(
					'ticket_title'=>$configInfoResult['data']['ticket_title'],
					'num'=>time(),
					'order_num'=>$orderInfoResult['data']['order_num'],
					'time'=>$orderInfoResult['data']['add_time'],
					'nick_name'=>$orderInfoResult['data']['nick_name'],
					//'address'=>$addressString,
					'phone'=>$orderInfoResult['data']['phone'],
					//'message'=>$orderInfoResult['data']['message'],
					'total_price'=>$orderInfoResult['data']['total_price'],
					'goodsList'=>$orderInfoResult['data']['goods_list'],
					//'qrcode'=>$orderInfoResult['data']['qrcode'],
					'ticket_address'=>$configInfoResult['data']['ticket_address'],
					'ticket_phone'=>$configInfoResult['data']['ticket_phone'],
					'ticket_ad'=>$configInfoResult['data']['ticket_ad'],
					'ticket_qcode'=>$configInfoResult['data']['ticket_qcode'],
					'ticket_is_double'=>$configInfoResult['data']['ticket_is_double']
			);
//			ChromePhp::INFO($mesageInfo);
//			die;
			if($orderInfoResult['data']['pay_method'] == 1){
				$mesageInfo['pay_method'] ="微信支付";
			}else if($orderInfoResult['data']['pay_method'] == 2){
				$mesageInfo['pay_method'] ="支付宝支付";
			}else if($orderInfoResult['data']['pay_method'] == 3){
				$mesageInfo['pay_method'] ="货到付款";
			}
			$deviceInfo['ticket_device_num'] = $configInfoResult['data']['ticket_device_num'];
			$deviceInfo['ticket_device_key'] = $configInfoResult['data']['ticket_device_key'];
			
			//$result = $printTicket->sendPrint($mesageInfo,$deviceInfo);//老版小票打印机通知
			$result = $printTicket->sendNewPrint($mesageInfo,$deviceInfo);//新版小票打印机通知
			if($result['errorCode'] != 0){
				$log = Log::getInstance();
				$log->log($result['errorInfo'],'ERROR');
			}
		}
	}
	
	/**
	 * 模板消息通知
	 * @param array $orderInfoResult
	 * @param array $configInfoResult
	 */
	public function templateMessageNotify($orderInfoResult,$configInfoResult){
		if(!$configInfoResult['data']['order_template_notify']) return;//开启模板消息通知
		$goodsList = $orderInfoResult['data']['goods_list'];
		$goodsString = "";
		foreach ($goodsList as $goods){
			$goodsString .=   $goods->goods_name . ",";
		}
		$goodsString = rtrim($goodsString,',');
		
		//通知用户
		require_once 'include/wechatUtil/TemplateMessage.php';
		$rawTemplateIdList = file_get_contents('include/wechatUtil/data/template_id.json');
		$templateIdList = json_decode($rawTemplateIdList,true);
		if(!$templateIdList['user_order_id']){//订单支付成功通知
			$templateIdShort = "OPENTM202199113";//订单支付成功通知
			$result = TemplateMessage::getTemplateId($templateIdShort);
			if($result['errcode'] == 0){
				$templateId = $result['template_id'];
				$templateIdList['user_order_id'] = $templateId;
				$f = fopen('include/wechatUtil/data/template_id.json', 'w+');
				fwrite($f, json_encode($templateIdList));
				fclose($f);
			}
		}else{
			$templateId = $templateIdList['user_order_id'];
		}
		$url = $configInfoResult['data']['order_template_url'] . '&oid=' . $orderInfoResult['data']['id'];
		$lib_user = spClass("lib_user");
		$userResult = $lib_user->findUser(array('account'=>$orderInfoResult['data']['account']));
		$touser = $userResult['data']['open_id'];
		$date = date('Y-m-d H:i:s');
		$data = array(
				'first'=>array('value'=>'您的订单支付成功。', 'color'=>'#32CD32'),
				'keyword1'=>array('value'=>$orderInfoResult['data']['order_num'],'color'=>'#173177'),//订单编号
				'keyword2'=>array('value'=>$goodsString, 'color'=>'#173177'),//订单商品
				'keyword3'=>array('value'=>$orderInfoResult['data']['total_price'] . "元", 'color'=>'#173177'),//支付金额
				'keyword4'=>array('value'=>$date, 'color'=>'#173177'),//支付时间
				'remark'=>array('value'=>$configInfoResult['data']['order_template_remark'], 'color'=>'#808080')//备注
		);
		$result = TemplateMessage::sendTemplateMessage($data, $touser, $templateId, $url);
		//通知管理员
		$url = $configInfoResult['data']['order_template_url'] . '&oid=' . $orderInfoResult['data']['id'];
		$touser = $configInfoResult['data']['order_template_admin'];
		$data = array(
				'first'=>array('value'=>"您的用户'{$orderInfoResult['data']['nick_name']}'订单支付成功。", 'color'=>'#32CD32'),
				'keyword1'=>array('value'=>$orderInfoResult['data']['order_num'],'color'=>'#173177'),//订单编号
				'keyword2'=>array('value'=>$goodsString, 'color'=>'#173177'),//订单商品
				'keyword3'=>array('value'=>$orderInfoResult['data']['total_price'] . "元", 'color'=>'#173177'),//支付金额
				'keyword4'=>array('value'=>$date, 'color'=>'#173177'),//支付时间
				'remark'=>array('value'=>'还等什么？赶快去接单吧！', 'color'=>'#808080')//备注
		);
		$result = TemplateMessage::sendMutileTemplateMessage($data, $touser, $templateId, $url);
	}
	
}