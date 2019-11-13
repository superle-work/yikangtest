<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_cash_account')) require "model/base/table/m_base_cash_account.php";

/**
 * 提供用户账户服务
 * @name lib_base_cash_account.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2017-05-12
 */
class lib_base_cash_account extends base_model{
    private $m_cash_account;
    public $tableConfigObj;//配置表对象
    
    function __construct(){
        parent::__construct();
        $this->m_cash_account = new m_base_cash_account();
        if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
        $this->tableConfigObj = new UtilConfig('base_config');
    }
	/**
	 * 插入用户账户记录
	 * @param array $conditions
	 * @return array
	 */
	function addAccount($accountInfo){
		try{
			$accountInfo['add_time'] = date("Y-m-d H:i:s",time());
			$result = $this->m_cash_account->create ($accountInfo);
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
	 * 更新用户账户记录
	 * @param array $conditions
	 * @param array $row
	 * @return array
	 */
	function updateAccount($conditions, $row){
		try{
			$result = $this->m_cash_account->update ($conditions,$row);
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
	 * 设置默认账户
	 * @param int $id
	 * @param int $user_id
	 * @return array
	 */
	function setDefaultAccount($id,$user_id){
		try{
			$sql = "UPDATE base_cash_account SET is_default = 0 WHERE user_id = {$user_id}";
			$result = $this->m_cash_account->runSql($sql);
			$sql = "UPDATE base_cash_account SET is_default =1 WHERE id = {$id}";
			$result = $this->m_cash_account->runSql($sql);
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	/**
	 * 单个删除用户账户记录
	 * @param array $conditions
	 * @return array
	 */
	function deleteAccount($conditions){
		try{
			$result = $this->m_cash_account->delete ( $conditions);
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
	 * 批量删除用户账户记录
	 * @param string $ids 如 1,2,3,4
	 * @return array
	 */
	function deleteAccountBatch($ids){
		try{
			$sql = "DELETE FROM base_cash_account WHERE id in({$ids})";
			$result = $this->m_cash_account->runSql($sql);
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
	 * 查看用户账户记录
	 * @param array $condition
	 * @return array
	 */
	function findAccount($condition){
		try{
			$result = $this->m_cash_account->find($condition);
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
	 * 查看多条用户账户记录
	 * @param array $condition
	 * @return array
	 */
	function findAllAccount($conditions,$sort){
		try{
			$result = $this->m_cash_account->findAll($conditions,$sort);
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
	  *分页查询用户账户记录
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return array
	 */
	function pagingAccount($page, $conditionList,$sort){
		$result = $this->m_cash_account->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
}