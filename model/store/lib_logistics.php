<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_logistics')) require "model/store/table/m_store_logistics.php";
/**
 * 提供物流中心管理服务
 * @name lib_logistics.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_logistics extends base_model {
    private $m_logistics;
    
    function __construct(){
        parent::__construct();
        $this->m_logistics = new m_store_logistics();
    }
    
	/**
	 * 添加物流中心
	 * @param array $goodsInfo
	 * @return array $result
	 */
	public function addLogistics($goodsInfo){
		$addInfo = array (
				"nick_name" => $goodsInfo['nick_name'],
				"phone" => $goodsInfo['phone'],

				"user_id" => $goodsInfo['user_id'],

				"head_img" => $goodsInfo['head_img'],		
				"province" => $goodsInfo['province'],
				"city" => $goodsInfo['city'],
				"area" => $goodsInfo['area'],
				"add_time" => date ( 'Y-m-d H:i:s', strtotime('+8hour') ),
		);
		try {
			$addGoodsId = $this->m_logistics->create ( $addInfo );
			if($addGoodsId == true){
				return  common::errorArray(0, "添加成功", array("lid" =>$addGoodsId));
			}else{
				return  common::errorArray(1, "添加失败,添加物流中心表失败", $addGoodsId);
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(3, "添加失败,操作数据库失败", $ex);
		}
	}

	
	/**
	 * 删除商品
	 * @param int $gid
	 * @return array $result
	 */
	public function deleteComplete($gid){
		try {
			$result = $this->m_logistics->deleteByPk($gid);
			if(!$result){
				return common::errorArray(1, "删除失败", $result);
			}
			return common::errorArray(0, "删除成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 更新商品
	 * @param array $conditions
	 * @param array $goodsInfo
	 * @return array $result
	 */
	public function updateLogistics($conditions, $goodsInfo){
		try {
			$result = $this->m_logistics->update($conditions, $goodsInfo);
			if( $result == true){
				return common::errorArray(0, "更新成功", $result);
			}else{
				return common::errorArray(1, "更新失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 分页查询商品
	 * @param array$page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @param string $keywords 模糊查询
	 * @param datetime $createTime 创建时间
	 * @return array $result
	 */
	public function pagingGoods($page, $conditionList, $sort = null, $keywords = null,$createTime = null){
		$result = $this->m_logistics->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 根据id获取物流中心信息
	 * @param int $gid
	 * @return array $result
	 */
	function getGoods($gid){
		$condition = array('id' => $gid);
		try {
			$result = $this->m_logistics->find($condition);
			if(true == $result){
				$goodsInfo = $result;
				return common::errorArray(0, "查询成功", $goodsInfo);
			}else {
				return common::errorArray(1, "查询失败", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查询满足条件的所有物流中心信息
	 * @param array $condition
	 * @return array
	 */
	function findAllLogistics($condition=null,$fields = null ,$limit=null){
		try{
			$result = $this->m_logistics->findAll($condition,$fields ,$limit);
			return common::errorArray(0, "查询成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "查询失败", $result);
		}
	}
	
	/**
	 * 批量删除物流中心
	 * @param string $gids
	 * @return array $result
	 */
	function batchDelete($gids){
		try{
			//删除数据库记录
			$sql = "DELETE FROM `store_logistic` WHERE id IN ({$gids})";
			$goodsResult = $this->m_logistics->runSql($sql);
			if(true != $goodsResult){
				return common::errorArray(1, "删除失败", $goodsResult);
			}
			return common::errorArray(0, "删除成功", $goodsResult);
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
}