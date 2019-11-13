<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_check_member')) require "model/store/table/m_store_check_member.php";
/**
 * 提供诊所管理服务
 * @name lib_check_member.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_check_member extends base_model {
    private $m_check_member;
    
    function __construct(){
        parent::__construct();
        $this->m_check_member = new m_store_check_member();
    }
    
	/**
	 * 添加诊所
	 * @param array $goodsInfo
	 * @param array $userInfo
	 * @return array $result
	 */
	public function addMember($goodsInfo){
		$addInfo = array (
				"name" => $goodsInfo['name'],
				"phone" => $goodsInfo['phone'],
				"sex" => $goodsInfo['sex'],
				"idCard" => $goodsInfo['idCard'],
				"uid" => $goodsInfo['uid'],
		);
		try {
			$addGoodsId = $this->m_check_member->create ( $addInfo );
			if($addGoodsId == true){
				return  common::errorArray(0, "添加成功", array("mid" =>$addGoodsId));
			}else{
				return  common::errorArray(1, "添加失败,添加检查人员信息表失败", $addGoodsId);
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
	public function deleteComplete($mid){
		try {
			$result = $this->m_check_member->deleteByPk($mid);
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
	public function updateMember($conditions, $goodsInfo){
		try {
			$result = $this->m_check_member->update($conditions, $goodsInfo);
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
		$result = $this->m_check_member->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 根据id获取信息
	 * @param int $gid
	 * @return array $result
	 */
	function getGoods($gid){
		$condition = array('id' => $gid);
		try {
			$result = $this->m_check_member->find($condition);
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
	 * 根据uid获取默认体检用户信息
	 * @param array $condition
	 * @return array $result
	 */
	function getMemberByUser($condition){
		try {
			$result = $this->m_check_member->find($condition);
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
	 * 查询满足条件的所有诊所信息
	 * @param array $condition
	 * @return array
	 */
	function findAllMember($condition=null,$fields = null ,$limit=null){
		try{
			$result = $this->m_check_member->findAll($condition,$fields ,$limit);
			return common::errorArray(0, "查询成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "查询失败", $result);
		}
	}
}