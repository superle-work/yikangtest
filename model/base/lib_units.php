<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_units')) require 'model/base/table/m_base_units.php';
/**
 * 提供单位服务
 * @name lib_units.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-02
 */
class lib_units extends base_model{
	private $m_units;
	
	/**
	 * 构造函数
	 */
	function __construct(){
		$this->m_units = new m_base_units();
	}
	
	/**
	 * 添加单位
	 * @param array $info
	 * @return array $result
	 */
	public function addUnits($info){
		try{
			$addId = $this->m_units->create($info);
			if($addId){
				return  common::errorArray(0, "添加成功", $addId);
			}else{
				return  common::errorArray(1, "添加失败",false);
			}	
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 查找单位列表
	 * @param array $condition
	 * @param string $sort
	 * @return array $result
	 */
	public function findAllUnits($condition, $sort = null){
		try{
			$result = $this->m_units->findAll($condition, $sort);
			if($result){
				return  common::errorArray(0, "查询成功", $result);
			}else{
				return  common::errorArray(1, "查询失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 查找单位
	 * @param array $condition
	 * @return array $result
	 */
	public function findUnits($condition){
		try{
			$result = $this->m_units->find($condition);
			if($result){
				return  common::errorArray(0, "查询成功", $result);
			}else{
				return  common::errorArray(1, "查询失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 修改单位
	 * @param array $condition
	 * @param array $info
	 * @return array $result
	 */
	public function updateUnits($condition, $info){
		try{
			$result = $this->m_units->update($condition, $info);
			if($result){
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
	 * 删除单位
	 * @param string $ids
	 * @return array $result
	 */
	public function deleteUnits($ids){
		try{
		    $sql = "delete from base_units where id in($ids)";
			$result = $this->m_units->runSql($sql);
			if($result){
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
	 * 分页查询单位
	 * @param array$page 分页信息
	 * @param array $conditionList 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @return array $result
	 */
	public function pagingUnits($page, $conditionList, $sort = null){
		$result = $this->m_units->paging($page, $conditionList, $sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
}