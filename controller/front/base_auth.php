<?php
/**
 *微信授权
 * @name base_auth.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
 class base_auth extends spController {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
	}
	
	/**
	 * 授权主接口
	 */
	 function main(){
		//获取用户openId
		$authInfo = WeChatOAuth::getAccessTokenAndOpenId($_GET['code']);
		$openId = $authInfo['openid'];
		//未取到openId值时，继续循环，直到取到为止
		if($openId == ""){
			$goBack = urldecode($this->spArgs('goBack'));
			echo "<html><head><meta http-equiv='refresh' content='0;url=".$goBack."'></head><body></body><html>";
			echo "<script type='text/javascript'>window.location.href('".$goBack."');</script>";
			exit;
		}
		include 'model/base/lib_user.php';
		$lib_user = new lib_user();
		$userResult = $lib_user->findUser(array('open_id' => $openId));
		if($userResult['errorCode'] == 0){//是用户
			$_SESSION['user'] = $userResult['data'];
			$_SESSION['user']['authInfo'] = $authInfo;//用户授权写入session中
			if(true){//不需要注册
				//返回最初的请求
				$goBack = urldecode($this->spArgs('goBack'));
				$goBack.= '&isBack=1';
				echo "<html><head><meta http-equiv='refresh' content='0;url=".$goBack."'></head><body></body><html>";
				echo "<script type='text/javascript'>window.location.href('".$goBack."');</script>";
				exit;
			}else{//已关注，但未注册
				$goRegister = "./index.php?c=user&a=register";
				echo "<html><head><meta http-equiv='refresh' content='0;url=".$goRegister."'></head><body></body><html>";
				echo "<script type='text/javascript'>window.location.href('".$goRegister."');</script>";
				exit;
			}
		}else{//非用户
			//构造用户信息
			$fansInfo = WeChatOAuth::getUserInfo($authInfo['access_token'], $openId);
			$userInfo = array(
					"account" => $fansInfo['openid'],//account 默认等于openid
					"subscribe" => 0,
					"open_id" => $fansInfo['openid'],
					"nick_name" => $fansInfo['nickname'],
					"sex" => $fansInfo['sex'],
					"city" => $fansInfo['city'],
					"province" => $fansInfo['province'],
					"country" => $fansInfo['country'],
					"head_img_url" => $fansInfo['headimgurl'],
					"subscribe_time" => date("Y-m-d H:i:s",$fansInfo['subscribe_time']),
					"add_time" => date ( 'Y-m-d H:i:s', time () ),
					"remark" => ''
			);
			$userInfo['subscribe_times'] = 0;
			//添加到用户表中
			$result = $lib_user->addUser($userInfo);
			$userInfo['id'] = $result['data'];
			$_SESSION['user'] = $userInfo;
			$_SESSION['user']['authInfo'] = $authInfo;//用户授权写入session中
        	$goBreak = urldecode($this->spArgs('goBack'));
		    $_SESSION['last_url'] = '';
		    echo "<html><head><meta http-equiv='refresh' content='0;url=".$goBreak."'></head><body></body><html>";
		    echo "<script type='text/javascript'>window.location.href('".$goBreak."');</script>";
			exit;
		}
	}
	
	/**
	 * 未关注也可以进入
	 */
	function allWechat(){
		//获取用户openId
		$authInfo = WeChatOAuth::getAccessTokenAndOpenId($_GET['code']);
		$openId = $authInfo['openid'];
		if($openId){//不管该用户是否是会员，权限判断交给前台处理
			$_SESSION['user']['authInfo'] = $authInfo;//用户授权写入session中
			//返回最初的请求
			$goBack = urldecode($this->spArgs('goBack'));
			echo "<html><head><meta http-equiv='refresh' content='0;url=".$goBack."'></head><body></body><html>";
			echo "<script type='text/javascript'>window.location.href('".$goBack."');</script>";
			exit;
		}else{
			$goSite = "./index.php?c=site&a=home";
			echo "<html><head><meta http-equiv='refresh' content='0;url=".$goSite."'></head><body></body><html>";
			echo "<script type='text/javascript'>window.location.href('".$goSite."');</script>";
			exit;
		}
	}
	
	/**
	 * 授权主接口
	 */
	function payAuth(){
		//获取用户openId
		$authInfo = WeChatOAuth::getAccessTokenAndOpenId($_GET['code']);
		if($authInfo['openid'] != '' && $authInfo['openid']  != null){
				$_SESSION['user']['payAuth'] = $authInfo;
				//返回最初的请求
				$goBack = urldecode($this->spArgs('goBack'));
				echo "<html><head><meta http-equiv='refresh' content='0;url=".$goBack."'></head><body></body><html>";
				echo "<script type='text/javascript'>window.location.href('".$goBack."');</script>";
				exit;
		}
	}

 }