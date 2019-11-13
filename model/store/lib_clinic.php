<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_clinic')) require "model/store/table/m_store_clinic.php";
/**
 * 提供诊所管理服务
 * @name lib_clinic.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_clinic extends base_model {
    private $m_clinic;
    
    function __construct(){
        parent::__construct();
        $this->m_clinic = new m_store_clinic();
    }
    
	/**
	 * 添加诊所
	 * @param array $goodsInfo
	 * @param array $userInfo
	 * @return array $result
	 */
	public function addClinic($goodsInfo,$userInfo){
		$addInfo = array (
				"name" => $goodsInfo['name'],
				"phone" => $goodsInfo['phone'],
				"clinicId" => $goodsInfo['clinicId'],
				"clinic_ratio" => $goodsInfo['clinic_ratio'],
				"img_url" => $goodsInfo['img_url'],
				"thumb" => $goodsInfo['thumb'],
				"address" => $goodsInfo['address'],
				"province" => $goodsInfo['province'],
				"city" => $goodsInfo['city'],
				"area" => $goodsInfo['area'],
				"uid" => $goodsInfo['uid'],
				"sort_num" => $goodsInfo['sort_num'],
				"detail_desc" => $goodsInfo['detail_desc'],
				"longitude" => $goodsInfo['longitude'],
				"latitude" => $goodsInfo['latitude'],				
				"add_time" => date ( 'Y-m-d H:i:s', time() ),
				"creator" => $userInfo['account']
		);
		try {
			$addGoodsId = $this->m_clinic->create ( $addInfo );
			if($addGoodsId == true){
				return  common::errorArray(0, "添加成功", array("cid" =>$addGoodsId));
			}else{
				return  common::errorArray(1, "添加失败,添加诊所表失败", $addGoodsId);
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
			$result = $this->m_clinic->deleteByPk($gid);
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
	public function updateClinic($conditions, $goodsInfo){
		try {
			$result = $this->m_clinic->update($conditions, $goodsInfo);
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
		$result = $this->m_clinic->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 根据id获取诊所信息
	 * @param int $gid
	 * @return array $result
	 */
	function getGoods($gid){
		$condition = array('id' => $gid);
		try {
			$result = $this->m_clinic->find($condition);
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
	 * 获取诊所信息
	 * @param  array $condition
	 * @return array $result
	 */
	function getClinicInfo($condition){
		try {
			$result = $this->m_clinic->find($condition);
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
	 * 查询满足条件的所有诊所信息
	 * @param array $condition
	 * @return array
	 */
	function findAllClinic($condition=null,$fields = null ,$limit=null){
		try{
			$result = $this->m_clinic->findAll($condition,"sort_num asc",$fields ,$limit);
			return common::errorArray(0, "查询成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "查询失败", $result);
		}
	}
	
	/**
	 * 添加商品辅图
	 * @param array $row
	 * @return array
	 */
	function addClinicImage($row){
		if(!class_exists('m_store_clinic_image')) include 'model/store/table/m_store_clinic_image.php';
		$m_clinic_image = new m_store_clinic_image();
		try {
			$result = $m_clinic_image->create($row);
			if(true == $result){
				return common::errorArray(0, "添加成功", $result);
			}else {
				return common::errorArray(1, "添加失败", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 更新商品辅图信息
	 * @param unknown_type $condition
	 * @param array $row
	 * @return array
	 */
	function updateGoodsImage($condition,$row){
		if(!class_exists('m_store_clinic_image')) include 'model/store/table/m_store_clinic_image.php';
		$m_clinic_image = new m_store_clinic_image();
		try {
			$result = $m_clinic_image->update($condition,$row);
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else {
				return common::errorArray(1, "修改失败", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 根据gid获取商品辅图列表
	 * @param int $gid
	 * @return array
	 */
	function getGoodsImageList($gid){
		if(!class_exists('m_store_clinic_image')) include 'model/store/table/m_store_clinic_image.php';
		$m_clinic_image = new m_store_clinic_image();
		$condition = array('cid' => $gid);
		try {
			$result = $m_clinic_image->findAll($condition);
			if(true == $result){
				return common::errorArray(0, "查询成功", $result);
			}else {
				return common::errorArray(1, "查询为空", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除商品附图
	 * @param array $condition
	 * @return array $result
	 */
	function deleteGoodsImage($condition){
		if(!class_exists('m_store_clinic_image')) include 'model/store/table/m_store_clinic_image.php';
		$m_clinic_image = new m_store_clinic_image();
		try {
			$result = $m_clinic_image->delete($condition);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else {
				return common::errorArray(1, "删除失败", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 批量删除诊所
	 * @param string $gids
	 * @return array $result
	 */
	function batchDelete($gids){
		try{
			$sql = "SELECT * FROM `store_clinic` WHERE id IN ({$gids})";
			$goodsResult = $this->m_clinic->findSql($sql);
			if(!$goodsResult){
				return common::errorArray(1, "查出应删除记录失败", $goodsResult);
			}
			foreach($goodsResult as $goods){
				//重置user表的user_type
				if(!class_exists('lib_user'))include "model/base/lib_user.php";
				$lib_user=new lib_user();
				$lib_user->updateUser("id in ({$goods['uid']})",array("user_type"=>0));
				
				//删除商品图片
				$imgUrl = $goods['img_url'];
				if($imgUrl) @unlink($imgUrl);//delete url
				$thumbUrl = substr($imgUrl, 0,strripos($imgUrl,'.')) . "_thumb" .  substr($imgUrl, strripos($imgUrl,'.'));
				if($imgUrl) @unlink($thumbUrl);//delete thumb	
			}
			
			//删除相关商品辅图及数据库记录
			if(!class_exists('m_store_clinic_image')) include 'model/store/table/m_store_clinic_image.php';
		    $m_clinic_image = new m_store_clinic_image();
			$sql = "SELECT * FROM `store_clinic_image` WHERE cid IN ({$gids})";
			$imageResult = $m_clinic_image->findSql($sql);
			foreach($imageResult as $image){
				if($image['img_url']) @unlink($image['img_url']);
				if($image['thumb']) @unlink($image['thumb']);
			}
			$sql = "DELETE FROM `store_clinic_image` WHERE cid IN ({$gids})";
			$imageResult = $m_clinic_image->runSql($sql);
			if(true != $imageResult) return common::errorArray(1, "删除诊所轮播图失败", $imageResult);
			//删除商品
			$sql = "DELETE FROM `store_clinic` WHERE id IN ({$gids})";
			$goodsResult = $this->m_clinic->runSql($sql);
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