<?php
/**
 * 微信入口
 * @name weixin_index.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-05
 */
class  weixin_index extends spController{
	/**
	 * 对接微信平台
	 */
	function index(){
		if (isset($_GET['echostr'])) {
	    	$this->valid();
		}else{
			$request = $this->getRequstObj();//获取腾讯回传的原始对象
			include 'model/weixin/lib_request.php';
			$requestData = new lib_request();
			echo $requestData->switchType($request);//处理后，返回给微信用户
		}
	}	
	
	/**
	 * 提供给微信服务器验证服务器配置
	 */
	private function valid(){
		$echoStr = $_GET["echostr"];
	
		//valid signature , option
		if($this->checkSignature()){
			echo $echoStr;
			exit;
		}
	}
	
	/**
	 * 微信验证第三方开发服务器
	 * @throws Exception
	 * @return boolean
	 */
	private function checkSignature(){
		// you must define TOKEN by yourself
		if (!defined("WECHAT_TOKEN")) {
			throw new Exception('TOKEN is not defined!');
		}
	
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
	
		$token = WECHAT_TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		// use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
	
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 获取微信服务器向第三方服务器回传的原始数据对象
	 * @return multitype:
	 */
	private function getRequstObj(){
		$xml = (array) simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA);

        //将数组键名转换为小写
       return  array_change_key_case($xml, CASE_LOWER);
	}
	
}