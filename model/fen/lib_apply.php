<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_fen_distributor_apply')) require "model/fen/table/m_fen_distributor_apply.php";

/**
 * 提供分销商申请服务
 * @name lib_apply.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_apply extends base_model{
    private $m_distributor_apply;
    public $tableConfigObj;//配置表对象
    
    function __construct(){
        parent::__construct();
        $this->m_distributor_apply = new m_fen_distributor_apply();
        if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
        $this->tableConfigObj = new UtilConfig('fen_config');
    }
	/**
	 * 添加分销商申请记录
	 * @param array $applyInfo 申请记录
	 * @return array
	 */
	function addApply($applyInfo){
		try{
			$applyInfo['add_time'] = date("Y-m-d H:i:s",time());
			$this->m_distributor_apply->runSql("set names 'utf8mb4'");
			$addId = $this->m_distributor_apply->create ( $applyInfo );
			if($addId == true){
				return  common::errorArray(0, "添加成功", $addId);
			}else{
				return  common::errorArray(1, "添加失败", $addId);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 更新分销商申请记录
	 * @param array $conditions
	 * @param array $row
	 * @return array
	 */
	function updateApply($conditions, $row){
		try{
			if($row['is_check']){
				$row['check_time'] = common::getTime();
			}
			$result = $this->m_distributor_apply->update ($conditions,$row );
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
	 * 单个删除分销商申请记录
	 * @param array $conditions
	 * @return array
	 */
	function deleteApply($conditions){
		try{
			$result = $this->m_distributor_apply->delete ( $conditions);
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
	 * 批量删除分销商申请记录
	 * @param string $ids 如 1,2,3,4
	 * @return array
	 */
	function deleteApplyBatch($ids){
		try{
			$sql = "DELETE FROM cg_distributor_apply WHERE id in({$ids})";
			$result = $this->m_distributor_apply->runSql($sql);
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
	 * 查看分销商申请记录
	 * @param array $condition
	 * @return array
	 */
	function findApply($condition){
		try{
			$result = $this->m_distributor_apply->find($condition);
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
	  *分页查询分销商申请记录
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return array
	 */
	function pagingApply($page, $conditionList,$sort){
		$result = $this->m_distributor_apply->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
}