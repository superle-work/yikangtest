<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_fen_deal_record')) require "model/fen/table/m_fen_deal_record.php";

/**
 * 提供平台销售统计服务
 * @name lib_deal_data.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author lay
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-12-29
 */
class lib_deal_data extends base_model{
    private $deal_record;
    
    function __construct(){
        parent::__construct();
        $this->deal_record = new m_fen_deal_record();
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
			$result = $this->deal_record->findSql($sql);
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
         SELECT SUM(a.money) AS total_price,SUM(a.self_fee) as self_fee,
         SUM(a.parent_fee) as parent_fee,SUM(a.grand_fee) as grand_fee,SUM(a.self_fee)+SUM(a.parent_fee)+SUM(a.grand_fee) as total_fee,a.add_date 
         FROM (SELECT money,self_fee,parent_fee,grand_fee,date_format(add_time,'%Y-%m-%d') AS add_date 
         FROM fen_deal_record WHERE add_time >= '{$from}' AND add_time <= '{$to}') AS a 
         GROUP BY a.add_date
EOF;
			$result = $this->deal_record->findSql($sql);
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
			if(null != $state && '' != $state){
				if($state == 0 || $state == '0'){
					$sql = "select module,money from fen_deal_record";
				}else{
					$sql = "select self_fee,parent_fee,grand_fee as total_money from fen_deal_record";
				}
			}else{
    			return false;
			}
			try{
				$result = $this->deal_record->findSql($sql);
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
(select IFNULL(sum(money),0) as total_money from fen_deal_record)
union
(select IFNULL(sum(money),0) as total_money from fen_deal_record where  add_time >= '{$lowDate}' and add_time <= '{$upDate}')
EOF;
			    $result = $this->deal_record->findSql($sql);
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
				$result = $this->deal_record->findSql($sql);
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
		    $result = $this->deal_record->findAll($conditions, $sort);
		    return $result;
		}
		
		/**
		 * 更新订单商品
		 * @param array $conditions
		 * @param string $row
		 * @return array $result
		 */
		function updataOrder($conditions, $row){
		    $result = $this->deal_record->update($conditions, $row);
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