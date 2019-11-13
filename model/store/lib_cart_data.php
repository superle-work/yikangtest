<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_cart_goods')) require "model/store/table/m_store_cart_goods.php";

/**
 * 提供购物车统计服务
 * @name lib_cart_data.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_cart_data extends base_model{
    private $m_cart_goods;
    
    function __construct(){
        parent::__construct();
        $this->m_cart_goods = new m_store_cart_goods();
    }
    
	/**
	 * 获取前n个商品
	 * @param int $quantity
	 * @param string $sort
	 * @return array $result
	 */
	function getTop($quantity,$sort = 'count DESC'){
		try{
			$sort = "a.{$sort}";
			$sql = "SELECT * FROM (SELECT gid, goods_name ,price,thumb,COUNT(gid) as count , SUM(goods_count) as total_count FROM store_cart_goods group by gid LIMIT {$quantity}) AS a ORDER BY {$sort}";
			$result = $this->m_cart_goods->findSql($sql);
			if(true == $result ){
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
	 * 获取最新加入到购物车的商品
	 * @param int $quantity
	 * @return array $result
	 */
	function getNewAdd($quantity){
		try{
			$sql = "SELECT *  FROM store_cart_goods ORDER BY add_time DESC LIMIT {$quantity}";
			$result = $this->m_cart_goods->findSql($sql);
			if(true == $result ){
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
	 * 按日期获取商品类别和数量变化数据
	 * @param int $startDate
	 * @param int $endDate
	 * @return array $result
	 */
	function getChartData($startDate,$endDate){
		try{
			$sql = "SELECT COUNT(a.goods_count) AS count,SUM(a.goods_count) AS quantity,a.add_date FROM (SELECT goods_count,date_format(add_time,'%Y-%m-%d') AS add_date FROM store_cart_goods WHERE add_time >= '{$startDate}' AND add_time <= '{$endDate}') AS a GROUP BY a.add_date";
			$result = $this->m_cart_goods->findSql($sql);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(2, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取购物车关键指标
	 * @return array $result
	 */
	function getKeyIndexOfCart(){
		$curDate = date("Y-m-d",time());
		$lowDate = "{$curDate} 00:00:00";
		$upDate = "{$curDate} 23:59:59";
		try{
			$sql =
			<<<EOF
(select IFNULL(count(gid),0) as total_count from store_cart_goods)
union
(select IFNULL(count(gid),0) as total_count from store_cart_goods where ( add_time >= '{$lowDate}' and add_time <= '$upDate'))
EOF;
			$result1 = $this->m_cart_goods->findSql($sql);
			$sql =
			<<<EOF
(select IFNULL(sum(goods_count),0) as total_count from store_cart_goods)
union
(select IFNULL(sum(goods_count),0) as total_count from store_cart_goods where ( add_time >= '{$lowDate}' and add_time <= '$upDate'))
EOF;
			$result2 = $this->m_cart_goods->findSql($sql);
			if(true == $result1 && true == $result2){
    			if(count($result1) == 1){//union会去重，如果重复了，这里手动复制一个
    				$result1[1] = $result1[0];
    			}
    			if(count($result2) == 1){//union会去重，如果重复了，这里手动复制一个
    				$result2[1] = $result2[0];
    			}
    			$result[0] = $result1;
    			$result[1] = $result2;
    			return common::errorArray(0, "查找成功", $result);
    		}else{
    		    return common::errorArray(2, "查找为空", $result);
    		}
    	}catch (Exception $ex){
    	    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
    	    return common::errorArray(1, "数据库操作失败",$ex);
    	}
	}
	
}