<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_order')) require 'model/store/lib_order.php';

/**
 * 订单管理
 * @name store_order.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class store_order extends admin_controller{
    private $lib_order;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	    $this->lib_order = new lib_order();    
	}
	
	/**
	 * 订单列表页面
	 */
	function orderList(){
	    $this->getSetMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "订单列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/order/orderList.html");
	}
	

	/**
     * 展示打印页面
     **/
    function showBindUser(){
        //一级分类
        $this->getMenu($this);

    	$this->log(__CLASS__, __FUNCTION__, "展示打印页面", 1, 'view');

        $this->display("../template/admin/{$this->theme}/store/page/order/bindUser.html");
    }
	

	/**
	 * 订单详情页面
	 */
	function orderDetailList(){
	    $this->getMenu($this);
	    //获取订单信息
        $orderResult = $this->lib_order->getOrderInfo(array('id' => $this->spArgs('oid')));
		
		//推荐诊所clinicID
		if($orderResult['data']['clinicID']){
			if(!class_exists("lib_clinic")) include_once "model/store/lib_clinic.php";
			$lib_clinic=new lib_clinic();
			$result=$lib_clinic->getClinicInfo(array('clinicId'=>$orderResult['data']['clinicID']));
			$this->recommendClinic=$result['data']['name'];
		}
		
		//诊所信息
		if($orderResult['data']['clinic_id']){
			if(!class_exists("lib_clinic")) include_once "model/store/lib_clinic.php";
			$lib_clinic=new lib_clinic();
			$clinicInfo=$lib_clinic->getGoods($orderResult['data']['clinic_id']);
			$this->clinicInfo=$clinicInfo['data'];
		}
		
		//医院信息
		if($orderResult['data']['hospital_id']){
			if(!class_exists("lib_hospital")) include_once "model/store/lib_hospital.php";
			$lib_hospital=new lib_hospital();
			$hospitalInfo=$lib_hospital->getGoods($orderResult['data']['hospital_id']);
			$this->hospitalInfo=$hospitalInfo['data'];
		}
		
		if(!class_exists(UtilConfig)) include_once "include/UtilConfig.php";
		$lib_config = new UtilConfig('store_config');
		$config = $lib_config->findConfigKeyValue();
		$this->config = $config['data'];
        $this->log(__CLASS__, __FUNCTION__, "订单详情页面", 1, 'view');
        $this->orderInfo = $orderResult['data'];
		$this->display("../template/admin/{$this->theme}/store/page/order/orderDetailList.html");
	}


	/**
	 *打印机打印订单
	 *
	 */
  	function printer(){
	  		$data=$this->spArgs();
	  		// dump($data);
	  		//查配置信息
			if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
			$lib_config = new UtilConfig('store_config');
			$configInfoResult = $lib_config->findConfigKeyValue();


			include_once 'include/PrintTicket.php';
			$printTicket = new PrintTicket();
			if(!class_exists('m_store_order')) include 'model/store/table/m_store_order.php';
	    
		    $m_store_order=new m_store_order();

			$orderInfoResult['data']=$m_store_order->find(array('id'=>$data['id']));
			$orderInfoResult['data']['goods_list']=json_decode($orderInfoResult['data']['goods_list'],true);
			$orderInfoResult['data']['member_info']=json_decode($orderInfoResult['data']['member_info'],true);
			// if(!class_exists("m_store_hospital")) include_once "model/store/table/m_store_hospital.php";
			if(!class_exists("m_store_clinic")) include_once "model/store/table/m_store_clinic.php";
			// if(!class_exists("lib_clinic")) include_once "model/store/lib_clinic.php";
			$m_store_clinic=new m_store_clinic();
			// $m_store_hospital=new m_store_hospital();
			if ($orderInfoResult['data']['clinic_id']) {
				$a=$m_store_clinic->find(array('id'=>$orderInfoResult['data']['clinic_id']));
				$orderInfoResult['data']['clinic_id']=$a['name'];
			}
			// dump($orderInfoResult['data']);
			if ($orderInfoResult['data']['clinicID']) {
				$b=$m_store_clinic->find(array('id'=>$orderInfoResult['data']['clinicID']));
				$orderInfoResult['data']['clinicID']=$b['name'];
			}			


			// dump($orderInfoResult['data']);die;
			$mesageInfo = array(
				'ticket_title'=>$configInfoResult['data']['ticket_title'],
				'num'=>time(),
				'order_num'=>$orderInfoResult['data']['order_num'],
				'time'=>$orderInfoResult['data']['add_time'],
				'name'=>$orderInfoResult['data']['member_info']['name'],
				'sex'=>$orderInfoResult['data']['member_info']['sex'],
				'idCard'=>$orderInfoResult['data']['member_info']['idCard'],
				'total_price'=>$orderInfoResult['data']['total_price'],
				'goodsList'=>$orderInfoResult['data']['goods_list'],
				'clinic_name'=>$orderInfoResult['data']['clinic_name'],
				'clinicID'=>$orderInfoResult['data']['clinicID'],
				'clinic_name'=>$orderInfoResult['data']['clinic_id'],
				'ticket_phone'=>$configInfoResult['data']['ticket_phone'],
				'ticket_ad'=>$configInfoResult['data']['ticket_ad'],
				'ticket_qcode'=>$orderInfoResult['data']['qrcode'],
				'ticket_is_double'=>$configInfoResult['data']['ticket_is_double']
			);
			
			if($orderInfoResult['data']['pay_method'] == 1){
				$mesageInfo['pay_method'] ="微信支付";
			}else if($orderInfoResult['data']['pay_method'] == 2){
				$mesageInfo['pay_method'] ="支付宝支付";
			}else if($orderInfoResult['data']['pay_method'] == 3){
				$mesageInfo['pay_method'] ="货到付款";
			}

			if(!class_exists('m_base_printer')) include 'model/base/table/m_base_printer.php';
	    
		    $m_base_printer=new m_base_printer();

			

			for ($i=0; $i <count($data['ids']) ; $i++) { 

				$array=$m_base_printer->find(array('num'=>$data['ids'][$i]));

				$deviceInfo['ticket_device_num'] = $array['num'];
				$deviceInfo['ticket_device_key'] = $array['printer_key'];
				$result = $printTicket->sendNewPrint($mesageInfo,$deviceInfo);//新版小票打印机通知

				if($result['errorCode'] != 0){
					$log = Log::getInstance();
					$log->log($result['errorInfo'],'ERROR');
				}

			}

  	}
	/**
	 * 分页查询订单
	 */
	function pagingOrder(){
		/*
	    $page = $this->getPageInfo($this);
		$sort = "add_time desc";
		$conditions = $this->getArgsList($this,array(state),false);
		$keywords = $this->getArgsList($this,array(clinic_name,hospital_name,order_num,phone),false);
		$createTime = $this->getArgsList($this,array('from','to'),false);
		$result = $this->lib_order->pagingOrder ( $page, $conditions, $sort, $keywords,$createTime);
		echo json_encode($result);
		*/
		
		//登录账户是子账户，是代理
		if($_SESSION['admin']['agent_id']){
			$agent_id=$_SESSION['admin']['agent_id'];
			if(!class_exists('lib_agent')) include "model/store/lib_agent.php";
			$lib_agent=new lib_agent();
			$agentInfo=$lib_agent->getGoods($agent_id);
			$condition['province']=$agentInfo['data']['province'];
			$condition['city']=$agentInfo['data']['city'];
			$condition['area']=$agentInfo['data']['area'];

			//查询代理区域的所有诊所
			if(!class_exists('lib_clinic')) include "model/store/lib_clinic.php";
			$lib_clinic=new lib_clinic();
			$clinicsInfo=$lib_clinic->findAllClinic($condition);
			$ids=array();
			foreach($clinicsInfo['data'] as $val){
				$ids[]=$val['id'];
			}
			$clinic_id=implode(',',$ids);
			$where=" and o.clinic_id in ({$clinic_id})";
		}
		else{
			$where='';
		}

		$page = $this->getPageInfo($this);
		$sort = "add_time desc";
		$conditions = $this->getArgsList($this,array(userstate,state,order_num,phone),false);
		$keywords = $this->getArgsList($this,array(clinic_name,hospital_name,),false);
		$createTime = $this->getArgsList($this,array('from','to'),false);
		extract($page);
		extract($conditions);
		extract($keywords);
		extract($createTime);
		$sql="select o.*,c.name clinic_name,h.name hospital_name from store_order o left join store_clinic c on o.clinic_id=c.id left join store_hospital h on o.hospital_id=h.id where is_show=1 {$where}";
		if($state!=''){
			$sql.=" and o.state={$state}";
		}
        if($userstate!=''){
            $sql.=" and o.user_type={$userstate}";
        }
		if($order_num){
			$sql.=" and o.order_num='{$order_num}'";
		}
		if($phone){
			$sql.=" and o.phone='{$phone}'";
		}
		if($clinic_name){
			$sql.=" and c.name like '%{$clinic_name}%'";
		}
		if($hospital_name){
			$sql.=" and h.name like '%{$hospital_name}%'";
		}
		
		if($from){
			$sql.=" and date_format(o.add_time,'%Y-%m-%d')>='{$from}'";
		}
		if($to){
			$sql.=" and date_format(o.add_time,'%Y-%m-%d')<='{$to}'";
		}

		$sql=$sql." order by add_time desc";

		if(!class_exists('m_store_order')) require "model/store/table/m_store_order.php";
		$m_store_order=new m_store_order();
		$result['orderList']=$m_store_order->spPager($pageIndex,$pageSize)->findSql($sql);
		$result['pageInfo']=$m_store_order->spPager()->getPager();
		
		//手动添加分页信息
		if($result['pageInfo']==NULL){
			$all_pages=count($result['orderList'])%$pageSize==0?intval(count($result['orderList'])/$pageSize):intval(count($result['orderList'])/$pageSize)+1;
			$result['pageInfo']['current_page']=$pageIndex;
			$result['pageInfo']['first_page']=1;
			$result['pageInfo']['prev_page']=($pageIndex-1)>0?($pageIndex-1):1;
			$result['pageInfo']['next_page']=($pageIndex+1)<=$all_pages?($pageIndex+1):$all_pages;
			$result['pageInfo']['last_page']=$all_pages;
			$result['pageInfo']['total_count']=count($result['orderList']);
			$result['pageInfo']['total_page']=$all_pages;
			$result['pageInfo']['page_size']=$pageSize;
			$result['pageInfo']['all_pages']=$all_pages;
		}

		foreach($result['orderList'] as &$v){
			//推荐诊所clinicID
			if($v['clinicID']){
				if(!class_exists("lib_clinic")) include_once "model/store/lib_clinic.php";
				$lib_clinic=new lib_clinic();
				$clinic=$lib_clinic->getClinicInfo(array('clinicId'=>$v['clinicID']));
				$v['tj_name']=$clinic['data']['name'];
			}
		}
		
		$result['errorCode']=0;
		echo json_encode($result);
	}

	/**
	 * 查看订单报告
	 * 
	 */
	function showReport(){
		$this->getMenu($this);

		$oid=$this->spArgs('oid');
		if(!class_exists('lib_report')) include "model/store/lib_report.php";
		$lib_report=new lib_report();
		$reportInfo=$lib_report->getReport($oid);
		$reportInfo['data']['img_str']=json_decode($reportInfo['data']['img_str'],true);

		$this->reportInfo=$reportInfo['data'];

        $this->log(__CLASS__, __FUNCTION__, "查看订单报告", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/order/showReport.html");
	}

	/**
	 * 编辑报告
	 * 
	 */
	function editReport(){
		$this->getMenu($this);

		$id=$this->spArgs('id');
		$this->id=$id;

        $this->log(__CLASS__, __FUNCTION__, "编辑报告", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/order/editReport.html");
	}

	/**
	 * 提交编辑内容
	 */
	function updateReport(){
		$condition=$this->getArgsList($this,array(report_desc,img_str));
		$id=$this->spArgs('id');
		if(!class_exists('lib_report')) include "model/store/lib_report.php";
		$lib_report=new lib_report();
		$res=$lib_report->updateReport(array('id'=>$id),$condition);
		echo json_encode($res);
	}
	
	/**
	 * 删除订单 真删
	 */
	function deleteOrder(){
		$result = $this->lib_order->deleteOrder( $this->spArgs('id') );
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "管理员列表页面", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 批量删除订单
	 */
	function batchDeleteOrder(){
		$oids = $this->spArgs('ids');
		$result = $this->lib_order->batchDeleteOrder($oids);
		echo json_encode($result);
	}
	
	/**
	 * 导出订单
	 */
	function importExcel(){
		/*	
		$sort = "add_time desc";
		$conditions = $this->getArgsList($this,array(state),false);
		$keywords = $this->getArgsList($this,array(address_text,order_num),false);
		$createTime = $this->getArgsList($this,array('from','to'),false);
		
		$result = $this->lib_order->getAllOrders ($conditions, $sort, $keywords,$createTime);
		$this->log(__CLASS__, __FUNCTION__, "导出订单", 0, 'view');
		$this->lib_order->importExcel($result['data']['orderList']);
		*/
		
		//登录账户是子账户，是代理
		if($_SESSION['admin']['agent_id']){
			$agent_id=$_SESSION['admin']['agent_id'];
			if(!class_exists('lib_agent')) include "model/store/lib_agent.php";
			$lib_agent=new lib_agent();
			$agentInfo=$lib_agent->getGoods($agent_id);
			$condition['province']=$agentInfo['data']['province'];
			$condition['city']=$agentInfo['data']['city'];
			$condition['area']=$agentInfo['data']['area'];

			//查询代理区域的所有诊所
			if(!class_exists('lib_clinic')) include "model/store/lib_clinic.php";
			$lib_clinic=new lib_clinic();
			$clinicsInfo=$lib_clinic->findAllClinic($condition);
			$ids=array();
			foreach($clinicsInfo['data'] as $val){
				$ids[]=$val['id'];
			}
			$clinic_id=implode(',',$ids);
			$where=" and o.clinic_id in ({$clinic_id})";
		}
		else{
			$where='';
		}
		
		$sort = "add_time desc";
		$conditions = $this->getArgsList($this,array(userstate,state,order_num,phone),false);
		$keywords = $this->getArgsList($this,array(clinic_name,hospital_name,),false);
		$createTime = $this->getArgsList($this,array('from','to'),false);
		extract($conditions);
		extract($keywords);
		extract($createTime);
		$sql="select o.*,c.name clinic_name,h.name hospital_name from store_order o left join store_clinic c on o.clinic_id=c.id left join store_hospital h on o.hospital_id=h.id where is_show=1 {$where}";
        if($state!=''){
            $sql.=" and o.state={$state}";
        }
		if($userstate!=''){
			$sql.=" and o.user_type={$userstate}";
		}
		if($order_num){
			$sql.=" and o.order_num='{$order_num}'";
		}
		if($phone){
			$sql.=" and o.phone='{$phone}'";
		}
		if($clinic_name){
			$sql.=" and c.name like '%{$clinic_name}%'";
		}
		if($hospital_name){
			$sql.=" and h.name like '%{$hospital_name}%'";
		}
		
		if($from){
			$sql.=" and date_format(o.add_time,'%Y-%m-%d')>='{$from}'";
		}
		if($to){
			$sql.=" and date_format(o.add_time,'%Y-%m-%d')<='{$to}'";
		}
		
		$result['orderList']=$this->lib_order->findSql($sql);
		foreach ($result['orderList'] as $key => &$v) {
			$goodsList = json_decode($v['goods_list'],true);
			$name = "";
			foreach ($goodsList as $val) {
				if($val['goods_name']){
					$name .= $val['goods_name'].',';
				}
			}
			$name = trim($name,',');
			$v['checkItem'] = $name;
		}
		
		$this->log(__CLASS__, __FUNCTION__, "导出订单", 0, 'view');
		$this->lib_order->importExcel($result['orderList']);
	}
	
	/**
	 * 导出订单商品
	 */
	function exportOrderDetail(){
		$oid = $this->spArgs('oid');
		$orderResult = $this->lib_order->getOrderInfo(array('id'=>$oid));
		$goodsList = $orderResult['data']['goods_list'];
		foreach ($goodsList as& $goods){
			$propertyString = '';
			foreach ($goods['property']as $property){
				$propertyString .= $property['name'] . ":" .  $property['value']  . ",";
			}
			$propertyString = trim($propertyString,',');
			$goods['property'] = $propertyString;
		}
		$fieldList = array(
					array('key'=>'goods_name','name'=>'商品名','width'=>25),
					array('key'=>'count','name'=>'数量','width'=>20),
					array('key'=>'price','name'=>'单价','width'=>20),
				);
		include 'include/UtilExcel.php';
		$utilExcel = new UtilExcel();
		$utilExcel->exportExcel($goodsList, $fieldList);
	}
}