<?php
/**
 * 微信红包
 * @author jianfang
 * @copyright changekeji
 * @version 1.0
 * @since: 2016-03-11
 */
require_once 'pay/notify/lib/WxPay.Config.php';
class RedPacket{
	
	/**
	 * 请求支付红包
	 * @param string $url
	 * @param string $obj
	 * @param string appId 客户$appid
	 * @param string $payKey 微信支付密钥
	 * @return Ambigous <mixed, boolean>
	 */
    public  function pay($url,$obj,$appId,$payKey){
    	$obj['nonce_str'] = $this->createNoncetr();
    	$stringA = $this->formatQueryParaMap($obj, false);
    	$stringSignTemp = $stringA . "&key=" . $payKey;
    	$sign = strtoupper(md5($stringSignTemp));
    	$obj['sign'] = $sign;
    	require_once 'include/utils.php';
    	$utils = new utils();
    	$postXml = $utils->arrayToXml($obj);
    	$responseXml = $this->curlPostSsl($url, $postXml,$appId);
    	return $responseXml;
    }
    
    /**
     * 创建随机字符串
     * @param int $length
     * @return string
     */
    private function createNoncetr($length = 32){
    	 $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	   	 $str = "";
		  for ($i = 0; $i < $length; $i++) {
		      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		  }
		  return $str;
	  }
	  
	  /**
	   * 构造查询数据
	   * @param string $paraMap
	   * @param bool $urlencode
	   * @return string
	   */
	  private function formatQueryParaMap($paraMap,$urlencode){
		  	$buff = "";
		  	ksort($paraMap);
		  	foreach($paraMap as $k => $v){
		  		if(null != $v && "null" != $v && "sign" != $k){
		  			if($urlencode){
		  				$v = urlencode($v);
		  			}
		  			$buff .= $k. "=" . $v . "&";
		  		}
		  	}
		  	$reqPar = '';
		  	if(strlen($buff) > 0){
		  		$reqPar = substr($buff, 0,strlen($buff) - 1);
		  	}
		  	return $reqPar;
	  }
	  
	  /**
	   * 数组转XML
	   * @param array $arr
	   * @return string
	   */
	  private function arrayToXml($arr){
	  		$xml = "<xml>";
	  		foreach ($arr as $key => $val){
	  			if(is_numeric($val)){
	  				$xml .= "<" . $key . ">" . $val . "</" . $key . ">";
	  			}else{
	  				$xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
	  			}
	  		}
	  		$xml .= "</xml>";
	  		return $xml;
	  }
	  
	  /**
	   * 提交证书
	   * @param string $url
	   * @param string $vars
	   * @param string $appId 客户appid
	   * @param int $second
	   * @return mixed|boolean
	   */
	  private function curlPostSsl($url, $vars,$appId, $second=30){
		  	$ch = curl_init();
		  	//超时时间
		  	curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		  	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		  	curl_setopt($ch,CURLOPT_URL,$url);
		  	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		  	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		  	
		  	curl_setopt($ch,CURLOPT_SSLCERT,getcwd().WxPayConfig::SSLCERT_PATH);
		  	curl_setopt($ch,CURLOPT_SSLKEY,getcwd().WxPayConfig::SSLKEY_PATH);
		  	curl_setopt($ch,CURLOPT_CAINFO,getcwd().WxPayConfig::ROOTCA_PATH);
		  	
		  	curl_setopt($ch,CURLOPT_POST, 1);
		  	curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
		  	$data = curl_exec($ch);
		  	if($data){
		  		curl_close($ch);
		  		return $data;
		  	}
		  	else {
		  		$error = curl_errno($ch);
		  		echo "call faild, code:$error\n";
		  		curl_close($ch);
		  		return false;
		  	}
	  }
}