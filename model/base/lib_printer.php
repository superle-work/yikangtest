<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_clinic')) require "model/base/table/m_base_printer.php";
/**
 * 提供打印机管理服务
 * @name lib_printer.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_printer extends base_model {
    private $m_printer;
    
    function __construct(){
        parent::__construct();
        $this->m_printer = new m_base_printer();
    }
    
	/**
	 * 添加打印机
	 * @param array $goodsInfo
	 * @return array $result
	 */
	public function addPrinter($goodsInfo){
		$addInfo = array (
				"num" => $goodsInfo['num'],
				"province" => $goodsInfo['province'],
				"city" => $goodsInfo['city'],
				"area" => $goodsInfo['area']
		);
		try {
			$addGoodsId = $this->m_printer->create ( $addInfo );
			if($addGoodsId == true){
				return  common::errorArray(0, "添加成功", array("cid" =>$addGoodsId));
			}else{
				return  common::errorArray(1, "添加失败,添加打印机表失败", $addGoodsId);
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(3, "添加失败,操作数据库失败", $ex);
		}
	}

	
	/**
	 * 删除打印机
	 * @param int $gid
	 * @return array $result
	 */
	public function deleteComplete($gid){
		try {
			$result = $this->m_printer->deleteByPk($gid);
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
	 * 更新打印机
	 * @param array $conditions
	 * @param array $goodsInfo
	 * @return array $result
	 */
	public function updatePrinter($conditions, $goodsInfo){
		try {
			$result = $this->m_printer->update($conditions, $goodsInfo);
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
	 * 分页查询打印机
	 * @param array$page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @param string $keywords 模糊查询
	 * @param datetime $createTime 创建时间
	 * @return array $result
	 */
	public function pagingGoods($page, $conditionList, $sort = null, $keywords = null,$createTime = null){
		$result = $this->m_printer->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 根据id获取打印机信息
	 * @param int $gid
	 * @return array $result
	 */
	function getPrinter($gid){
		$condition = array('id' => $gid);
		try {
			$result = $this->m_printer->find($condition);
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
	 * 获取打印机信息
	 * @param  array $condition
	 * @return array $result
	 */
	function getPrinterInfo($condition){
		try {
			$result = $this->m_printer->find($condition);
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
	 * 查询满足条件的所有打印机信息
	 * @param array $condition
	 * @return array
	 */
	function findAllPrinter($condition=null,$fields = null ,$limit=null){
		try{
			$result = $this->m_printer->findAll($condition,"sort_num asc",$fields ,$limit);
			return common::errorArray(0, "查询成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "查询失败", $result);
		}
	}
	
	/**
	 * 批量删除打印机
	 * @param string $gids
	 * @return array $result
	 */
	function batchDelete($gids){
		try{
			$sql = "SELECT * FROM `base_printer` WHERE id IN ({$gids})";
			$goodsResult = $this->m_printer->findSql($sql);
			if(!$goodsResult){
				return common::errorArray(1, "查出应删除记录失败", $goodsResult);
			}
			//删除商品
			$sql = "DELETE FROM `base_printer` WHERE id IN ({$gids})";
			$goodsResult = $this->m_printer->runSql($sql);
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