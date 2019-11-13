<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_units')) require 'model/base/lib_units.php';
/**
 *单位管理
 * @name base_units.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class base_units extends admin_controller{
    private $lib_units;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->lib_units = new lib_units();
	}
	
    /**
	 * 单位列表页面
	 */
	function unitsList(){
		$this->getSetMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "单位列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/units/page/unitsList.html");
	}
	
	/**
	 * 添加页面
	 */
	function addUnits(){
	    $this->getMenu($this);
	    $this->log(__CLASS__, __FUNCTION__, "单位列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/units/page/addUnits.html");
	}
	
	/**
	 * 编辑页面
	 */
	function editUnits(){
		$this->getMenu($this);
		$unitsResult = $this->lib_units->findUnits(array('id' => $this->spArgs('id')));
		$this->unitsInfo = $unitsResult['data'];
		$this->log(__CLASS__, __FUNCTION__, "单位列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/units/page/editUnits.html");
	}
	
	/**
	 * 添加单位
	 */
	function insertUnits(){
	    $this->getMenu($this);
	    $unitsInfo = $this->getArgsList($this, array('name', 'sort'));
	    $result = $this->lib_units->addUnits( $unitsInfo );
	    $this->log(__CLASS__, __FUNCTION__, "添加单位", 0, 'add');
	    echo json_encode($result);
	}
	
	/**
	 * 获取单位列表
	 */
	function getUnitsList(){
	    $unitsResult = $this->lib_units->findUnitsList();
	    $this->log(__CLASS__, __FUNCTION__, "获取单位列表", 0, 'view');
	    echo json_encode($unitsResult);
	}
	
	/**
	 * 修改单位
	 */
	function updateUnits(){
		$unitsInfo = $this->getArgsList($this, array('name', 'sort'));
		$result = $this->lib_units->updateUnits( array('id' => $this->spArgs('id')), $unitsInfo );
		$this->log(__CLASS__, __FUNCTION__, "修改单位", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 删除单位
	 */
	function deleteUnits(){
		$result = $this->lib_units->deleteUnits($this->spArgs('ids'));
		$this->log(__CLASS__, __FUNCTION__, "删除单位", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 分页查询单位
	 */
	function pagingUnits(){
		$keyValueList = array('name'=>'like');
		$conditionList = $this->getPagingList($this, $keyValueList);
		$result = $this->lib_units->pagingUnits($this->getPageInfo($this), $conditionList, $sort="sort asc");
		echo json_encode($result);
	}
}