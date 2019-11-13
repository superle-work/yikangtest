<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_plugin_ticket_device')) require 'model/plugin/table/m_plugin_ticket_device.php';
/**
 * 提供打印机管理服务
 * @name lib_ticket.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-18
 */
class lib_ticket extends base_model{
    private $m_device;
    
    function __construct(){
        parent::__construct();
        $this->m_device = new m_plugin_ticket_device();
    }
	
    //---------------------------------------设备管理--------------------------------------
    
	/**
	 * 添加设备
	 * @param array $deviceInfo
	 * @return array $result
	 */
	public function addDevice($deviceInfo){
		try{
		$deviceInfo['add_time'] = common::getTime();
		$addId = $this->m_device->create ( $deviceInfo );
		if($addId == true){
			return  common::errorArray(0, "添加成功", $addId);
		}else{
			return  common::errorArray(1, "添加失败", false);
		}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 查询设备
	 * @param array $condition
	 * @return array $result
	 */
	public function findDevice($condition){
		try{
			$result = $this->m_device->find($condition);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取设备列表
	 * @param array $conditions
	 * @return array $result
	 */
	public function findAllDevice($conditions){
		try{
			$result = $this->m_device->findAll($conditions);
			return common::errorArray(0, "查找成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 设备信息修改
	 * @param array $condition
	 * @param array $deviceInfo
	 * @return $result
	 */
	public function updateDevice($condition,$deviceInfo){
		try{
			$result = $this->m_device->update ($condition,$deviceInfo );
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 设备删除
	 * @param string $ids
	 * @return array $result
	 */
	public function deleteDevice($ids){
		try{
		    $sql = "delete from plugin_ticket_device where id in ($ids)";
			$result = $this->m_device->runSql ( $sql);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
}