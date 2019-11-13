<?php
/**
 *支付成功后通知
 * @name PayNotifyCallBack.php
 * @package cwms
 * @category wechat/pay
 * @link /controller/PayNotifyCallBack.php
 * @author jianfang
 * @version 1.0
 * @created 2015-7-25
 */
require_once "include/pay/wechat/lib/WxPay.Api.php";
require_once 'include/pay/wechat/lib/WxPay.Notify.php';
require_once 'include/log.php';

//初始化日志
$logHandler= new CLogFileHandler("/logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
				&& array_key_exists("result_code", $result)
				&& $result["return_code"] == "SUCCESS"
				&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}

	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();

		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}