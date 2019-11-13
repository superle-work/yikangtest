<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_express')) require 'model/base/lib_express.php';
/**
 *快递公司管理
 * @name base_express.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author Micheal
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-12-13
 */
class base_express extends admin_controller{
    private $lib_express;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->lib_express = new lib_express();
	}
	
	/**
	 * 快递公司列表页面
	 */
	function expressList(){
		$this->getSetMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "快递公司列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/express/page/expressList.html");
	}
	
	/**
	 * 添加快递公司页面
	 */
	function addExpress(){
	    $this->getMenu($this);
	    $this->log(__CLASS__, __FUNCTION__, "添加快递公司页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/base/express/page/addExpress.html");
	}
	
	/**
	 * 编辑快递公司页面
	 */
	function editExpress(){
		$this->getMenu($this);
		$expressResult = $this->lib_express->findExpress(array('id'=>$this->spArgs('id')));
		$this->expressInfo = $expressResult['data'];
		$this->log(__CLASS__, __FUNCTION__, "编辑快递公司页面", 1, 'edit');
		$this->display("../template/admin/{$this->theme}/base/express/page/editExpress.html");
	}
	
	/**
	 * 增加快递公司
	 */
	function insertExpress(){
	    $addInfo = $this->getArgsList($this,array(name,code,is_use,sort));
	    $result = $this->lib_express->addExpress($addInfo);
	    $this->log(__CLASS__, __FUNCTION__, "增加快递公司", 0, 'add');
	    echo json_encode($result);
	}
	
	/**
	 * 删除快递公司
	 */
	function deleteExpress(){
		$ids = $this->spArgs('ids');
		$result = $this->lib_express->deleteExpress($ids);
		$this->log(__CLASS__, __FUNCTION__, "删除快递公司", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 编辑快递公司
	 */
	function updateExpress(){
	    $expressInfo = $this->getArgsList($this,array(name,code,is_use,sort));
		$result = $this->lib_express->updateExpress (array('id'=>$this->spArgs('express_id')),$expressInfo);
		$this->log(__CLASS__, __FUNCTION__, "编辑快递公司", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 快递公司的停用/启用
	 */
	function setExpressState(){
	    $result = $this->lib_express->updateExpress(array('id'=>$this->spArgs('id')),array('is_use'=>$this->spArgs('is_use')));
	    $this->log(__CLASS__, __FUNCTION__, "快递公司的停用/启用", 0, 'edit');
	    echo json_encode($result);
	}
	
	/**
	 * 分页查询快递公司
	 */
	function pagingExpress(){
	    $page = $this->getPageInfo($this);
	    $keyValueList = array('name'=>'like','is_use'=>'=');
	    $conditionList = $this->getPagingList($this, $keyValueList);
	    $sort = "is_use desc,sort asc";
	    $result = $this->lib_express->pagingExpress($page,$conditionList,$sort);
	    echo json_encode($result);
	}
}