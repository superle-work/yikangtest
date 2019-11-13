<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_log')) require 'model/base/table/m_base_log.php';
if(!class_exists('m_base_error')) require 'model/base/table/m_base_error.php';
/**
 * 提供日志记录服务
 * @name lib_log.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-03
 */
class lib_log extends base_model{
    private $m_log;
    private $m_error;
    
    function __construct(){
        parent::__construct();
        $this->m_log = new m_base_log();
        $this->m_error = new m_base_error();
    }
    
	/**
	 * 获取单个日志信息
	 * @param array $condition
	 * @return array $result
	 */
	public function findLog($condition){
		try {
			$reslut = $this->m_log -> find($condition);
			if(true == $reslut){
				return common::errorArray(0, "查询成功",$reslut);
			}else{
				return common::errorArray(1, "查询为空", $reslut);
			}
		}catch (Exception $ex){
			$this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 添加日志
	 * @param array $row
	 * @return array $result
	 */
	public function addLog($row){
		try {
			$row['add_time'] = common::getTime();
			$addId = $this->m_log -> create($row);
			if($addId){
				return common::errorArray(0, "添加成功",$addId);
			}else{
				return common::errorArray(1, "添加失败", false);
			}
		}catch (Exception $ex){
			$this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除日志 批量
	 * @param string $ids
	 * @return array $resultArray
	 */
	public function deleteLog($ids){
		try{
			$sql = "DELETE FROM base_log WHERE id IN ({$ids})";
			$result = $this->m_log->runSql ($sql);
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
	 * 自动删除1000条之后的操作日志
	 * @param int $recordsNumber;
	 * @return $resultArray
	 */
	public function deleteSomeLogs($logsNumber = 999){
	    try{
	        $sql = "DELETE FROM base_log WHERE id < (SELECT MIN(l.id) FROM (SELECT id FROM base_log ORDER BY id DESC LIMIT {$logsNumber}) AS l)";
	        $result = $this->m_log->runSql ($sql);
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
	 * 分页查看日志
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @param array $orList
	 * @return array $result
	 */
	public function pagingLog($page,$conditionList,$sort = '',$orList = null){
		$result = $this->m_log->paging($page, $conditionList,$sort,$orList);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 清楚多余的日志
	 * @param int $days
	 * @return array $result
	 */
	public function clearLog($days){
	    try{
	        $date = date("Y-m-d",time() - ($days * 3600 * 24));
	        $sql = "DELETE FROM base_log WHERE add_time <  '{$date}'";
	        $result = $this->m_log->runSql ($sql);
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
	
	//------------------------------------错误日志管理-------------------------
	
	/**
	 * 获取单个错误信息
	 * @param array $condition
	 * @return array $result
	 */
	public function findError($condition){
		try {
			$reslut = $this->m_error -> find($condition);
			if(true == $reslut){
				return common::errorArray(0, "查询成功",$reslut);
			}else{
				return common::errorArray(1, "查询为空", $reslut);
			}
		}catch (Exception $ex){
			$this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 添加错误
	 * @param array $row
	 * @return array $result
	 */
	public function addError($row){
		try {
			$row['add_time'] = common::getTime();
			$addId = $this->m_error -> create($row);
			if(true == $addId){
				return common::errorArray(0, "添加成功",$addId);
			}else{
				return common::errorArray(1, "添加失败", false);
			}
		}catch (Exception $ex){
			$this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除错误 批量
	 * @param string $ids
	 * @return array $result
	 */
	public function deleteError($ids){
		try{
			$sql = "DELETE FROM base_error WHERE id IN ({$ids})";
			$result = $this->m_error->runSql ($sql);
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
	 * 分页查看错误
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @param array $orList
	 * @return array $result
	 */
	public function pagingError($page,$conditionList,$sort = '',$orList = null){
		$result = $this->m_error->paging($page, $conditionList,$sort,$orList);
		if($result['errorCode'] == 1){
			$this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 清楚多余的错误日志
	 * @param int $days
	 * @return array $result
	 */
	public function clearError($days){
	    try{
	        $date = date("Y-m-d",time() - ($days * 3600 * 24));
	        $sql = "DELETE FROM base_error WHERE add_time <  '{$date}'";
	        $result = $this->m_log->runSql ($sql);
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
	 * 自动删除1000条之后的错误日志
	 * @param int $recordsNumber;
	 * @return $resultArray
	 */
	public function deleteSomeErrors($errorsNumber = 999){
	    try{
	        $sql = "DELETE FROM base_error WHERE id < (SELECT MIN(e.id) FROM (SELECT id FROM base_error ORDER BY id DESC LIMIT {$errorsNumber}) AS e)";
	        $result = $this->m_error->runSql ($sql);
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
}
