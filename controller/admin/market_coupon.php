<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_coupon')) require 'model/market/coupon/lib_coupon.php';
/**
 * 优惠券管理
 * @name market_coupon
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author leon
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-09-01
 */
class  market_coupon extends admin_controller{	
	private $lib_coupon;
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
        $this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		$this->lib_coupon = new lib_coupon();
	}
	
	/**
	 * 优惠券列表页面
	 */
	function couponList(){
	 	$this->getSetMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "优惠券列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/market/coupon/page/couponList.html");
	}
	
	/**
	 * 添加优惠券页面
	 */
	function addCoupon(){
		$this->getMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "添加优惠券页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/market/coupon/page/addCoupon.html");
	}
	
	/**
	 * 编辑优惠券页面
	 */
	function editCoupon(){
		$this->getMenu($this);
		$result = $this->lib_coupon->findCoupon( array('id' => $this->spArgs('id')) );
		$this->couponInfo = $result['data'];
		$this->log(__CLASS__, __FUNCTION__, "编辑优惠券页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/market/coupon/page/editCoupon.html");
	}
	
	/**
	 * 用户优惠券列表
	 */
	function userCouponList(){
		$this->getMenu($this);
		//当前优惠券信息
		$result = $this->lib_coupon->findCoupon( array('id' => $this->spArgs('id')) );
		$this->couponInfo = $result['data'];
		$this->log(__CLASS__, __FUNCTION__, "用户优惠券列表", 1, 'view');
		$this->display("../template/admin/{$this->theme}/market/coupon/page/userCouponList.html");
	}
	
	/**
	 * 用户优惠券分享列表
	 */
	function couponShareList(){
		$this->getMenu($this);//设置页面公共数据
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "用户优惠券分享列表", 1, 'view');
		$this->display("../template/admin/{$this->theme}/market/coupon/page/couponShareList.html");
	}
	
	/**
	 * 优惠券订单列表
	 */
	function couponOrderList(){
	    $this->getMenu($this);//设置页面公共数据
	    $this->log(__CLASS__, __FUNCTION__, "优惠券订单列表", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/market/coupon/page/couponOrderList.html");
	}
	
	/**
	 * 添加优惠券
	 */
	function insertCoupon(){
	    $couponInfo = $this->getArgsList($this, 
	        array('name', 'type', 'value', 'condition_value', 'discount', 'user_limit',
	              'spare_quantity', 'use_scene', 'is_use', 'description', 'color',
	              'share_times', 'start_date', 'end_date', 'valid_start', 'valid_end',
	              'detail', 'notice', 'points', 'use_type', 'sale_value'
	        )
	    );
	    $couponInfo['get_quantity'] = 0;
		if($this->spArgs('type') != '1'){//非代金券
            $couponInfo['value'] = 0;
            $couponInfo['condition_value'] = 0;
		
        }
        if($this->spArgs('type') != '3'){
            $couponInfo['discount'] = 0;
        }
		
		$result = $this->lib_coupon->addCoupon( $couponInfo );
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "添加优惠券", 0, 'add');
		echo json_encode($result);
	}
	
	/**
	 * 修改优惠券
	 */
	function updateCoupon(){
		$condition = array('id' => $this->spArgs('id'));
		$couponInfo = $this->getArgsList($this, 
	        array('name', 'type', 'value', 'condition_value', 'discount', 'user_limit',
	              'spare_quantity', 'use_scene', 'is_use', 'description', 'color',
	              'share_times', 'start_date', 'end_date', 'valid_start', 'valid_end',
	              'detail', 'notice', 'use_type', 'sale_value'
	        )
	    );
		if($this->spArgs('type') != '1'){//非代金券时
            $couponInfo['value'] = 0;
            $couponInfo['condition_value'] = 0;
        }
        if($this->spArgs('type') != '3'){//非折扣券时
            $couponInfo['discount'] = 0;
        }
		if($this->spArgs('points') != '' && $this->spArgs('points') != NULL){
		    $couponInfo['points'] = $this->spArgs('points');
		}else{
		    $couponInfo['points'] = '0';
		}
		if($this->spArgs('sale_value') == NULL || $this->spArgs('sale_value') == ""){
			$couponInfo['sale_value'] = '0';
		}
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "修改优惠券", 0, 'edit');
		$result =  $this->lib_coupon->updateCoupon( $condition, $couponInfo);
		echo json_encode($result);
	}
	
	
	/**
	 * 开启关闭优惠券
	 */
	function useCoupon(){
		 $condition = array('id' => $this->spArgs('id'));
		 $result = $this->lib_coupon->updateCoupon($condition, array('is_use'=>$this->spArgs('is_use')));
		 //日志记录
		 $this->log(__CLASS__, __FUNCTION__, "开启关闭优惠券", 0, 'edit');
		 echo json_encode($result);
	}
	
	/**
	 * 删除优惠券
	 */
	function deleteCoupon(){
		$id= $this->spArgs('id');
		$couponResult = $this->lib_coupon->findUserCouponSp($id);
		if($couponResult['errorCode'] == 0){//查到有用户领取未使用的优惠券，删除前需要告知用户及其再次确认
			if(!$this->spArgs('isAgree')){//未确认时，给出提示
			    //日志记录
			    $this->log(__CLASS__, __FUNCTION__, "提示确认删除优惠券", 0, 'view');
			    echo json_encode(common::errorArray(2,"该优惠券已经被人领取且尚未使用，您确定要删除么？" , $couponResult['data']));
                exit;
			}else{
                //得到用户确认删除时
			    $this->log(__CLASS__, __FUNCTION__, "删除(包含未被使用)优惠券", 0, 'del');
                $result = $this->lib_coupon->deleteCoupon($id);
                echo  json_encode($result);
			}

		}else{
			//日志记录
			$this->log(__CLASS__, __FUNCTION__, "删除优惠券", 0, 'del');
			$result = $this->lib_coupon->deleteCoupon($id);
			echo  json_encode($result);
		}
	}
	
	/**
	 * 批量删除优惠券
	 */
	function deleteCouponBatch(){
		$ids= $this->spArgs('ids');
		$aIds = explode(',', $ids);
		if(!$this->spArgs('isAgree')){//未确认时，给出提示
			foreach($aIds as $id){
				$couponResult = $this->lib_coupon->findUserCouponSp($id);
				if($couponResult['errorCode'] == 0){//查到有用户领取未使用的优惠券，删除前需要告知用户及其再次确认
				    //日志记录
				    $this->log(__CLASS__, __FUNCTION__, "提示确认批量删除优惠券", 0, 'view');
					echo json_encode(common::errorArray(2,"存在优惠券已经被人领取且尚未使用，您确定要删除么？" , $couponResult['data']));
					exit;
				}
			}
			//日志记录
			$this->log(__CLASS__, __FUNCTION__, "批量删除优惠券", 0, 'del');
			$result = $this->lib_coupon->deleteCoupon($ids);
			echo  json_encode($result);
		}else{
			//日志记录
			$this->log(__CLASS__, __FUNCTION__, "批量删除优惠券", 0, 'del');
			//得到用户确认删除时
			$result = $this->lib_coupon->deleteCoupon($ids);
			echo  json_encode($result);
		}
	}
	
	/**
	 * 分页查询优惠券
	 */
	function pagingCoupon(){
		$page = array('pageIndex'=>$this->spArgs('pageIndex'),'pageSize'=>$this->spArgs('pageSize'));
		$conditionList = $this->getPagingList($this, array(
		    'name' => 'like', 'is_use' => '='
		));//默认无条件 必须有
		$sort = "is_use desc,add_time desc,end_date desc";
		$result = $this->lib_coupon->pagingCoupon($page, $conditionList, $sort);
		echo json_encode($result);
	}
	
	/**
	 * 分页查询优惠券
	 */
	function pagingUserCoupon(){
		$page = array('pageIndex'=>$this->spArgs('pageIndex'),'pageSize'=>$this->spArgs('pageSize'));
		$conditionList = array();
		if($this->spArgs('name') != '' && $this->spArgs('name') != null){
		    array_push($conditionList, array('field'=>'lf_name','operator'=>'like','value'=>$this->spArgs('name')));
		}
		if($this->spArgs('is_use') != '' && $this->spArgs('is_use') != null){
		    array_push($conditionList, array('field'=>'lf_is_use','operator'=>'=','value'=>$this->spArgs('is_use')));
		}
		if($this->spArgs('coupon_id') != '' && $this->spArgs('coupon_id') != null){
		    array_push($conditionList, array('field'=>'lf_coupon_id','operator'=>'=','value'=>$this->spArgs('coupon_id')));
		}
		$sort = "add_time desc";
		$result = $this->lib_coupon->pagingUserCoupon($page, $conditionList, $sort);
		echo json_encode($result);
	}
	
	/**
	 * 分页查询优惠券分享
	 */
	function pagingShare(){
		$page = array('pageIndex'=>$this->spArgs('pageIndex'),'pageSize'=>$this->spArgs('pageSize'));
		$conditionList = array();//默认无条件 必须有
		if("" != $this->spArgs('coupon_name') && null != $this->spArgs('coupon_name')){
			array_push($conditionList,  array("field" => "coupon_name","operator" => "like","value" => $this->spArgs('coupon_name')));
		}
		if("" != $this->spArgs('nick_name') && null != $this->spArgs('nick_name')){
			array_push($conditionList,  array("field" => "nick_name","operator" => "like","value" => $this->spArgs('nick_name')));
		}
		if("" != $this->spArgs('from') && null != $this->spArgs('from')){
			array_push($conditionList,  array("field" => "add_time","operator" => ">=","value" => $this->spArgs('from')));
		}
		if("" != $this->spArgs('to') && null != $this->spArgs('to')){
			array_push($conditionList,  array("field" => "add_time","operator" => "<=","value" => $this->spArgs('to')));
		}
		$sort = "add_time desc";
		$result = $this->lib_coupon->pagingShare($page, $conditionList, $sort);
		echo json_encode($result);
	}
	
	/**
	 * 删除订单
	 */
	function deleteCouponOrder(){
	    $ids = $this->spArgs('ids');
	    if(!class_exists('lib_coupon_order')) include 'model/market/coupon/lib_coupon_order.php';
	    $lib_coupon_order = new lib_coupon_order();
	    $result = $lib_coupon_order->deleteCouponOrder($ids);
	    $this->log(__CLASS__, __FUNCTION__, "删除优惠券订单", 0, 'del');
	    echo json_encode($result);
	}
	
	/**
	 * 删除订单
	 */
	function batchDeleteOrder (){
	    $ids = $this->spArgs('ids');
	    if(!class_exists('lib_coupon_order')) include 'model/market/coupon/lib_coupon_order.php';
	    $lib_coupon_order = new lib_coupon_order();
	    $result = $lib_coupon_order->deleteCouponUserOrder($ids);
	    $this->log(__CLASS__, __FUNCTION__, "删除优惠券订单", 0, 'del');
	    echo json_encode($result);
	}
	
	
	/**
	 * 分页查询优惠券订单
	 */
	function pagingOrder(){
	    $page = $this->getPageInfo($this);
	    $conditionList = $this->getPagingList($this, array(
	        'nick_name' => 'like', 'coupon_name' => 'like'
	    ));//默认无条件 必须有
	    $sort = "add_time desc";
	    if(!class_exists('lib_coupon_order')) include 'model/market/coupon/lib_coupon_order.php';
	    $lib_coupon_order = new lib_coupon_order();
	    $result = $lib_coupon_order->pagingOrder($page, $conditionList, $sort);
	    echo json_encode($result);
	}
	
/////////////////////////////////////优惠券订单//////////////////////////////////////////////
	/**
	 * 优惠券订单页面
	 */
	function buyCouponOrder (){
	    $this->getMenu($this);//设置页面公共数据
	    $this->log(__CLASS__, __FUNCTION__, "购买优惠券订单列表", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/market/coupon/page/order/orderList.html");
	}
	
	/**
	 * 优惠券订单详情页面
	 */
	function buyCouponOrderDetail (){
	    $this->getMenu($this);//设置页面公共数据
	    $conditions = array('id' => $this->spArgs('oid'));
	    if(!class_exists('lib_coupon_order')) include 'model/market/coupon/lib_coupon_order.php';
	    $lib_coupon_order = new lib_coupon_order();
	    $result = $lib_coupon_order->findUserCouponOrder($conditions);
	    $this->orderInfo = $result['data'];
	    $this->log(__CLASS__, __FUNCTION__, "优惠券订单详情页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/market/coupon/page/order/orderDetail.html");
	}
	
	/**
	 * 设置订单状态
	 */
	function setOrderState(){
	    $conditions = array('id'=>$this->spArgs('oid'));
	    $row = array('state'=>$this->spArgs('state'));
	    if(!class_exists('lib_coupon_order')) include 'model/market/coupon/lib_coupon_order.php';
	    $lib_order = new lib_coupon_order();
	    $result = $lib_order->updateUserCouponOrder($conditions, $row);
	    $this->log(__CLASS__, __FUNCTION__, "设置订单状态", 0, 'edit');
	    echo json_encode($result);
	}
	
	/**
	 * 删除订单
	 */
	function deleteOrder(){
	    $ids = $this->spArgs('id');
	    if(!class_exists('lib_coupon_order')) include 'model/market/coupon/lib_coupon_order.php';
	    $lib_order = new lib_coupon_order();
	    $result = $lib_order->deleteUserCouponOrder($ids);
	    $this->log(__CLASS__, __FUNCTION__, "删除订单", 0, 'del');
	    echo json_encode($result);
	}
	
	/**
	 * 分页查询购买订单记录
	 */
	function pagingUserOrder (){
	    $page = $this->getPageInfo($this);
	    $conditionList = array();
	    if('' != $this->spArgs('order_num') && null != $this->spArgs('order_num')){
	        array_push($conditionList, array('field'=>'lf_order_num','operator'=>'=','value'=>$this->spArgs('order_num')));
	    }
	    if('' != $this->spArgs('state') && null != $this->spArgs('state')){
	        array_push($conditionList, array('field'=>'lf_state','operator'=>'=','value'=>$this->spArgs('state')));
	    }
	    if('' != $this->spArgs('from') && null != $this->spArgs('from')){
	        array_push($conditionList, array('field'=>'lf_add_time','operator'=>'>=','value'=>$this->spArgs('from')));
	    }
	    if('' != $this->spArgs('to') && null != $this->spArgs('to')){
	        array_push($conditionList, array('field'=>'lf_add_time','operator'=>'<=','value'=>$this->spArgs('to')));
	    }
	    $sort = "add_time desc";
	    if(!class_exists('lib_coupon_order')) include 'model/market/coupon/lib_coupon_order.php';
	    $lib_coupon_order = new lib_coupon_order();
	    $result = $lib_coupon_order->pagingUserJoinOrder($page, $conditionList, $sort);
	    echo json_encode($result);
	}
}