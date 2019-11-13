<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_deal_data')) require 'model/fen/lib_deal_data.php';
/**
 * 平台销售额统计
 * @name store_data.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author lay
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-12-29
 */
class fen_data extends admin_controller{
    private $lib_deal_data;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	    $this->lib_deal_data = new lib_deal_data();
	}
	
	/**
	 * 销售额统计页面
	 */
	function saleDataList(){
		$this->getSetMenu($this);
		$result = $this->lib_deal_data->getKeyIndexOfSale();
		$keyIndex['totalMoney'] = $result['data'][0]['total_money'];//总销售额
		$keyIndex['todayMoney'] = $result['data'][1]['total_money'];//今日销售额
		$this->keyIndex = $keyIndex;
		$this->log(__CLASS__, __FUNCTION__, "销售额统计页面", 1, 'view');//日志记录
		$this->display("../template/admin/{$this->theme}/fen/page/data/saleDataList.html");
	}
	
	/**
	 * 订单量统计页面
	 */
	function orderDataList(){
		$this->getSetMenu($this);
		$result = $this->lib_deal_data->getKeyIndexOfOrder();
		$keyIndex['totalOrderCount'] = $result['data'][0]['total_count'];//总订单数
		$keyIndex['todayOrderCount'] = $result['data'][1]['total_count'];//今日订单数
		$this->keyIndex = $keyIndex;
		$this->log(__CLASS__, __FUNCTION__, "订单量统计页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/data/orderDataList.html");
	}
	
	/**
     * 购物车统计页面
     */
    function cartDataList(){
		$this->getSetMenu($this);
        $result = $this->lib_cart_data->getKeyIndexOfCart();
        $keyIndex['totalCateCount'] = $result['data'][0][0]['total_count'];//商品类别数
        $keyIndex['todayCateCount'] = $result['data'][0][1]['total_count'];//今日商品类别数
        $keyIndex['totalQuantityCount'] = $result['data'][1][0]['total_count'];//商品数
        $keyIndex['todayQuantityCount'] = $result['data'][1][1]['total_count'];//今日商品数
        $this->keyIndex = $keyIndex;
        $this->log(__CLASS__, __FUNCTION__, "购物车统计页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/fen/page/data/cartDataList.html");
    }
    
    /**
     * 获取购物车商品排行
     */
    function getCartRank(){
    	//购物车商品重复次数排行
    	$cartDataResult = $this->lib_cart_data ->getTop(10,"count DESC");
    	$result['goodsTimesList'] = $cartDataResult['data'];
    	//购物车商品数量排行
    	$cartDataResult = $this->lib_cart_data ->getTop(10,"total_count DESC");
    	$result['goodsCountList'] = $cartDataResult['data'];

    	echo json_encode($result);
    }
    
    /**
     * 按日期获取商品类别和数量变化数据
     */
    function getCartChart(){
    	$dayCount = $this->spArgs('dayCount');
    	if(null == $dayCount || '' == $dayCount){
    		$dayCount = 6;
    	}else{
    		$dayCount = $dayCount - 1;
    	}
    	if(!class_exists('UtilDate')) include 'include/UtilDate.php';
    	$dateList = $utilDate->getSerialDate($dayCount+1,'asc');
    	$lastDate = date('Y-m-d', strtotime("-{$dayCount} days"));
    	$result = $this->lib_cart_data->getChartData($lastDate,date('Y-m-d', strtotime('1 days')));
    	if($result['errorCode'] == 0 || $result['errorCode'] == 2){
    		if(count($dateList) != count($result['data'])){//补全数据
    			for ($i = 0;$i < count($dateList);$i++){
    				$valueResult = $this->dateInData($dateList[$i], $result['data']);
    				if($valueResult['result']){
    					$dateList[$i] = $result['data'][$valueResult['sub']];
    				}else{
    					$dateList[$i] = array(
    								'count' =>0,
    								'quantity' =>0,
    								'add_date' =>$dateList[$i]
    							);
    				}
    			}
    			$result['data'] = $dateList;
    		}
    		$xList = array();
    		$yList[0] = array('name'=>'商品分类数量','data'=>array(),"color"=>"#7cb5ec");
    		$yList[1] = array('name'=>'商品个数数量','data'=>array(),"color"=>"#F7A35C");
    		foreach ($result['data'] as &$per){
    			$per['count'] = (int)($per['count']);
    			$per['quantity'] = (int)($per['quantity']);
    			array_push($xList, $per['add_date']);
    			array_push($yList[0]['data'], $per['count']);
    			array_push($yList[1]['data'], $per['quantity']);
    		}
    		$result['data'] = array('xData'=>$xList,'yData'=>$yList);
    	}
    	echo json_encode($result);
    }
    
    /**
     * 按日期获取不同状态订单数量变化数据
     */
    function getOrderData(){
    	$dayCount = $this->spArgs('dayCount');
    	if(null == $dayCount || '' == $dayCount){
    		$dayCount = 6;
    	}else{
    		$dayCount = $dayCount - 1;
    	}
    	if(!class_exists('UtilDate')) include_once 'include/UtilDate.php';
    	$dateList = $utilDate->getSerialDate($dayCount + 1,'asc');
    	$lastDate = date('Y-m-d', strtotime("-{$dayCount} days"));
    	$result = $this->lib_deal_data->getOrderData($lastDate,date('Y-m-d', strtotime('1 days')));
    	if($result['errorCode'] == 0 || $result['errorCode'] == 2){
    		if(count($dateList) != count($result['data'])){//补全数据
    			for ($i = 0;$i < count($dateList);$i++){
    				$valueResult = $this->dateInData($dateList[$i], $result['data']);
    				if($valueResult['result']){
    					$dateList[$i] = $result['data'][$valueResult['sub']];
    				}else{
    					$dateList[$i] = array(
    							'total_count' =>0,
    							'pay_count' =>0,
    							'send_count' =>0,
    							'success_count' =>0,
    							'add_date' =>$dateList[$i]
    					);
    				}
    			}
    			$result['data'] = $dateList;
    		}
    		$xList = array();
    		$yList[0] = array('name'=>'订单总数','data'=>array(),"color"=>"#7cb5ec");
    		$yList[1] = array('name'=>'未付款总数','data'=>array(),"color"=>"#F7A35C");
    		$yList[2] = array('name'=>'已付款总数','data'=>array(),"color"=>"pink");
    		$yList[3] = array('name'=>'成功总数','data'=>array(),"color"=>"green");
    		foreach ($result['data'] as &$per){
    			$per['total_count'] = (int)($per['total_count']);
    			$per['pay_count'] = (int)($per['pay_count']);
    			$per['send_count'] = (int)($per['send_count']);
    			$per['success_count'] = (int)($per['success_count']);
    			array_push($xList, $per['add_date']);
    			array_push($yList[0]['data'], $per['total_count']);
    			array_push($yList[1]['data'], $per['pay_count']);
    			array_push($yList[2]['data'], $per['send_count']);
    			array_push($yList[3]['data'], $per['success_count']);
    		}
    		$result['data'] = array('xData'=>$xList,'yData'=>$yList);
    	}
    	echo json_encode($result);
    }
    
    /**
     * 按日期获取不同状态销售额变化数据
     */
    function getQuantityData(){
    	$dayCount = $this->spArgs('dayCount');
    	if(null == $dayCount || '' == $dayCount){
    		$dayCount = 6;
    	}else{
    		$dayCount = $dayCount - 1;
    	}
    	if(!class_exists('UtilDate')) include 'include/UtilDate.php';
    	$utilDate = new UtilDate();
    	$dateList = $utilDate->getSerialDate($dayCount + 1,'asc');
    	$lastDate = date('Y-m-d', strtotime("-{$dayCount} days"));
    	$result = $this->lib_deal_data->getQuantityData($lastDate,date('Y-m-d', strtotime('1 days')));
    	if($result['errorCode'] == 0 || $result['errorCode'] == 2){
    		if(count($dateList) != count($result['data'])){//补全数据
    			for ($i = 0;$i < count($dateList);$i++){
    				$valueResult = $this->dateInData($dateList[$i], $result['data']);
    				if($valueResult['result']){
    					$dateList[$i] = $result['data'][$valueResult['sub']];
    				}else{
    					$dateList[$i] = array(
    							'total_price' =>0,
    							'self_fee' =>0,
    							'parent_fee' =>0,
    							'grand_fee' =>0,
    					        'total_fee' => 0,
    							'add_date' =>$dateList[$i]
    					);
    				}
    			}
    			$result['data'] = $dateList;
    		}
    		$xList = array();
    		$yList[0] = array('name'=>'销售总额','data'=>array(),"color"=>"#7cb5ec");
    		$yList[1] = array('name'=>'一级佣金','data'=>array(),"color"=>"#F7A35C");
    		$yList[2] = array('name'=>'二级佣金','data'=>array(),"color"=>"pink");
    		$yList[3] = array('name'=>'三级佣金','data'=>array(),"color"=>"green");
    		$yList[4] = array('name'=>'总佣金','data'=>array(),"color"=>"purple");
    		foreach ($result['data'] as &$per){
    			$per['total_price'] = (int)($per['total_price']);
    			$per['self_fee'] = (int)($per['self_fee']);
    			$per['parent_fee'] = (int)($per['parent_fee']);
    			$per['grand_fee'] = (int)($per['grand_fee']);
    			$per['total_fee'] = (int)$per['total_fee'];
    			array_push($xList, $per['add_date']);
    			array_push($yList[0]['data'], $per['total_price']);
    			array_push($yList[1]['data'], $per['self_fee']);
    			array_push($yList[2]['data'], $per['parent_fee']);
    			array_push($yList[3]['data'], $per['grand_fee']);
    			array_push($yList[4]['data'], $per['total_fee']);
    		}
    		$result['data'] = array('xData'=>$xList,'yData'=>$yList);
    	}
    	echo json_encode($result);
    }
    
    /**
     * 获取销量饼图数据
     */
    function getOrderPieData(){
    	$state = $this->spArgs('state');//0商城销售占比 1发放佣金比例
    	$result = $this->lib_deal_data->getSaleRateDate($state);
    	if($result['errorCode'] == 0){
    		$dataList = array();
    		foreach ($row['data'] as &$per){
    			$per['rate'] = round($total_price / $sum_money ,2);
    			array_push($dataList, array("{$per['name']}",$per['rate']));
    		}
    		$result['data'] = $dataList;
    	}
    	echo json_encode($result);
    }
    
    /**
     * 判断日期在不在返回数据结果中
     * @param string $date
     * @param array $data
     * @return array
     */
    private function dateInData($date,$data){
    	for($i = 0;$i < count($data);$i++){
    		if($date == $data[$i]['add_date']){
    			return array('result'=>true,'sub'=>$i);
    		}
    	}
    	return array('result'=>false,'sub'=>null);
    }
    
    /**
     * 优秀分销商统计图
     */
    function getDealBarData(){
        if(!class_exists('lib_deal_record')) include 'model/fen/lib_deal_record.php';
        $lib_deal_record = new lib_deal_record();
        $info = $this->getArgsList($this,array(state,date));
        $result = $lib_deal_record->dealBarChartData($info);
        echo json_encode($result);
    }
}