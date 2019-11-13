<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_news')) require "model/store/table/m_store_news.php";

/**
 * 提供模板管理服务
 * @name lib_news.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_news extends base_model{
    private $m_store_news;
    
    function __construct(){
        parent::__construct();
        $this->m_store_news = new m_store_news();
    }
    
	/**
	 * 消息添加
	 * @param array $newsInfo
	 * @return array $result
	 */
	public function addNews($newsInfo){
		try{
				$addId = $this->m_store_news->create ($newsInfo);
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
	 * 商城消息修改
	 * @param array $condition
	 * @param array $newsInfo
	 * @return array $result
	 */
	public function updateNews($condition,$newsInfo){
		try{
			$result = $this->m_store_news->update ($condition,$newsInfo );
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
	 * 获取单个商家消息
	 * @param array $conditions
	 * @return array $result
	 */
	public function findNews($conditions){
		try{
			$result = $this->m_store_news->find($conditions);
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
	 * 获取所有模板信息
	 * @param array $conditions
	 * @param string $sort
	 * @return array $result
	 */
	public function findAllNews($conditions,$sort = ''){
		try{
			$result = $this->m_store_news->findAll($conditions,$sort);
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
	 * 删除商城消息
	 * @param $conditions
	 * @return array $result
	 */
	public function deleteNews($conditions){
		try{
			$result = $this->m_store_news->delete ( $conditions);
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
	 * 批量删除消息
	 * @param string $ids 
	 * @return array $result
	 */
	public function batchDeleteNews($ids){
		try{
			$sql = "DELETE FROM `store_news` WHERE id IN ({$ids})";
			$result = $this->m_store_news->runSql($sql);
			return common::errorArray(0, "删除成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", "");
		}
	}
	
	/**
	 * 分页查询商城消息
	 * @param array$page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @param string $keywords 模糊查询
	 * @return multitype:boolean |multitype:number string Ambigous <boolean, multitype:boolean >
	 */
	public function pagingNews($page, $conditionList, $sort = null){
		$result = $this->m_store_news->paging($page, $conditionList,$sort);
		return $result;
	}
	
}