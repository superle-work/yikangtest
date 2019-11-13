<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_sms')) require 'model/plugin/lib_sms.php';
/**
 * 短信管理
 * @name plugin_sms.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class plugin_sms extends admin_controller{
	private $lib_sms;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		$this->lib_sms = new lib_sms();
	}
	
	//短信发送记录
	/**
	 * 短信中心页面
	 */
	function smsRecordList(){
	    $this->getSetMenu($this);//设置侧边栏导航
	    //剩余短信数量
	    $countResult = $this->lib_sms->findSMS();
	    $this->quantity = $countResult['data']['count'];
	    $this->log(__CLASS__, __FUNCTION__, "短信中心页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/plugin/page/smsRecordList.html");
	}
	/**
	 * 短信发送记录列表页面
	 */
	function smsRecordManage(){
	    $this->getMenu($this);//设置侧边栏导航
	    //获取用户控制面板
	    $page = $this->spArgs('page');
	    if($page == 'smsSendRecord'){
	        $countResult = $this->lib_sms->findSMS();
	        $this->quantity = $countResult['data']['count'];
	        $this->log(__CLASS__, __FUNCTION__, "短信发送记录页面", 1, "view");//加入日志
	        $this->display("../template/admin/{$this->theme}/plugin/page/smsSendRecord.html");
	    }else{
	        $this->log(__CLASS__, __FUNCTION__, "短信购买记录页面", 1, "view");//加入日志
	        $this->display("../template/admin/{$this->theme}/plugin/page/smsPayRecord.html");
	    }
	}
	
	/**
	 * 分页查询短信发送记录
	 */
	function pagingRecord(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('verify_result' => '=','type' => '=','send_phone' => '=');
		$conditionList = $this->getPagingList($this, $keyValueList);
		$sort = "add_time desc";
		$result = $this->lib_sms->pagingRecord($page, $conditionList, $sort);
		echo json_encode($result);
	}
	
	//短信充值记录
	
	/**
	 * 分页查询短信充值记录
	 */
	function pagingPay(){
		$page = $this->getPageInfo($this);
		$conditionList = array();//默认无条件 必须有
		$sort = "add_time desc";
		$result = $this->lib_sms->pagingPay($page, $conditionList, $sort);
		
		echo json_encode($result);
	}
}