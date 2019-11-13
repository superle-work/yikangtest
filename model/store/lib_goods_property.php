<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_goods_property')) require "model/store/table/m_store_goods_property.php";

/**
 * 提供商品属性服务
 * @name lib_goods_property.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_goods_property extends base_model{
    private $m_property;
    
    function __construct(){
        parent::__construct();
        $this->m_property = new m_store_goods_property();
    }
    
	/**
	 * 添加商品属性
	 * @param array $goodsPropertyInfo
	 * @return array $result
	 */
	public function addGoodsProperty($goodsPropertyInfo){
		try{
			$addId = $this->m_property->create ( $goodsPropertyInfo );
			if($addId){
				return  common::errorArray(0, "添加成功", $addId);
			}else{
				return  common::errorArray(1, "添加失败", $addId);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 修改商品属性
	 * @param array $condition
	 * @param array $propertyInfo
	 * @return array $result
	 */
	public function updateGoodsProperty($condition,$propertyInfo){
		try{
			$result = $this->m_property->update ($condition,$propertyInfo );
			if(true == $result){
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
	 * 获取所有商品属性信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function findAllGoodsProperty($conditions){
		try{
			$result = $this->m_property->findAll($conditions);
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
	 * 获取单个商品属性信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function findGoodsProperty($conditions){
		try{
			$result = $this->m_property->find($conditions);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取单个商品属性信息 剩余的属性组合
	 * @param array $conditions
	 * @return array $result
	 */
	public function findGoodsPropertyOther($conditions){
		try{
			$sql = "SELECT * FROM store_goods_property WHERE gid = {$conditions['gid']} AND property_text like '%{$conditions['property_text']}%'";
			$result = $this->m_property->findSql($sql);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取所有商品属性信息 修改商品时进入修改页面时调用
	 * @param array $conditions
	 * @param string $sort
	 * @return array $result
	 */
	public function findAllGoodsPropertyEditOld($conditions,$sort = ''){
		try{
			$result = $this->m_property->findAll($conditions,$sort);
			//把pgids转化成list数组
			foreach ($result as &$per){
				$pgidList = explode(',', $per['pgids']);
				$propertyList = array();
				foreach ($pgidList as $pgid){
					$resultPg = $this->findPropertyGoods(array('id' => $pgid));
					$propertyList[] = array(
								'id' => $resultPg['data']['id'],
								'name' => $resultPg['data']['name'],
								'value' => $resultPg['data']['value']
							);
				}
				$propertyGroups[] = array(
						'ori_price' => $per['ori_price'],
						'price' => $per['price'],
						'inventory' => $per['inventory'],
						'id' => $per['id'],
						'propertyList' => $propertyList
				);
			}
			if(true == $result ){
				return common::errorArray(0, "查找成功", $propertyGroups);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 编辑商品时获取所有商品属性
	 * @param array $conditions
	 * @param string $sort
	 * @return array $result
	 */
	public function findAllGoodsPropertyEdit($conditions,$sort = ''){
		try{
			$result = $this->m_property->findAll($conditions,$sort);
			//把pgids转化成list数组
			foreach ($result as &$per){
				$propertyList = json_decode($per['property_json']);
				$propertyGroups[] = array(
						'ori_price' => $per['ori_price'],
						'price' => $per['price'],
						'inventory' => $per['inventory'],
						'id' => $per['id'],
						'propertyList' => $propertyList
				);
			}	
			if(true == $result ){
				return common::errorArray(0, "查找成功", $propertyGroups);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取所有商品属性信息 前台查看商品时用
	 * @param array $conditions
	 * @param string $sort
	 * @return array $result
	 */
	public function findAllGoodsPropertyFront($conditions,$sort = ''){
		try{
			$goodsPropertyList = $this->m_property->findAll($conditions,$sort);
			//确认name个数及第一个valueList
			$propertyObjList = json_decode($goodsPropertyList[0]['property_json']);
			for ($m = 0;$m < count($propertyObjList);$m++){
				$propertyList[$m]['name'] = $propertyObjList[$m]->name;
				$propertyList[$m]['valueList'][] = $propertyObjList[$m]->value;
			}
			//为valueList赋值
			for ($n = 1;$n < count($goodsPropertyList);$n++){
				$propertyObjList = json_decode($goodsPropertyList[$n]['property_json']);
				for($i = 0 ;$i < count($propertyList);$i++){
					//如果不重复则push
					if(!$this->isValueInArray($propertyObjList[$i]->value,$propertyList[$i]['valueList'])){
						array_push($propertyList[$i]['valueList'] , $propertyObjList[$i]->value);
					}
				}
			}
		return common::errorArray(0, "查找成功", $propertyList);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 判断某个值是否在数组内
	 * @param unknown $value
	 * @param array $array
	 * @return boolean
	 */
	private function isValueInArray($value,$array){
		foreach ($array as $per){
			if($value == $per){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 删除商品属性 真删
	 * @param array $conditions
	 * @return array $result
	 */
	public function deleteGoodsProperty($conditions){
		try{
			$result = $this->m_property->delete ( $conditions);
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
	 * 获取商品的属性信息
	 * @param int $gid
	 * @return array $result
	 */
	public function getPropertyData($gid){
		try{
		    $propertyList = $this->m_property->findAll (array('gid' => $gid));
			//重组数据格式
			$num = 0;
			foreach($propertyList as &$item){
			    $item['propertyList'] = json_decode($item['property_json'],true);
			    if(!$num) $num = count($item['propertyList']);
			    unset($item['property_json']);
			    unset($item['property_text']);
			}
			//将所有的属性值按0排序
			$n = 0;
			for($i = 0;$i<$num;$i ++){
			    foreach($propertyList as $value){
			        $subList[$i][$value['propertyList'][$i]['value']] = $n++;
			    }
			    $propertyAsKey = $i == 0? array_flip($subList[$i]) : array_merge($propertyAsKey,array_flip($subList[$i]));
			}
			$propertyAsKey = array_flip($propertyAsKey);//交换数组键值
			//拼接ids组成样式array(array(ids=>array(1,2,4),price,ori_price,id),array());
			foreach($propertyList as &$value){
			    $value['ids'] = array();
			    foreach($value['propertyList'] as $item_item){
			        $value['ids'][] = $propertyAsKey[$item_item['value']];
			    }
			    unset($value['propertyList']);
			}
			if($propertyList){
				return common::errorArray(0, "获取成功", $propertyList);
			}else{
				return common::errorArray(1, "获取失败", $propertyList);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}

	
}