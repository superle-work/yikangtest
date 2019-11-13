<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_cart_goods')) require "model/store/table/m_store_cart_goods.php";

/**
 * 提供购物车管理服务
 * @name lib_cart.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_cart extends base_model {
    private $m_cart_goods;
    
    function __construct(){
        parent::__construct();
        $this->m_cart_goods = new m_store_cart_goods();
    }
    
	/**
	 * 商品添加到购物车
	 * @param string $account 用户账号
	 * @param string $gid 商品id
	 * @param string $count 商品数量
	 * @return array $result
	 */
	public function addCartGoods($account,$gid,$count){
		$cartGoodsInfo['add_time'] = common::getTime();

		$cartGoodsInfo['account'] = $account;
		
		if(!class_exists('m_store_goods')) include 'model/store/table/m_store_goods.php';
		$m_goods = new m_store_goods();
		$goods = $m_goods->find(array('id'=>$gid));
		/*
		//判断是否是多规格
		if($gpid != 0){//多规格
			if(!class_exists('m_store_goods_property')) include 'model/store/table/m_store_goods_property.php';
			$m_goods_property = new m_store_goods_property();
			$goodsProperty = $m_goods_property->find(array('id'=>$gpid));
			
			$cartGoodsInfo['property'] = $goodsProperty['property_json'];
			$cartGoodsInfo['price'] = $goodsProperty['price'];
			$cartGoodsInfo['ori_price'] = $goodsProperty['ori_price'];
			$cartGoodsInfo['add_price'] = $goodsProperty['price'];
		}else{//统一规格
			$cartGoodsInfo['property'] = '';
			$cartGoodsInfo['price'] = $goods['price'];
			$cartGoodsInfo['ori_price'] = $goods['ori_price'];
			$cartGoodsInfo['add_price'] = $goods['price'];
		}
		*/
		
		$cartGoodsInfo['gid'] = $gid;
		//$cartGoodsInfo['gpid'] = $gpid;
		$cartGoodsInfo['goods_name'] = $goods['name'];
		$cartGoodsInfo['goods_count'] = $count;
		$cartGoodsInfo['thumb'] = $goods['thumb'];
		$cartGoodsInfo['price'] = $goods['price'];
		$cartGoodsInfo['add_price'] = $goods['price'];
		$cartGoodsInfo['add_time'] = date("Y-m-d H:i:s");
		$cartGoodsInfo['type'] = $goods['good_type'];
		
		//获取该商品在购物车的数量
		//当前用户的购物车中是否存在当前待加入商品
		$addedResult = $this->isGoodsAdded($cartGoodsInfo['gid'],$account);
		if($addedResult['errorCode'] == 0){
			//存在时，更新该商品数目
			$updateCount = $addedResult['data']['goods_count'] + $cartGoodsInfo['goods_count'];
			$updateResult = $this->updateGoodCount($addedResult['data']['id'],$updateCount);
			
			if($updateResult['errorCode'] == 0){
				return common::errorArray(0, "添加到购物车成功，更新商品数目成功",
					array('id' => $addedResult['data']['id'])
				);
				
			}else{
				return common::errorArray(1, "添加到购物车失败，更新商品数目失败",
					array('count' => $updateCount)
				);
			}
		}else{//该商品为新添加商品，更新购物车商品类别数量+1
			try{
				$addResult = $this->m_cart_goods->create($cartGoodsInfo);
				if($addedResult == true){
					return common::errorArray(0, "添加到购物车成功，新增商品成功",
						array('id' => $addResult)
					);
				}else{
					return common::errorArray(1, "添加到购物车失败，新增商品失败",
						array('id' => $addResult)
					);
				}
			}catch (Exception $ex){
			    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			    return common::errorArray(1, "操作数据库失败",$ex);
			}
		}
	}
	
	/**
	 * 判断商品是否被添加到购物车
	 * @param int $cid 购物车id
	 * @param int $gid 商品id
	 * @param string $gpid 商品属性id
	 * @return array $result
	 */
	function isGoodsAdded($gid,$account){
		$conditions = array(
				'gid'=> $gid,
				'account'=>$account
		);
		try {
			$result = $this->m_cart_goods->find($conditions);
			if($result == true){
				return common::errorArray(0, "已添加过",$result);
			}else{
				return common::errorArray(1, "未添加", array());
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "操作数据库失败", $ex);
		}
	}

	/**
	 * 更改购物车商品
	 * @param array $condition 
	 * @param array $rowList 
	 * @return array $result
	 */
	public function updateCartGoods($condition,$rowList){
	    try {
	        $result = $this->m_cart_goods->update($condition,$rowList);
	        if($result == true){
	            return common::errorArray(0, "更新购物车商品成功", $result);
	        }else{
	            return common::errorArray(1, "更新购物车商品失败", array());
	        }
	    } catch (Exception $ex) {
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "操作数据库失败", $ex);
	    }
	}
	
	/**
	 * 更改购物车商品数量
	 * @param string $cgid 购物车商品表主键id
	 * @param string $goods_count 购物车数量
	 * @return array $result
	 */
	public function updateGoodCount($cgid,$goods_count){
		$conditions = array(
			'id' => $cgid
		);
		$row = array(
			'goods_count' => $goods_count
		);
		try {
			$result = $this->m_cart_goods->update($conditions,$row);
			if($result == true){
				return common::errorArray(0, "更新购物车商品数量成功", $result);
			}else{
				return common::errorArray(1, "更新购物车商品数量失败", array());
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "操作数据库失败", $ex);
		}
	}
	
	/**
	 * 删除购物车商品
	 * @param string $ids
	 * @return array $result
	 */
	function delCartGoodsByIds($ids){
		try {
			$sql = "DELETE FROM store_cart_goods WHERE id in ({$ids})";
			$result = $this->m_cart_goods->runSql($sql);
			if($result == true){
				return common::errorArray(0, "删除购物车商品成功", $result);
			}else{
				return common::errorArray(1, "删除购物车商品失败", array());
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "操作数据库失败", $ex);
		}
	}
	
	/**
	 * 通过条件删除商品
	 * @param array $conditions
	 * @return array $result
	 */
	function delCartGoodsByConditions($conditions){
	    try {
	        $result = $this->m_cart_goods->delete($conditions);
	        if($result == true){
	            return common::errorArray(0, "删除购物车商品成功", $result);
	        }else{
	            return common::errorArray(1, "删除购物车商品失败", array());
	        }
	    } catch (Exception $ex) {
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "操作数据库失败", $ex);
	    }
	}
	
	/**
	 * 查询用户购物车商品列表
	 * @param array $conditions
	 * @return array $result
	 */
	public function getCartList($conditions){
		try {
			$result['dataList'] = $this->m_cart_goods->findAll($conditions);
			$total = 0;
			/*
			foreach ($result['dataList'] as &$per){
				//$per['property'] = json_decode($per['property']);//json转对象
				//$total += $per['goods_count'];
				if(!class_exists('lib_goods')) include 'model/store/lib_goods.php';
				$lib_goods=new lib_goods();
				$goodsInfo=$lib_goods->getGoods($per['gid']);
				if($goodsInfo['data']['updown']==0){
					unset($per);
				}
			}*/
			$result['total_count'] = $total;
			if($result == true){
				return common::errorArray(0, "查询用户购物车商品列表成功", $result);
			}else{
				return common::errorArray(1, "查询用户购物车商品列表失败", array());
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "操作数据库失败", $ex);
		}
	}
	
	/**
	 * 查询用户购物车商品数量
	 * @param string $account
	 * @return array $result
	 */
	public function getCartGoodsCount($account){
		try {
			$sql = "SELECT COUNT(*) as num FROM store_cart_goods WHERE account = '{$account}'";
			$result = $this->m_cart_goods->findSql($sql);
			if($result == true){
				return common::errorArray(0, "查询用户购物车商品数量成功", $result['0']['num']);
			}else{
				return common::errorArray(1, "查询用户购物车商品数量失败", '');
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "操作数据库失败", $ex);
		}
	}
	
	/**
	 * 查询用户购物车商品信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function getCartGoods($conditions){
		try {
			$result = $this->m_cart_goods->find($conditions);
			$result['property'] = json_decode($result['property']);//json转对象
			if($result == true){
				return common::errorArray(0, "查询用户购物车商品信息成功", $result);
			}else{
				return common::errorArray(1, "查询用户购物车商品信息失败", array());
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "操作数据库失败", $ex);
		}
	}
	
	/**
	 * 获取购物车容量
	 * @return array $result
	 */
	public function getCartCapacity(){
		try {
			$result = $this->m_cart_goods->find();
			if($result == true){
				return common::errorArray(0, "购物车容量", $result['capacity']);
			}else{
				return common::errorArray(1, "购物车容量", array());
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "操作数据库失败", $ex);
		}
	}
	
	/**
	 * 获取购物车商品个数和总价
	 * @param string $account
	 * @return array $result
	 */
	public function getBottomCartInfo($account){
		try {
			$sql = "select count(1) as total_num,sum(price*goods_count) as total_money from store_cart_goods where account='{$account}'";
			$result = $this->m_cart_goods->findSql($sql);
			if($result[0]['total_num'] > 0){
				return common::errorArray(0, "购物车有商品", $result[0]);
			}else{
				return common::errorArray(0, "购物车无商品", array('total_num' => 0,'total_money' => 0));
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "操作数据库失败", $ex);
		}
	}
}