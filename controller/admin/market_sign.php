<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_sign')) require 'model/market/sign/lib_sign.php';
/**
 * 微信签到管理
 * @name market_sign.php
 * @package sign
 * @category controller
 * @link http://www.changekeji.com
 * @author Lay
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-09-05
 */
class market_sign extends admin_controller{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		common::rightVerify($_SESSION['admin'], "./admin.php?c=admin&a=admin");
	}
	
	/**
	 * 签到记录列表页面
	 */
	function signRecordList(){
		$this->getSetMenu($this);
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "签到记录列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/market/sign/page/signRecordList.html");
	}

	/**
     * 签到配置页面
     */
    function signConfigPage(){
        $this->getMenu($this);
        //签到配置
        $lib_sign = new lib_sign();
        $result = $lib_sign->findAllSign(null,'days asc');
        $this->signList = $result['data'];
        //签到描述
        if(!class_exists('lib_market_config')) include 'model/market/config/lib_market_config.php';
        $lib_market_config = new lib_market_config();
        $configResult = $lib_market_config->findConfig(array('item_key'=>'sign_desc'));
        $this->signDesc = $configResult['data']['item_value'];
        //日志记录
        $this->log(__CLASS__, __FUNCTION__, "签到配置页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/market/sign/page/signConfigPage.html");
    }

	/**
	 * 编辑签到签到说明
	 */
	function editSignDesc(){
		$this->getMenu($this);
		//签到配置编辑
        $lib_sign = new lib_sign();
        $result = $lib_sign->findAllSign(null,'days asc');
        $this->signList = $result['data'];
        //签到描述
        if(!class_exists('lib_market_config')) include 'model/market/config/lib_market_config.php';
        $lib_market_config = new lib_market_config();
        $configResult = $lib_market_config->findConfig(array('item_key'=>'sign_desc'));
		ChromePhp::info($configResult);
        $this->configInfo = $configResult['data'];
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "编辑签到配置页面", 1, 'edit');
		$this->display("../template/admin/{$this->theme}/market/sign/page/editSignDesc.html");
	}
	
	/**
	 * 编辑签到配置页面
	 */
	function editSign(){
		$this->getMenu($this);
		//签到配置编辑
        $lib_sign = new lib_sign();
        $condition = array("id"=>$this->spArgs("id"));
        $result = $lib_sign->findSign($condition);
        $this->signInfo = $result['data'];
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "编辑签到配置页面", 1, 'edit');
		$this->display("../template/admin/{$this->theme}/market/sign/page/editSign.html");
	}
	
	/**
	 * 添加签到配置页面
	 */
	function addSign(){
		$this->getMenu($this);
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "添加签到配置页面", 1, 'add');
		$this->display("../template/admin/{$this->theme}/market/sign/page/addSign.html");
	}
	
	/**
	 * 修改签到配置
	 */
	function updateSign(){
		$lib_sign = new lib_sign();
		$condition['id'] = $this->spArgs('id');
		if(NULL != $this->spArgs('days') && '' != $this->spArgs('days')){
		    $signInfo['days'] = $this->spArgs('days');
		}
		$signInfo['points'] = $this->spArgs('points');
		$result = $lib_sign->updateSign( $condition, $signInfo);
		//签到描述
		$signInfo['sign_desc'] = $this->spArgs('sign_desc');
		if(!class_exists('lib_market_config')) include 'model/market/config/lib_market_config.php';
		$lib_market_config = new lib_market_config();
		$lib_market_config->updateConfigPer(array('item_key'=>'sign_desc' ),array('item_value'=>$this->spArgs('sign_desc')));
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "修改签到配置", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 添加签到配置
	 */
	function insertSign(){
		$lib_sign = new lib_sign();
		$signInfo['days'] = $this->spArgs('days');
		$signInfo['points'] = $this->spArgs('points');
		$result = $lib_sign->addSign($signInfo);
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "添加签到配置", 0, 'add');
		echo json_encode($result);
	}
	
	/**
	 * 删除签到记录
	 */
	function deleteSign(){
		$lib_sign = new lib_sign();
		$ids = $this->spArgs('ids');
		$result = $lib_sign->deleteSignRecord( $ids);
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "删除签到配置", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 删除签到配置
	 */
	function deleteSignConfig(){
		$lib_sign = new lib_sign();
		$ids = $this->spArgs('ids');
		$result = $lib_sign->deleteSign( $ids);
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "删除签到配置", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 分页查询用户签到记录
	 */
	function pagingSignRecord(){
		$page = array('pageIndex' => $this->spArgs('pageIndex'),'pageSize'=> $this->spArgs('pageSize'));
		$conditionList = array();//默认无条件 必须有
		if("" != $this->spArgs('nick_name') && null != $this->spArgs('nick_name')){
			array_push($conditionList,  array("field" => "nick_name","operator" => "like","value" => $this->spArgs('nick_name')));
		}
		if("" != $this->spArgs('from') && null != $this->spArgs('from')){
			array_push($conditionList,  array("field" => "add_time","operator" => ">=","value" => $this->spArgs('from')));
		}
		if("" != $this->spArgs('to') && null != $this->spArgs('to')){
			array_push($conditionList,  array("field" => "add_time","operator" => "<=","value" => $this->spArgs('to')));
		}
		$sort = "add_time desc";
		$lib_sign = new lib_sign();
		$result =$lib_sign-> pagingSignRecord( $page, $conditionList, $sort);
		echo json_encode($result);
	}
	
}
/* End of file w_signManage.php */
/* Location: ./controller/admin/w_signManage.php */