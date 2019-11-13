<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_service')) require 'model/base/table/m_base_service.php';
/**
 * 提供商城客服管理服务
 * @name lib_service.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-02
 */

class lib_service extends base_model{
	private $m_service;
	
	/**
	 * 构造方法
	 */
	function __construct(){
		parent::__construct();
		$this->m_service = new m_base_service();
	}
	
	/**
	 * 添加客服人员
	 * @param array $info
	 * @return array $result
	 */
	public function addService($info){
		try{
		    $info['add_tine'] = common::getTime();
			$id = $this->m_service->create($info);
			if($id){
				return  common::errorArray(0, "添加成功", $id);
			}else{
				return  common::errorArray(1, "添加失败", false);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 找到符合条件所有客服
	 * @param array $condition
	 * @param string $sort
	 * @return array $result
	 */
	public function findAllService($condition, $sort = null){
		try{
			$result = $this->m_service->findAll($condition, $sort);
			if(true == $result){
				return  common::errorArray(0, "查找成功", $result);
			}else{
				return  common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 找到符合条件的某个客服
	 * @param array $condition
	 * @return array $result
	 */
	public function findService($condition){
		try{
			$result = $this->m_service->find($condition);
			if(true == $result){
				return  common::errorArray(0, "查找成功", $result);
			}else{
				return  common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 修改客服信息
	 * @param array $condition
	 * @param array $row
	 * @return array $result
	 */
	public function updateService($condition, $row){
		try{
			$result = $this->m_service->update($condition, $row);
			if(true == $result){
				return  common::errorArray(0, "修改成功", $result);
			}else{
				return  common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 批量删除客服
	 * @param string $ids
	 * @return array $result
	 */
	public function deleteService($ids,$field = 'id'){
		try{
			$sqlDelete = "DELETE FROM base_service WHERE {$field} IN ({$ids})";
			$result = $this->m_service->runSql($sqlDelete);
			if($result){
			    //删除二维码图片
			    $sqlFind = "SELECT * FROM base_service WHERE {$field} IN ({$ids})";
			    $serviceList = $this->m_service->findSql($sqlFind);
			    if(count($serviceList)){
			        foreach ($serviceList as $service){
			          if($service['type'] == '2'){//微信客服有二维码图片
		                  unlink($service['number']);
		              }
			        }
			    }
				return  common::errorArray(0, "删除成功", $result);
			}else{
				return  common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 分页查询客服
	 * @param array$page 分页信息
	 * @param array $conditionList 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @return array $result
	 */
	function pagingService($page, $conditionList, $sort = null){
		$result = $this->m_service->paging($page, $conditionList, $sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
}