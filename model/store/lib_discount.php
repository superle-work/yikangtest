<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_agent')) require "model/store/table/m_store_discount.php";
/**
 * 提供折扣管理服务
 * @name discount.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_discount extends base_model {
    private $m_discount;
    
    function __construct(){
        parent::__construct();
        $this->m_discount = new m_store_discount();
    }
    
	/**
	 * 添加折扣
	 * @param array $goodsInfo
	 * @param array $userInfo
	 * @return array $result
	 */
    public function addDiscount($goodsInfo){
        $addInfo = array (
            "user_type" => $goodsInfo['user_type'],
            "discount" => $goodsInfo['discount'],
            "blood_fee" => $goodsInfo['blood_fee'],
            "transport_fee" => $goodsInfo['transport_fee']
        );
        try {
            $addGoodsId = $this->m_discount->create($addInfo);
            if($addGoodsId == true){
                return  common::errorArray(0, "添加成功", array("cid" =>$addGoodsId));
            }else{
                return  common::errorArray(1, "添加失败,添加折扣表失败", $addGoodsId);
            }
        } catch (Exception $ex) {
            $this->errorLog(__CLASS__, __FUNCTION__, $ex);
            return  common::errorArray(3, "添加失败,操作数据库失败", $ex);
        }
    }

	
	/**
	 * 删除折扣
	 * @param int $gid
	 * @return array $result
	 */
	public function deleteDiscount($gid){
		try {
			$result = $this->m_discount->deleteByPk($gid);
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
	 * 更新折扣
	 * @param array $conditions
	 * @param array $goodsInfo
	 * @return array $result
	 */
	public function updateDiscount($conditions, $goodsInfo){
		try {
			$result = $this->m_discount->update($conditions, $goodsInfo);
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
	 * 分页查询折扣
	 * @param array$page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @param string $keywords 模糊查询
	 * @param datetime $createTime 创建时间
	 * @return array $result
	 */
	public function pagingDiscount($page, $conditionList, $sort = null, $keywords = null,$createTime = null){
		$result = $this->m_discount->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 根据id获取折扣信息
	 * @param int $id
	 * @return array $result
	 */
	function getDiscount($id){
		$condition = array('id' => $id);
		try {
			$result = $this->m_discount->find($condition);
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
	 * 获取折扣信息
	 * @param array $condition
	 * @return array $result
	 */
	function getDiscountInfo($condition){
		try {
			$result = $this->m_discount->find($condition);
			if(true == $result){
				return common::errorArray(0, "查询成功", $result);
			}else {
				return common::errorArray(1, "查询失败", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查询满足条件的所有折扣信息
	 * @param array $condition
	 * @return array
	 */
	function findAllDiscount($condition=null,$fields = null ,$limit=null){
		try{
			$result = $this->m_discount->findAll($condition,$fields ,$limit);
			return common::errorArray(0, "查询成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "查询失败", $result);
		}
	}
	
	/**
	 * 批量删除折扣
	 * @param string $gids
	 * @return array $result
	 */
	function batchDelete($gids){
		try{		
			//删除代理
			$sql = "DELETE FROM `store_discount` WHERE id IN ({$gids})";
			$goodsResult = $this->m_discount->runSql($sql);
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