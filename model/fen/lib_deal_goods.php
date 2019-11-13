<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_fen_deal_goods')) require "model/fen/table/m_fen_deal_goods.php";

/**
 * 提供交易成功商品信息商品信息服务
 * @name lib_deal_goods.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_deal_goods extends base_model{
    private $m_deal_goods;
    public $tableConfigObj;//配置表对象
    
    function __construct(){
        parent::__construct();
        $this->m_deal_goods = new m_fen_deal_goods();
        if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
        $this->tableConfigObj = new UtilConfig('fen_config');
    }
	/**
	 * 添加订单记录
	 * @param array $dealInfo 订单记录
	 * @return array
	 */
	function addGoods($dealInfo){
		try{
			$dealInfo['add_time'] = common::getTime();
			$addId = $this->m_deal_goods->create ( $dealInfo );
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
	 * 更新交易成功商品信息记录
	 * @param array $conditions
	 * @param array $row
	 * @return array
	 */
	function updateDealGoods($conditions, $row){
		try{
			if($row['is_check']){
				$row['check_time'] = common::getTime();
			}
			$result = $this->m_deal_goods->update ($conditions,$row );
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
	 * 单个删除交易成功商品信息记录
	 * @param array $conditions
	 * @return array
	 */
	function deleteDealGoods($conditions){
		try{
			$result = $this->m_deal_goods->delete ( $conditions);
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
	 * 批量删除交易成功商品信息记录
	 * @param string $ids 如 1,2,3,4
	 * @return array
	 */
	function deleteDealGoodsBatch($ids){
		try{
			$sql = "DELETE FROM cg_deal_goods WHERE id in({$ids})";
			$result = $this->m_deal_goods->runSql($sql);
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
	 * 查看交易成功商品信息记录
	 * @param array $condition
	 * @return array
	 */
	function findDealGoods($condition){
		try{
			$result = $this->m_deal_goods->find($condition);
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
	 *分页查询交易成功商品信息记录
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return array
	 */
	function pagingDealGoods($page, $conditionList,$sort){
		$result = $this->m_deal_goods->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
}

