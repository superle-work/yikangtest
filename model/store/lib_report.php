<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_report')) require "model/store/table/m_store_report.php";
/**
 * 提供订单报告管理服务
 * @name lib_report.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_report extends base_model {
    private $m_report;
    
    function __construct(){
        parent::__construct();
        $this->m_report = new m_store_report();
    }
    
	/**
	 * 添加报告
	 * @param array $goodsInfo
	 * @return array $result
	 */
	public function addReport($goodsInfo){
		$addInfo = array (
			"oid" => $goodsInfo['oid'],
			"user_id" => $goodsInfo['user_id'],
			"img_str" => $goodsInfo['img_str'],
			"report_desc" => $goodsInfo['report_desc'],				
			"add_time" => date ( 'Y-m-d H:i:s', time() ),
		);
		try {
			$addGoodsId = $this->m_report->create ( $addInfo );
			if($addGoodsId == true){
				return  common::errorArray(0, "添加成功", array("rid" =>$addGoodsId));
			}else{
				return  common::errorArray(1, "添加失败,添加报告表失败", $addGoodsId);
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
			$result = $this->m_report->deleteByPk($gid);
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
	 * 更新
	 * @param array $conditions
	 * @param array $goodsInfo
	 * @return array $result
	 */
	public function updateReport($conditions, $goodsInfo){
		try {
			$result = $this->m_report->update($conditions, $goodsInfo);
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
		$result = $this->m_report->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 根据oid获取报告信息
	 * @param int $oid
	 * @return array $result
	 */
	function getReport($oid){
		$condition = array('oid' => $oid);
		try {
			$result = $this->m_report->find($condition);
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
	 * 根据id获取报告信息
	 * @param int $id
	 * @return array $result
	 */
	function getReportInfo($id){
		$condition = array('id' => $id);
		try {
			$result = $this->m_report->find($condition);
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
	 * 查询满足条件的所有报告信息
	 * @param array $condition
	 * @return array
	 */
	function findAllReport($condition=null,$fields = null ,$limit=null){
		try{
			$result = $this->m_report->findAll($condition,$fields ,$limit);
			return common::errorArray(0, "查询成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "查询失败", $result);
		}
	}
}