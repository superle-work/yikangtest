<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_school')) require 'model/base/lib_school.php';
/**
 *校区管理
 * @name base_school.php
 * @package fjwl
 * @category controller
 * @link http://www.chanekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2017-07-25
 */
class base_school extends admin_controller{
    private $lib_school;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->lib_school = new lib_school();
	}
	
    /**
	 * 校区列表页面
	 */
	function schoolList(){
		$this->getSetMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "校区列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/school/page/schoolList.html");
	}
	
	/**
	 * 添加页面
	 */
	function addSchool(){
	    $this->getMenu($this);
	    $this->log(__CLASS__, __FUNCTION__, "校区列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/school/page/addSchool.html");
	}
	
	/**
	 * 编辑页面
	 */
	function editSchool(){
		$this->getMenu($this);
		$schoolResult = $this->lib_school->findSchool(array('id' => $this->spArgs('id')));
		$this->schoolInfo = $schoolResult['data'];
		$this->log(__CLASS__, __FUNCTION__, "校区列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/school/page/editSchool.html");
	}
	
	/**
	 * 添加校区
	 */
	function insertSchool(){
	    $this->getMenu($this);
	    $schoolInfo = $this->getArgsList($this, array(province,city,area,school,fee,sort_num));
	    $schoolInfo['school'] = $schoolInfo['area'];
	    $result = $this->lib_school->addSchool( $schoolInfo );
	    $this->log(__CLASS__, __FUNCTION__, "添加校区", 0, 'add');
	    echo json_encode($result);
	}
	
	/**
	 * 获取校区列表
	 */
	function getSchoolList(){
	    $schoolResult = $this->lib_school->findAllSchool();
	    $this->log(__CLASS__, __FUNCTION__, "获取校区列表", 0, 'view');
	    echo json_encode($schoolResult);
	}
	
	/**
	 * 修改校区
	 */
	function updateSchool(){
		$schoolInfo = $this->getArgsList($this, array(province,city,area,school,fee,sort_num));
		$result = $this->lib_school->updateSchool( array('id' => $this->spArgs('id')), $schoolInfo );
		$this->log(__CLASS__, __FUNCTION__, "修改校区", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 删除校区
	 */
	function deleteSchool(){
		$result = $this->lib_school->deleteSchool($this->spArgs('ids'));
		$this->log(__CLASS__, __FUNCTION__, "删除校区", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 分页查询校区
	 */
	function pagingSchool(){
		$keyValueList = array('area'=>'like', 'province' => 'like', 'city' => 'like');
		$conditionList = $this->getPagingList($this, $keyValueList);
		$page = $this->getPageInfo($this);
		$result = $this->lib_school->pagingSchool($page, $conditionList, $sort="sort_num asc");
		echo json_encode($result);
	}
}