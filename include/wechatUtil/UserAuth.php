<?php
/**
 * 微信网页授权
 * @name UserAuth
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-09
 */
class UserAuth {
	/**
	 * 校验session后
	 * 获取用户收货地址的code后重定向到goBack
	 */
	public static function verify($goBack,$scope='snsapi_base',$goBreak){
		if($_SESSION['user'] == null || $_SESSION['user'] == '' || 
			$_SESSION['user']['authInfo']['openid'] == null || $_SESSION['user']['authInfo']['access_token'] == null){
		    
		    if($goBreak !=""){//用户自定义的回调地址
		        $goBack = $goBreak;
		    }
			$goBack= urlencode($goBack);
			WeChatOAuth::getCode("index.php?c=base_auth&a=main&goBack={$goBack}",1,$scope);
		}else{
			//判断授权是否过期
			$result = WeChatOAuth::checkAccessToken($_SESSION['user']['authInfo']['access_token'], $_SESSION['user']['authInfo']['openid']);
			if($result['errcode'] != 0){//过期的时候自动刷新
				$_SESSION['user']['authInfo'] = WeChatOAuth::refreshToken($_SESSION['user']['authInfo']['refresh_token']);
			}
		}
	}
	
	/**
	 * 未关注也可以进入
	 * @param url $goBack
	 */
	public static function verifyAll($goBack){
		if($_SESSION['user'] == null || $_SESSION['user'] == '' || 
			$_SESSION['user']['authInfo']['openid'] == null || $_SESSION['user']['authInfo']['access_token'] == null){
			$goBack= urlencode($goBack);
			WeChatOAuth::getCode("index.php?c=base_auth&a=allWechat&goBack={$goBack}",1);
		}else{
			//判断授权是否过期
			$result = WeChatOAuth::checkAccessToken($_SESSION['user']['authInfo']['access_token'], $_SESSION['user']['authInfo']['openid']);
			if($result['errcode'] != 0){//过期的时候自动刷新
				$_SESSION['user']['authInfo'] = WeChatOAuth::refreshToken($_SESSION['user']['authInfo']['refresh_token']);
			}
		}
	}
	
	/**
	 * 校验session后
	 * 获取用户收货地址的code后重定向到goBack
	 */
	public static function verifyPay($goBack){
		if(!isset($_SESSION['user']['payAuth'])){
			$goBack= urlencode($goBack);
			WeChatOAuth::getCode("index.php?c=base_auth&a=payAuth&goBack={$goBack}",1);
		}else{
			//判断授权是否过期
			$result = WeChatOAuth::checkAccessToken($_SESSION['user']['payAuth']['access_token'], $_SESSION['user']['payAuth']['openid']);
			if($result['errcode'] != 0){//过期的时候自动刷新
				$_SESSION['user']['payAuth'] = WeChatOAuth::refreshToken($_SESSION['user']['payAuth']['refresh_token']);
			}
		}
	}
}
