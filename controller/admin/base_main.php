<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_admin')) require 'model/base/lib_admin.php';
/**
 *后台未登陆
 * @name base_main.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-02
 */
class base_main extends admin_controller{
    private $lib_admin;
    
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->lib_admin = new lib_admin();
	}
	
	/**
	 * 管理员登录界面
	 */
	function login(){
	    $this->theme = $this->getConfigValue('admin_theme');
		$this->display("../template/admin/{$this->theme}/login.html");
	}

	/**
	 * 管理员注销
	 */
	function logout(){
		if("change" != $_SESSION['admin']['account'] && !DEBUG){
			$this->log(__CLASS__, __FUNCTION__, "退出登录", 0, 'view');
		}
		session_destroy();
		$_SESSION['admin'] = null;
		$this->theme = $this->getConfigValue('admin_theme');
		$this->display("../template/admin/{$this->theme}/login.html");
	}
	
	/**
	 * 管理员登录
	 */
	function adminLogin(){
		$adminInfo = array(
				"account" =>$this->spArgs('account'),
				"password" => $this->spArgs('password'),
		);
		
		$result = $this->lib_admin->adminLogin ( $adminInfo );
		if($result['errorCode'] == 0 ){
			$_SESSION['admin'] = $result['data'];
		    //未读信息个数
		    if(!class_exists('lib_admin_message')) include 'model/base/lib_admin_message.php';
		    $lib_admin_message = new lib_admin_message();
		    $noReadCountResult = $lib_admin_message->getNoReadMessageCount($result['data']['id']);
		    $_SESSION['admin']['noReadCount'] = $noReadCountResult['data'];
			$this->remember();
			if(!DEBUG){//非调试模式
				$this->log(__CLASS__, __FUNCTION__, "登录成功", 0, 'view');
			}
		}else{
			if(!DEBUG){//非调试模式
				$this->log(__CLASS__, __FUNCTION__, "登录失败", 0, 'view');
			}
		}
		echo json_encode($result);
		exit;
	}
	
	/**
	 * 记住账号密码
	 */
	private function remember(){
		if ($this->spArgs ( 'remember' ) == 1) {
			setcookie ( 'account', $this->spArgs ( 'account' ), time () + 3600*24*7  );
			setcookie ( 'password', $this->spArgs ( 'password' ), time () + 3600*24*7  );
			setcookie ( 'remember', $this->spArgs ( 'remember' ), time () + 3600*24*7  );
		} else {
			setcookie ( 'name', $this->spArgs ( 'account' ), time () - 3600*24*7  );
			setcookie ( 'password', $this->spArgs ( 'password' ), time () - 3600*24*7  );
			setcookie ( 'remember', $this->spArgs ( 'remember' ), time () - 3600*24*7  );
		}
	}
    
}