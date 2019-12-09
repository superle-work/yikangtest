<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_order')) require "model/store/table/m_store_order.php";
if(!class_exists('m_store_order_goods')) require "model/store/table/m_store_order_goods.php";

if(!class_exists('PHPExcel')) require 'include/PHPExcel.php';
if(!class_exists('Excel2007')) require 'include/PHPExcel/Reader/Excel2007.php';
if(!class_exists('Excel5')) require 'include/PHPExcel/Reader/Excel5.php';
if(!class_exists('IOFactory')) include 'include/PHPExcel/IOFactory.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
/**
 * 提供订单管理服务
 * @name lib_order.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_order extends base_model {
	private $orderName;
	private $orderDesc;
	private $m_order;
	private $m_order_goods;
	
	function __construct(){
	    parent::__construct();
	    $this->m_order = new m_store_order();
	    $this->m_order_goods = new m_store_order_goods();
	}
	
	/**
	 * 提交订单
	 * @param array $userInfo 用户信息 账号和昵称
	 * @param string $gids 商品ids
	 * @param string $counts 商品个数
	 * @param string $cgids 购物车ids

	 * @param string $total_price 总价格

	 * @param string $checkMember_id 检查人员信息id
	 * @param string $clinicID 推荐诊所ID(不是诊所表的主键id)
	 * @return array $result 
	 */

	public function addOrder($userInfo,$gids,$counts,$cgids,$total_price,$checkMember_id,$clinicID,$pay_method =0){

		if(!class_exists('lib_goods')) include 'model/store/lib_goods.php';
	    $lib_goods = new lib_goods();
		$goodsResult = $lib_goods->findGoodsIn($gids);
		$goodsCountList = explode(',', $counts);
		
		for($i = 0;$i < count($goodsCountList);$i++){
			$goodsResult['data'][$i]['goods_count'] = $goodsCountList[$i];
		}
		$rawGoodsList = $goodsResult['data'];//订单商品列表
		$goodsList = $this->getGoodsList($rawGoodsList);
		//计算后原价
		$totalPrice = $total_price;
		
		$lib_config = new UtilConfig('store_config');
		$configInfoResult = $lib_config->findConfigKeyValue();

		$base_config = new UtilConfig('base_config');
		$baseconfig = $base_config->findConfigKeyValue();
		
		//获取用户信息(手机号)
		if(!class_exists('lib_user')) include 'model/base/lib_user.php';
	    $lib_user = new lib_user();
		$userResult=$lib_user->findUser(array('id'=>$userInfo['id']));
		$phone=$userResult['data']['phone'];
		
		//体检人员信息
		if(!class_exists('lib_check_member')) include 'model/store/lib_check_member.php';
		$lib_check_member = new lib_check_member();
		$memInfo=$lib_check_member -> getGoods($checkMember_id);
		$member_info = json_encode($memInfo['data']);
		
		
		$addInfo = array (
				"order_num" => $this->generateOrderNum(),
				"account" => $userInfo['account'],
				"nick_name" => $userInfo['nick_name'],
	            "head_img_url" => $userInfo['head_img_url'], 
				"total_price" => $totalPrice,
				"goods_list" => json_encode($goodsList),
				"add_time" => date ('Y-m-d H:i:s', time ()),
				"phone"=>$phone,
				"checkMember_id"=>$checkMember_id,
				"member_info"=>$member_info,
				"clinicID"=>$clinicID,
		        "state" => 0,
		        "pay_method" => $pay_method,
		);
		try {
			//添加订单
			$addOrderId = $this->m_order->create ( $addInfo );
			if($addOrderId == true){
				//添加订单商品
				if(!class_exists('lib_cart')) include 'model/store/lib_cart.php';
				$lib_cart  = new lib_cart();
				foreach($goodsList as &$row){
					$row['oid'] = $addOrderId;
					$addOgId = $this->m_order_goods->create ( $row );
				}
				if($cgids){//订单提交成功后删除购物车商品
					$res = $lib_cart->delCartGoodsByIds($cgids);
				}
				$data['addOrderId'] = $addOrderId;
				$data['state'] = 0;
				return  common::errorArray(0, "添加成功", $data);
			}else{
				return  common::errorArray(1, "添加失败,添加订单表失败", $data);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "添加失败,操作数据库失败", $ex);
		}
	}
	
	/**
	 * 获取商品的子表
	 * @param array $rawGoodsList
	 * @return array $goodsList
	 */
	private function getGoodsList($rawGoodsList){
		$goodsList = array();
		foreach ($rawGoodsList as $rawGoods){
			$goods['gid'] = $rawGoods['id'];
			$goods['goods_name'] = $rawGoods['name'];
			$goods['count'] = $rawGoods['goods_count'];
			$goods['thumb'] = $rawGoods['thumb'];
			$goods['price'] = $rawGoods['price'];
			$goods['sample_vessel'] = $rawGoods['sample_vessel'];
			array_push($goodsList, $goods);
		}
		return $goodsList;
	}
	
	/**
	 * 判断订单是否可以删除
	 * @param string $oid
	 * @return array $result
	 */
	public function isOrderCanDel($oid){
		//交易成功和交易关闭才可以删除
		$result = $this->getOrderState($oid);
		$orderState = $result['data'];
		
		if($orderState == 4 || $orderState == 5){
			return common::errorArray(0, "可以删除", $orderState);
		}else{
			return common::errorArray(1, "不可删除", $orderState);
		}
	}
	
	/**
	 * 删除订单 真删
	 * @param string $id 主键id
	 * @return array $result
	 */
	public function deleteOrder($pk){
		try {
			//假删,改变is_show的值
			$sql="update store_order set is_show=0 where id={$pk}";
			$result = $this->m_order->runSql($sql);

			// $result = $this->m_order->deleteByPk($pk);
			//删除ordergoods
			$this->m_order_goods->delete(array('oid' => $pk));
			if($result == true){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除为空", array());
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(2, "操作数据库失败", $ex);
		}
	}
	
	/**
	 * 批量删除订单
	 * @param string $oids
	 * @return $result
	 */
	public function batchDeleteOrder($oids){
		try{
			//假删,改变is_show的值
			$sql = "update store_order set is_show=0 WHERE id IN ({$oids})";

			//$sql = "DELETE FROM store_order WHERE id IN ({$oids})";
			$result = $this->m_order->runSql($sql);
			if($result == true){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除为空", array());
			}
		}catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(2, "操作数据库失败", $ex);
		}
	}

	/**
	 * 获取订单状态
	 * @param string $oid
	 * @return array $result
	 * 0未付款 1已付款  2正在退款 3已退款  4交易关闭5交易成功 6已删除
	 */
	public function getOrderState($oid){
		$conditions = array('id'=> $oid);
		try {
			$result = $this->m_order->find($conditions,'','state');
			if($result == true){
				return common::errorArray(0, "查询成功", $result['state']);
			}else{
				return common::errorArray(1, "查询为空", array());
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(2, "操作数据库失败", array());
		}
		
	}
	
	/**
	 * 修改订单价格
	 * @param string $oid 订单id
	 * @param string $totalPrice 订单总价格
	 * @return array $result
	 */
	public function editTotalPrice($oid,$totalPrice){
		$conditions = array(
				'id' => $oid
		);
		$row = array(
				'totalprice' => $totalPrice
		);
		return $this->updateOrder($conditions, $row);
	}
	
	/**
	 * 设置订单状态
	 * @param string $oid
	 * @param string $state 0未付款 1已付款  2正在退款 3已退款  4交易关闭 5交易成功6已删除
	 * @return array $result
	 */
	public function setOrderState($oid,$state){
		$conditions = array(
			'id' => $oid
		);
		$row = array(
			'state' => $state
		);
		return $this->updateOrder($conditions, $row);
	}
	
	/**
	 * 更新订单
	 * @param array $conditions
	 * @param array $row
	 * @return array $row
	 */
	public function updateOrder($conditions, $row){
		try {
			if( $this->m_order->update($conditions, $row)){
				return common::errorArray(0, "更新成功", $row);
			}else{
				return common::errorArray(1, "更新失败", $row);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(2, "数据库操作失败", $row);
		}
	}
	
	/**
	 * 生成订单号
	 * @return string
	 */
	private function generateOrderNum(){
		if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
		$lib_config = new UtilConfig('store_config');
		$config = $lib_config->findConfigKeyValue('order');
		$this->orderName = $config['data']['order_name'];
		$this->orderDesc = $config['data']['order_desc'];
		$num = $config['data']['order_bit'] - 8;
		if($num <  2){
			$num = 2;
		}else if($num > 16){
			$num = 8;
		}
		$date = date ( 'YmdHis', time () );
		switch ($num){
			case 2:
				$bit = 99;
				break;
			case 3:
				$bit = 999;
				break;
			case 4:
				$bit = 9999;
				break;
			case 5:
				$bit = 99999;
				break;
			case 6:
				$bit = 999999;
				break;
			case 7:
				$bit = 9999999;
				break;
			case 8:
				$bit = 99999999;
				break;
			default:
				$bit = 999999;
				$num = 6;
		}
		$serialNumber = sprintf("%0{$num}s", rand(1,$bit));
		return "{$config['data']['order_prefix']}" .  $serialNumber.$date;
	}
	
	/**
	 * 分页查询订单
	 * @param array$page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @param string $keywords 模糊查询
	 * @param datetime $createTime 创建时间
	 * @param datetime $endTime 完成时间
	 * @return multitype:boolean |multitype:number string Ambigous <boolean, multitype:boolean >
	 */
	public function pagingOrder($page, $conditions, $sort = null, $keywords = null,$createTime = null,$endTime = null){
		$pageIndex=$page['pageIndex'];
		$pageSize=$page['pageSize'];
		if(empty($sort)){
			$sortstr = "";
		}
		else if(is_array($sort)){
			if(count($sort) == 0){
				$sortstr = "";
			}
			else{
				$sortstr = "ORDER BY";
				foreach ($sort as $key => $sortitem){
					$sortarray[] = " {$sortitem['field']} {$sortitem['orderby']}";
				}
				$sortstr = $sortstr.join(',', $sortarray);
			}
		}
		else if(is_string($sort)){
			$sortstr = "ORDER BY ".$sort;
		}
		if(empty($conditions)&&(empty($keywords)&&(empty($createTime))&&(empty($endTime)))){
			$sql = "SELECT * FROM store_order {$sortstr}";
		}
		else {
			//循环conditions数组，生成执行查询操作的sql语句
			$where = "";
			if (is_array ( $conditions ) || is_array($keywords)|| is_array($createTime)|| is_array($endTime)) {
				$join = array ();
				if (!empty($conditions)) {
					foreach ( $conditions as $key => $condition ) {
						//检测具体条件是否为数组，如果是则拆分条件并用OR连接，两边加上括号
						if(is_array($condition)){
							$join2=array();
							foreach ($condition as $key2 => $value){
								$value=$this->escape($value);
								$join2[]="{$key} = {$value}";
							}
							$join[] = '('.join( " OR ", $join2 ).')';
						}
						else{
							//如果具体条件不是数组，则过滤字符串之后直接赋值
							$condition = $this->escape ( $condition );
							$join [] = "{$key} = {$condition}";
						}
					}
				}
	
				//模糊查询条件
				if(!empty($keywords)){
					foreach ($keywords as $key3 => $keyword){
						$join [] = $key3." LIKE CONCAT('%','$keyword','%')";
					}
				}
	
				//时间段条件
				if(!empty($createTime)){
					if ($createTime['from']!='') {
						$join [] = "date_format(add_time,'%Y-%m-%d')>='".$createTime['from']."'";
					}
					if ($createTime['to']!='') {
						$join [] = "date_format(add_time,'%Y-%m-%d')<='".$createTime['to']."'";
					}
				}
				//时间段条件
				if(!empty($endTime)){
					if ($endTime['from']!='') {
						$join [] = "date_format(end_time,'%Y-%m-%d')>='".$endTime['from']."'";
					}
					if ($endTime['to']!='') {
						$join [] = "date_format(end_time,'%Y-%m-%d')<='".$endTime['to']."'";
					}
				}
				//将所有的条件用AND连接起来
				$where = "WHERE " . join ( " AND ", $join );
			} else {
				if (null != $conditions)
					$where = "WHERE " . $conditions;
			}
			//根据$sort的值 选择要排序的字段
			$sql = "SELECT * FROM store_order {$where} {$sortstr}";
		}
		
		//查询数据库
		try {
			$result ['orderList'] = $this->m_order->spPager ( $pageIndex, $pageSize )->findSql($sql);
			$result['pageInfo']=$this->m_order->spPager()->getPager();
			$mfb_getOrderGoods =  new  m_store_order_goods();
			$count = 0;
			foreach ($result ['orderList'] as &$orderInfo){
				//decode地址信息
				//$orderInfo['address'] = json_decode($orderInfo['address']);
				//decode商品信息
				$orderInfo['goods_list'] = json_decode($result['orderList'][$count]['goods_list']);
				$result['orderList'][$count]['member_info']=json_decode($result['orderList'][$count]['member_info'],true);
				foreach ($orderInfo['goods_list'] as &$goods){
					$goods->property = json_decode($goods->property);
					//返回给页面ogid
					$orderGoodsConditions = array(
							"oid" => $orderInfo['id'],
							"gid" => $goods->gid,
					);
					$resultTemp = $mfb_getOrderGoods->find($orderGoodsConditions);
					$goods->ogid = $resultTemp['id'];
				}
				$orderInfo['goods_count'] = count($orderInfo['goods_list']);
				$count = $count + 1;
			}
		} catch (Exception $ex) {
			$result ["errorCode"] = 2;
			$result ["errorInfo"] = '数据库操作失败';
			$result ["result"] = array (
					"isSuccess" => FALSE
			);
			return $result;
		}
		//如果之后1页，手动添加分页信息
		if($result['pageInfo']==NULL){
			$result['pageInfo']['current_page']=1;
			$result['pageInfo']['first_page']=1;
			$result['pageInfo']['prev_page']=1;
			$result['pageInfo']['next_page']=1;
			$result['pageInfo']['last_page']=1;
			$result['pageInfo']['total_count']=count($result['orderList']);
			$result['pageInfo']['total_page']=1;
			$result['pageInfo']['page_size']=$pageSize;
			$result['pageInfo']['all_pages']=array(1);
		}
		if($result === FALSE) { // 如果数据库查无数据
			$errorCode = 1;
			$errorInfo = '获取分页数据失败';
			$result['isSuccess'] = FALSE;
		} else {
			$errorCode = 0;
			$errorInfo = '获取分页数据成功';
			$result['isSuccess'] = TRUE;
		}
		if(errorCode == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result);
		}
		return common::errorArray($errorCode, $errorInfo, $result);
	}
	
	public function pagingAdminOrder($page, $conditionList, $sort='add_time desc', $orList=null){
	    $result = $this->m_order->paging($page, $conditionList, $sort, $orList);
	    if($result['errorCode'] == '0'){
	        $count = 0;
	        $mfb_getOrderGoods =  new  m_store_order_goods();
	        foreach ($result ['data']['dataList'] as &$orderInfo){
	            //decode地址信息
	            $orderInfo['address'] = json_decode($orderInfo['address']);
	            //decode商品信息
	            $orderInfo['goods_list'] = json_decode($result['data']['dataList'][$count]['goods_list']);
	            foreach ($orderInfo['goods_list'] as &$goods){
	                $goods->property = json_decode($goods->property);
	                //返回给页面ogid
	                $orderGoodsConditions = array(
	                    "oid" => $orderInfo['id'],
	                    "gid" => $goods->gid,
	                    "gpid" => $goods->gpid
	                );
	                $resultTemp = $mfb_getOrderGoods->find($orderGoodsConditions);
	                $goods->ogid = $resultTemp['id'];
	            }
	            $orderInfo['goods_count'] = count($orderInfo['goods_list']);
	            $count = $count + 1;
	        }
	    }else{
	        $this->errorLog(__CLASS__, __FUNCTION__, $result);
	    }
	    return $result;
	}
	
	/**
	 * 根据id获取商品信息 只查ordergoods表
	 * @param int $gid
	 * @return array $result
	 */
	function getGoods($id){
		$condition = array('id' => $id);
		try {
			$result = $this->m_order_goods->find($condition);
			if(true == $result){
				return common::errorArray(0, "查询成功", $result);
			}else {
				return common::errorArray(1, "查询失败", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(2, "数据库操作失败", $ex);}
	}
	
	/**
	 * 获取订单列表
	 * @param array $conditions
	 * @return array $result
	 */
	function getOrderList($conditions){
		try {
			$result = $this->m_order->findAll($conditions);
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
	 * 获取对应订单状态的订单数
	 * @param String $account
	 * @param String $state
	 * @return array $result
	 */
	function getNumOfState($account,$state){
		try {
			$sql = "SELECT COUNT(*) AS num FROM store_order WHERE account = '{$account}' AND state = {$state}";
			$result = $this->m_order->findSql($sql);
			if(true == $result){
				return common::errorArray(0, "查询成功", $result[0]['num']);
			}else {
				return common::errorArray(1, "查询失败", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取订单所有商品
	 * @param array $conditions
	 * @return array $result
	 */
	function getOrderGoodsList($conditions){
		try {
			$result = $this->m_order_goods->findAll($conditions);
			foreach ($result as &$per){
				$per['property'] = json_decode($per['property']); 
			}
			return common::errorArray(0, "查询成功", $result);
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取订单信息
	 * @param array $conditions
	 * @param boolean $boolean
	 * @return array $result
	 */
	function getOrderInfo($conditions,$boolean = true){
		try {
			$result = $this->m_order->find($conditions);
			if($result){
				$result['goods_list'] = json_decode($result['goods_list'],$boolean);
				return common::errorArray(0, "查询成功", $result);
			}else{
				return common::errorArray(1, "查询为空", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取订单列表
	 * @param array $conditions
	 * @param string $sort
	 * @param string $keywords
	 * @param string $createTime
	 * @param string $endTime
	 * @return array $result
	 */
	function getAllOrders($conditions, $sort = null, $keywords = null,$createTime = null,$endTime = null){
		if(empty($sort)){
			$sortstr = "";
		}
		else if(is_array($sort)){
			if(count($sort) == 0){
				$sortstr = "";
			}
			else{
				$sortstr = "ORDER BY";
				foreach ($sort as $key => $sortitem){
					$sortarray[] = " {$sortitem['field']} {$sortitem['orderby']}";
				}
				$sortstr = $sortstr.join(',', $sortarray);
			}
		}
		else if(is_string($sort)){
			$sortstr = "ORDER BY ".$sort;
		}
		
		if(empty($conditions)&&(empty($keywords)&&(empty($createTime))&&(empty($endTime)))){
			$sql = "SELECT * FROM store_order {$sortstr}";
		}
		else {
			//循环conditions数组，生成执行查询操作的sql语句
			$where = "";
			if (is_array ( $conditions ) || is_array($keywords) || is_array($createTime) || is_array($endTime)) {
				$join = array ();
					
				if (!empty($conditions)) {
					foreach ( $conditions as $key => $condition ) {
						//检测具体条件是否为数组，如果是则拆分条件并用OR连接，两边加上括号
						if(is_array($condition)){
							$join2=array();
							foreach ($condition as $key2 => $value){
								$value=$this->escape($value);
								$join2[]="{$key} = {$value}";
							}
							$join[] = '('.join( " OR ", $join2 ).')';
						}
						else{
							//如果具体条件不是数组，则过滤字符串之后直接赋值
							$condition = $this->escape ( $condition );
							$join [] = "{$key} = {$condition}";
						}
					}
				}
		
				//模糊查询条件
				if(!empty($keywords)){
					foreach ($keywords as $key3 => $keyword){
						$join [] = $key3." LIKE CONCAT('%','$keyword','%')";
					}
				}
		
				//时间段条件
				if(!empty($createTime)){
					if ($createTime['from']!='') {
						$join [] = "date_format(add_time,'%Y-%m-%d')>='".$createTime['from']."'";
					}
					if ($createTime['to']!='') {
						$join [] = "date_format(add_time,'%Y-%m-%d')<='".$createTime['to']."'";
					}
				}
		
				//时间段条件
				if(!empty($endTime)){
					if ($endTime['from']!='') {
						$join [] = "date_format(end_time,'%Y-%m-%d')>='".$endTime['from']."'";
					}
					if ($endTime['to']!='') {
						$join [] = "date_format(end_time,'%Y-%m-%d')<='".$endTime['to']."'";
					}
				}
		
				//将所有的条件用AND连接起来
				$where = "WHERE " . join ( " AND ", $join );
			} else {
				if (null != $conditions)
					$where = "WHERE " . $conditions;
			}
			//根据$sort的值 选择要排序的字段
			$sql = "SELECT * FROM store_order {$where} {$sortstr}";
		}
		//查询数据库
		try {
			$result ['orderList'] = $this->m_order->findSql($sql);
		} catch (Exception $ex) {
			$result ["errorCode"] = 2;
			$result ["errorInfo"] = '数据库操作失败';
			$result ["result"] = array (
					"isSuccess" => FALSE
			);
			return $result;
		}
		if(errorCode == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result);
		}
		return common::errorArray(0, "获取数据成功", $result);
	}
	
	/**
	 * 导出excel
	 * @param array $oriData
	 */
	function importExcel($oriData){
		$headArr = array("用户","订单编号","总价","订单状态","采样诊所","检测医院","物流人员","支付方式","创建日期","体检者姓名","检测项目");
		$data = array();
		for($i = 0;$i < count($oriData);$i++){
			$data[$i][0] = " ".$oriData[$i]['nick_name'];
			$data[$i][1] = " ".$oriData[$i]['order_num'];
			$data[$i][2] = (float)$oriData[$i]['total_price'];
			if($oriData[$i]['state'] == 0){
				$data[$i][3] = '待付款';
			}else if($oriData[$i]['state'] == '1'){
				$data[$i][3] = '待采样';
			}else if($oriData[$i]['state'] == '2'){
				$data[$i][3] = '待送检';
			}else if($oriData[$i]['state'] == '3'){
				$data[$i][3] = '送检中';
			}else if($oriData[$i]['state'] == '4'){
				$data[$i][3] = '检测中';
			}else if($oriData[$i]['state'] ==  '5'){
				$data[$i][3] = '已完成';
			}
			
			$data[$i][4] = " ".$oriData[$i]['clinic_name'];
			$data[$i][5] = " ".$oriData[$i]['hospital_name'];
			
			$data[$i][6] = " ".$oriData[$i]['logistics_worker_name'];
			
			if($oriData[$i]['pay_method'] == 1){
				$data[$i][7] = '微信支付';
			}elseif($oriData[$i]['pay_method'] == 2){
				$data[$i][7] = '支付宝';
			}elseif($oriData[$i]['pay_method'] == 3){
				$data[$i][7] = '货到付款';
			}else{
				$data[$i][7] = '';
			}
			$data[$i][8] = $oriData[$i]['add_time'];
			$arr = json_decode($oriData[$i]['member_info'],true);
			$data[$i][9] = " ".$arr['name'];
			$data[$i][10] = " ".$oriData[$i]['checkItem'];		
		}
		$this->getExcel($headArr,$data);
	}
	
	/**
	 * 导出excel 的配置
	 * @param array $headArr
	 * @param array $data
	 */
	private function getExcel($headArr,$data){
		if(empty($data) || !is_array($data)){
			die("data must be a array");
		}
		
		$date = date("Y_m_d",time());
		$fileName = "订单_".date ( 'YmdHis', time () ).".xlsx";
	
		//创建新的PHPExcel对象
		$objPHPExcel = new PHPExcel();
		$objProps = $objPHPExcel->getProperties();
	
		//设置表头
		$key = ord("A");
		foreach($headArr as $v){
			$colum = chr($key);
			$objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
			$key += 1;
		}
	
		$column = 2;
		$objActSheet = $objPHPExcel->getActiveSheet();
		foreach($data as $key => $rows){ //行写入
			$span = ord("A");
			foreach($rows as $keyName=>$value){// 列写入
				$j = chr($span);
				$objActSheet->setCellValue($j.$column, $value);
				$span++;
			}
			$column++;
		}
	
		$fileName = iconv("utf-8", "gb2312", $fileName);
		//重命名表
		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		//设置活动单指数到第一个表,所以Excel打开这是第一个表
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		//将输出重定向到一个客户端web浏览器(Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=\"$fileName\"");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		/* if(!empty($_GET['excel'])){
			$objWriter->save('php://output'); //文件通过浏览器下载
		}else{
			
			$objWriter->save($fileName); //脚本方式运行，保存在当前目录
			LogUtil::$Log->info(1);
			return;
		} */
		exit;
	
	}
	
}