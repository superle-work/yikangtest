<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_order')) require "model/store/table/m_store_order.php";
if(!class_exists('m_store_order_goods')) require "model/store/table/m_store_order_goods.php";

/**
 * 提供订单统计服务
 * @name lib_cart_data.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_order_data extends base_model{
    private $m_order;
    private $m_order_goods;
    
    function __construct(){
        parent::__construct();
        $this->m_order = new m_store_order();
        $this->m_order_goods = new m_store_order_goods();
    }
    
	/**
	 * 按日期获取订单量变化数据
	 * @return array
	 */
	function getOrderData($from,$to){
		try{
		$sql= 
<<<EOF
SELECT f.*,IFNULL(g.success_count,0) AS success_count FROM
(
     SELECT d.*,IFNULL(e.send_count,0) AS send_count FROM 
    (
     SELECT b.add_date, IFNULL(b.total_count,0) AS total_count,IFNULL(c.pay_count,0) AS pay_count FROM
        (#所有订单
         SELECT COUNT(a.state) AS total_count,a.add_date FROM (SELECT state,date_format(add_time,'%Y-%m-%d') AS add_date FROM store_order WHERE add_time >= '{$from}' AND add_time <= '{$to}') AS a GROUP BY a.add_date
        ) AS b
        LEFT JOIN
        (#代付款订单
         SELECT COUNT(a.state) AS pay_count,a.add_date FROM (SELECT state,date_format(add_time,'%Y-%m-%d') AS add_date FROM store_order WHERE state = '0' AND add_time >= '{$from}' AND add_time <= '{$to}') AS a GROUP BY a.add_date
        ) AS c ON b.add_date = c.add_date
    ) AS d
    LEFT JOIN
    (#未发货订单
     SELECT COUNT(a.state) AS send_count,a.add_date FROM (SELECT state,date_format(add_time,'%Y-%m-%d') AS add_date FROM store_order WHERE state = '1' AND add_time >= '{$from}' AND add_time <= '{$to}') AS a GROUP BY a.add_date
    ) AS e ON d.add_date = e.add_date
)AS f
LEFT JOIN
(#交易成功的订单
 SELECT COUNT(a.state) AS success_count,a.add_date FROM (SELECT state,date_format(add_time,'%Y-%m-%d') AS add_date FROM store_order WHERE state = '5' AND add_time >= '{$from}' AND add_time <= '{$to}') AS a GROUP BY a.add_date
)as g ON f.add_date = g.add_date
EOF;
			$result = $this->m_order->findSql($sql);
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
	 * 按日期获取订单量变化数据
	 * @return array
	 */
	function getQuantityData($from,$to){
		try{
			$sql =
<<<EOF
SELECT f.*,IFNULL(g.success_total_price,0) AS success_total_price FROM
(
     SELECT d.*,IFNULL(e.send_total_price,0) AS send_total_price FROM 
    (
     SELECT b.add_date, IFNULL(b.total_price,0) AS total_price,IFNULL(c.pay_total_price,0) AS pay_total_price FROM
        (#所有订单
         SELECT SUM(a.total_price) AS total_price,a.add_date FROM (SELECT total_price,date_format(add_time,'%Y-%m-%d') AS add_date FROM store_order WHERE add_time >= '{$from}' AND add_time <= '{$to}') AS a GROUP BY a.add_date
        ) AS b
        LEFT JOIN
        (#代付款订单
         SELECT SUM(a.total_price) AS pay_total_price,a.add_date FROM (SELECT total_price,date_format(add_time,'%Y-%m-%d') AS add_date FROM store_order WHERE state = '0' AND add_time >= '{$from}' AND add_time <= '{$to}') AS a GROUP BY a.add_date
        ) AS c ON b.add_date = c.add_date
    ) AS d
    LEFT JOIN
    (#未发货订单
     SELECT SUM(a.total_price) AS send_total_price,a.add_date FROM (SELECT total_price,date_format(add_time,'%Y-%m-%d') AS add_date FROM store_order WHERE state = '1' AND add_time >= '{$from}' AND add_time <= '{$to}') AS a GROUP BY a.add_date
    ) AS e ON d.add_date = e.add_date
)AS f
LEFT JOIN
(#交易成功的订单
 SELECT SUM(a.total_price) AS success_total_price,a.add_date FROM (SELECT total_price,date_format(add_time,'%Y-%m-%d') AS add_date FROM store_order WHERE state = '5' AND add_time >= '{$from}' AND add_time <= '{$to}') AS a GROUP BY a.add_date
)as g ON f.add_date = g.add_date
EOF;
			$result = $this->m_order->findSql($sql);
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
		 * 获取商品销量类别占比
		 * @param int $state
		 * @return array $result
		 */
		function getSaleRateDate($state){
			$where = "";
			if(null != $state && '' != $state){
				if($state == 3 || $state == '3'){
					$where = "where b.state = 2 or state = 1";
				}else{
					$where = "where b.state = {$state}";
				}
			}
			try{
// 				$sql =
// 				<<<EOF
//     select d.name,count(c.gid) as cid  from
//     (
//         select a.oid,a.gid,b.state from store_order_goods as a 
//         left join store_order as b on a.oid = b.id {$where}
//     )as c left join store_category as d on d.gids = c.gid or d.gids like 'c.gid,%' or d.gids like '%,c.gid,%' or d.gids like '%,c.gid'
// EOF;
				$sql =
				<<<EOF
    select d.id,d.name,c.gid from   
    (
        select a.oid,a.gid,b.state from store_order_goods as a 
        left join store_order as b on a.oid = b.id {$where}
    )as c left join store_category as d on d.gids = c.gid 
    or d.gids like concat("%",c.gid,"%") or d.gids like concat("c.gid,%") or d.gids like concat("%,c.gid")
EOF;
				$result = $this->m_order->findSql($sql);
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
		 * 获取销售关键指标
		 * @return array $result
		 */
		function getKeyIndexOfSale(){
			$curDate = date("Y-m-d",time());
			$lowDate = "{$curDate} 00:00:00";
			$upDate = "{$curDate} 23:59:59";
			try{
				$sql =
<<<EOF
(select IFNULL(sum(total_price),0) as total_money from store_order where (state = 1 or state = 2 or state = 5))
union
(select IFNULL(sum(total_price),0) as total_money from store_order where (state = 1 or state = 2 or state = 5) and( add_time >= '{$lowDate}' and add_time <= '{$upDate}'))
EOF;
			    $result = $this->m_order->findSql($sql);
			    if(true == $result ){
			    	if(count($result) == 1){//union会去重，如果重复了，这里手动复制一个
			    		$result[1] = $result[0];
			    	}
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
		 * 获取订单量关键指标
		 * @return array $result
		 */
		function getKeyIndexOfOrder(){
			$curDate = date("Y-m-d",time());
			$lowDate = "{$curDate} 00:00:00";
			$upDate = "{$curDate} 23:59:59";
			try{
				$sql =
				<<<EOF
(select IFNULL(count(total_price),0) as total_count from store_order)
union
(select IFNULL(count(total_price),0) as total_count from store_order where ( add_time >= '{$lowDate}' and add_time <= '{$upDate}'))
				
EOF;
				$result = $this->m_order->findSql($sql);
				if(true == $result ){
					if(count($result) == 1){//union会去重，如果重复了，这里手动复制一个
						$result[1] = $result[0];
					}
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
		 * 查找订单商品
		 * @param array $conditions
		 * @param string $sort
		 * @return array $result
		 */
		function findAllOrder($conditions, $sort = null){
		    $result = $this->m_order_goods->findAll($conditions, $sort);
		    return $result;
		}
		
		/**
		 * 更新订单商品
		 * @param array $conditions
		 * @param string $row
		 * @return array $result
		 */
		function updataOrder($conditions, $row){
		    $result = $this->m_order_goods->update($conditions, $row);
		    return $result;
		}
		

		/**
		 * 查找商品
		 * @param array $conditions
		 * @param string $sort
		 * @return array $result
		 */
		function findGoods($conditions, $sort = null){
		    if(!class_exists('m_store_goods')) include 'model/store/table/m_store_goods.php';
		    $m_goods = new m_store_goods();
		    $result = $m_goods->findAll($conditions, $sort);
		    return $result;
		}
}