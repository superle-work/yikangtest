<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_weixin_eventkey')) require "model/weixin/table/m_weixin_eventkey.php";

/**
 * 提供微信推送事件名称管理服务
 * @name lib_weixin_eventkey.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2017-05-09
 */
class lib_weixin_eventkey extends base_model{
    private $m_weixin_eventkey;
    
    function __construct(){
        parent::__construct();
        $this->m_weixin_eventkey = new m_weixin_eventkey();
    }
    
	/**
	 * 微信推送事件名称添加
	 * @param array $addInfo
	 * @return array $result
	 */
	public function addEventKey($addInfo){
		try{
			$addId = $this->m_weixin_eventkey->create ( $addInfo );
			if($addId){
				return  common::errorArray(0, "添加成功", $addId);
			}else{
				return  common::errorArray(1, "添加失败",$addId);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}

	/**
	 * 微信推送事件名称信息修改 包括修改微信推送事件名称
	 * @param array $conditions
	 * @param array $row
	 * @return array $result
	 */
	public function updateEventKey($conditions,$row){
		try{
			$result = $this->m_weixin_eventkey->update ($conditions,$row );
			if($result){
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
	 * 获取单个微信推送事件名称信息
	 * @param array $conditions
	 * @param string $sort
	 * @param string $fields
	 * @return array $result
	 */
	public function findEventKey($conditions, $sort = null, $fields = null){
		try{
			$result = $this->m_weixin_eventkey->find($conditions,$sort,$fields);
			if($result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取多个微信推送事件名称信息
	 * @param array $conditions
	 * @param string $sort
	 * @param string $fields
	 * @param string $limit
	 * @return array $result
	 */
	public function findAllEventKey($conditions, $sort = null, $fields = null, $limit = null){
	    try{
	        $result = $this->m_weixin_eventkey->findAll($conditions, $sort, $fields, $limit);
	        if($result ){
	            return common::errorArray(0, "查找成功", $result);
	        }else{
	            return common::errorArray(1, "查找为空", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}
	
	/**
	 * 删除微信推送事件名称 真删
	 * @param array $conditions
	 * @return array $result
	 */
	public function deleteEventkey($ids,$field = 'id'){
		try{
			$result = $this->m_weixin_eventkey->delete ( "{$field} in ({$ids})");
			if($result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 分页查询微信推送事件名称
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return array $result
	 */
	public function pagingEventkey($page,$conditionList,$sort = ''){
		$result = $this->m_weixin_eventkey->paging($page,$conditionList,$sort );
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	
}