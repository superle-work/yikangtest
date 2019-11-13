<?php
/**
 * 微信支付收货地址类
 * @author jianfang
 */
require_once "WxPay.Config.php";
class Address{
	/**
	 * 构造收货地址签名
	 * @param string $url 重定向的url，一定要带上code和state，否则签名一定错误！
	 * @param 用户授权 $access_token
	 * @return json
	 */
	public static function GetEditAddressParameters($url,$access_token){
		$data = array();
		$data["appid"] = WECHAT_APPID;
		$data["url"] = $url;
		$time = time();
		$data["timestamp"] = "$time";
		$data["noncestr"] = "1234568";
		$data["accesstoken"] = $access_token;
		ksort($data);
		$params = Address::ToUrlParams($data);
		$addrSign = sha1($params);
	
		$afterData = array(
				"addrSign" => $addrSign,
				"signType" => "sha1",
				"scope" => "jsapi_address",
				"appId" => WECHAT_APPID,
				"timeStamp" => $data["timestamp"],
				"nonceStr" => $data["noncestr"]
		);
		$parameters = json_encode($afterData);
		return $parameters;
	}
	
	/**
	 *
	 * 拼接签名字符串
	 * @param array $urlObj
	 *
	 * @return 返回已经拼接好的字符串
	 */
	private static function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			if($k != "sign"){
				$buff .= $k . "=" . $v . "&";
			}
		}
	
		$buff = trim($buff, "&");
		return $buff;
	}

}