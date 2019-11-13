<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_balance_record')) require "model/base/table/m_base_balance_record.php";

/**
 * 提供余额提取服务
 * @name lib_balance_record.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2017-05-15
 */
class lib_balance_record extends base_model{
    private $m_balance_record;
    public $tableConfigObj;//配置表对象
    
    function __construct(){
        parent::__construct();
        $this->m_balance_record = new m_base_balance_record();
        if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
        $this->tableConfigObj = new UtilConfig('base_config');
    }
	/**
	 * 插入佣金提取记录
	 * @param array $conditions
	 * @return array
	 */
	function addRecord($recordInfo){
		try{
			$recordInfo['add_time'] = date("Y-m-d H:i:s",time());
			$result = $this->m_balance_record->create ($recordInfo);
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", "");
		}
	}
	/**
	 * 更新佣金提取记录
	 * @param array $conditions
	 * @param array $row
	 * @return array
	 */
	function updateRecord($conditions, $row){
		try{
			$result = $this->m_balance_record->update ($conditions,$row);
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", "");
		}
	}
	
	/**
	 * 根据distributor_id查找取款总额
	 * @param int $conditions
	 * @return array
	 */
	function findAllbalanceMoney($user_id){
		try{
			$sql ="SELECT SUM(money) AS sumMoney FROM base_balance_record WHERE user_id = {$user_id}";
			$result = $this->m_balance_record->findSql($sql);
			if(true == $result){
				return common::errorArray(0, "统计成功", $result[0]['sumMoney']);
			}else{
				return common::errorArray(1, "统计成功", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", "");
		}
	}
	
	/**
	 * 单个删除佣金提取记录
	 * @param array $conditions
	 * @return array
	 */
	function deleteRecord($conditions){
		try{
			$result = $this->m_balance_record->delete ( $conditions);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 批量删除佣金提取记录
	 * @param string $ids 如 1,2,3,4
	 * @return array
	 */
	function deleteRecordBatch($ids){
		try{
			$sql = "DELETE FROM base_balance_record WHERE id in({$ids})";
			$result = $this->m_balance_record->runSql($sql);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查看佣金提取记录
	 * @param array $condition
	 * @return array
	 */
	function findRecord($condition){
		try{
			$result = $this->m_balance_record->find($condition);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败",$ex);}
	}
	
	/**
	 * 查看多条佣金提取记录
	 * @param array $condition
	 * @return array
	 */
	function findAllRecord($conditions,$sort){
		try{
			$result = $this->m_balance_record->findAll($conditions,$sort);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	  *分页查询佣金提取记录
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return array
	 */
	function pagingRecord($page, $conditionList,$sort){
		$result = $this->m_balance_record->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
}