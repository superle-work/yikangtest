<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_ticket')) require 'model/plugin/lib_ticket.php';
/**
 *小票打印机管理
 * @name base_ticket.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-18
 */
class base_ticket extends admin_controller{
    private $lib_ticket;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->lib_ticket = new lib_ticket();
	}
	
	//----------------------------------------设备管理----------------------------------------
	
    /**
	 * 打印机列表页面
	 */
	function deviceList(){
		$this->getSetMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "打印机列表页面", 1, 'view');
		$deviceResult = $this->lib_ticket->findAllDevice();
		$this->deviceList = $deviceResult['data'];
		$this->display("../template/admin/{$this->theme}/base/ticket/page/deviceList.html");
	}
	
	/**
	 * 添加打印机页面
	 */
	function addDevice(){
	    $this->getMenu($this);
	    $this->log(__CLASS__, __FUNCTION__, "添加打印机页面", 1, 'add');
		$this->display("../template/admin/{$this->theme}/base/ticket/page/addDevice.html");
	}
	
	/**
	 * 编辑打印机页面
	 */
	function editDevice(){
	    $this->getMenu($this);
	    $deviceResult = $this->lib_ticket->findDevice(array('id' => $this->spArgs('id')));
	    $this->device = $deviceResult['data'];
	    $this->log(__CLASS__, __FUNCTION__, "编辑打印机页面", 1, 'edit');
	    $this->display("../template/admin/{$this->theme}/base/ticket/page/editDevice.html");
	}
	
	/**
	 * 添加打印机
	 */
	function insertDevice(){
		$keyList = array(device_num,device_key,buy_date,install_date,user);
		$ticketInfo = $this->getArgsList($this, $keyList);
		$result = $this->lib_ticket->addDevice( $ticketInfo );
		$this->log(__CLASS__, __FUNCTION__, "添加打印机", 0, 'add');
		echo json_encode($result);
	}
	
	/**
	 * 编辑打印机
	 */
	function updateDevice(){
		$id = $this->spArgs('sid');
		$deviceInfo = $this->getArgsList($this, array(device_num,device_key,buy_date,install_date,user));
		$result = $this->lib_ticket->updateDevice( array('id' => $id), $deviceInfo );
		$this->log(__CLASS__, __FUNCTION__, "编辑打印机", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 删除打印机
	 */
	function deleteDevice(){
		$result = $this->lib_ticket->deleteDevice($this->spArgs('ids'));
		$this->log(__CLASS__, __FUNCTION__, "删除打印机", 0, '');
		echo json_encode($result);
	}

}