<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_promise')) require "model/store/table/m_store_promise.php";

/**
 * 提供模板管理服务
 * @name lib_promise.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_promise extends base_model{
    private $m_store_promise;
    
    function __construct(){
        parent::__construct();
        $this->m_store_promise = new m_store_promise();
    }
    
	/**
	 * 添加商家承诺
	 * @param array $newsInfo
	 * @return array $result
	 */
	public function addPromise($promiseInfo){
		try{
				$addId = $this->m_store_promise->create ($promiseInfo);
				if($addId){
					return  common::errorArray(0, "添加成功", '');
				}else{
					return  common::errorArray(1, "添加失败", '');
				}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}

	
	/**
	 * 商家承诺修改
	 * @param array $condition
	 * @param array $promiseInfo
	 * @return array $result
	 */
	public function updatePromise($condition,$promiseInfo){
		try{
			$result = $this->m_store_promise->update ($condition,$promiseInfo );
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
	 * 获取单个商家承诺
	 * @param array $conditions
	 * @return array $result
	 */
	public function findPromise($conditions){
		try{
			$result = $this->m_store_promise->find($conditions);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "数据库操作失败", array());
		}
	}
	
	/**
	 * 获取所有商家承诺
	 * @param array $conditions
	 * @param string $sort
	 * @return array $result
	 */
	public function findAllPromise($conditions,$sort = ''){
		try{
			$result = $this->m_store_promise->findAll($conditions,$sort);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "数据库操作失败", array());
		}
	}
	
	/**
	 * 删除商家承诺
	 * @param $conditions
	 * @return array $result
	 */
	public function deletePromise($conditions){
		try{
			$result = $this->m_store_promise->delete ( $conditions);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return  common::errorArray(1, "数据库操作失败", "");
		}
	}
	
	/**
	 * 批量删除商家承诺
	 * @param string $ids 
	 * @return array $result
	 */
	public function batchDeletePromise($ids){
		try{
			$sql = "DELETE FROM `store_promise` WHERE id IN ({$ids})";
			$result = $this->m_store_promise->runSql($sql);
			return common::errorArray(0, "删除成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", "");
		}
	}
	
	/**
	 * 分页查询商家承诺
	 * @param array$page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @param string $keywords 模糊查询
	 * @return multitype:boolean |multitype:number string Ambigous <boolean, multitype:boolean >
	 */
	public function pagingPromise($page, $conditionList, $sort = null){
		$result = $this->m_store_promise->paging($page, $conditionList,$sort);
		return $result;
	}
	
}