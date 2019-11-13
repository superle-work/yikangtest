<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_log')) require 'model/base/lib_log.php';
/**
 *日志管理
 * @name base_log.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class base_log extends admin_controller{
    private $lib_log;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->lib_log = new lib_log();
	}
	
	//----------------------------------操作日志------------------------------
	
	/**
	 * 操作日志列表页面
	 */
	function logList(){
		$this->getSetMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "操作日志列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/log/page/logList.html");
	}
	
	/**
	 * 删除操作日志
	 */
	function deleteLog(){
		$ids = $this->spArgs('ids');
		$result = $this->lib_log->deleteLog($ids);
		$this->log(__CLASS__, __FUNCTION__, "删除操作日志", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 分页查询操作日志记录
	 */
	function pagingLog(){
	    $page = $this->getPageInfo($this);
	    $keyValueList = array('name'=>'like','simple_desc'=>'like','province'=>'like','city'=>'like','module'=>'=','is_page'=>'=','type'=>'=','from_add_time'=>'>=','to_add_time'=>'<=');
	    $conditionList = $this->getPagingList($this, $keyValueList);
	    $sort = "add_time desc";
	    $result = $this->lib_log->pagingLog($page,$conditionList,$sort);
	    echo json_encode($result);
	}
	
	//----------------------------------错误日志------------------------------
    
	/**
	 * 错误日志列表页面
	 */
	function errorList(){
	    $this->getSetMenu($this);
	    $this->log(__CLASS__, __FUNCTION__, "错误日志列表页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/base/error/page/errorList.html");
	}
	
	/**
	 * 错误详情页面
	 */
	function errorDetail(){
	    $this->getMenu($this);
	    $errorResult = $this->lib_log->findError(array('id'=>$this->spArgs('id')));
	    $this->error = $errorResult['data'];
	    $this->log(__CLASS__, __FUNCTION__, "错误日志详情页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/base/error/page/errorDetail.html");
	}
	
	/**
	 * 删除错误日志
	 */
	function deleteError(){
	    $ids = $this->spArgs('ids');
	    $result = $this->lib_log->deleteError($ids);
	    $this->log(__CLASS__, __FUNCTION__, "删除错误日志", 0, 'del');
	    echo json_encode($result);
	}
	
	/**
	 * 分页查询错误日志记录
	 */
	function pagingError(){
	    $page = $this->getPageInfo($this);
	    $keyValueList = array('name'=>'like','province'=>'like','city'=>'like','from_add_time'=>'>=','to_add_time'=>'<=');
	    $conditionList = $this->getPagingList($this, $keyValueList);
	    $sort = "add_time desc";
	    $result = $this->lib_log->pagingError($page,$conditionList,$sort);
	    echo json_encode($result);
	}
}