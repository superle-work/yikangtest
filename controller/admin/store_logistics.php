<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_logistics')) require 'model/store/lib_logistics.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
/**
 *诊所管理
 * @name store_logistics.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class store_logistics extends admin_controller{
    private $lib_logistics;

    /**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	    $this->lib_logistics = new lib_logistics();
	}
	
	/**
	 * 跳转添加诊所信息页面
	 */
	function addLogistics(){
		$this->getMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "跳转添加诊所信息页面", 1, 'add');
		$this->display("../template/admin/{$this->theme}/store/page/logistics/addLogistics.html");
	}
	
	/**
     * 绑定用户页面
     **/
    function showBindUser(){
        //一级分类
        $this->getMenu($this);
    	$this->log(__CLASS__, __FUNCTION__, "绑定用户页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/store/page/logistics/bindUser.html");
    }
	
	/*
	 * 展示核销记录
	 * */
	function cancelOrder(){
		$this->getMenu($this);
		$id=$this->spArgs('uid');
		$this->id=$id;
		$this->log(__CLASS__, __FUNCTION__, "核销记录", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/logistics/cancelOrder.html");
	}
	
	/*
	 * 异步展示核销记录列表
	 * */
	function pagingCancelOrder(){
		$page = $this->getPageInfo($this);
		extract($page);
		$Info = $this->getArgsList($this,array(id,order_num,state,from_add_time,to_add_time));
		$from=$Info['from_add_time'];
		$to=$Info['to_add_time'];
		$sql="select * from store_order where logistics_worker={$Info['id']}";
		if($Info['state']){
			$sql.=" and state={$Info['state']}";
		}
		if($Info['order_num']){
			$sql.=" and order_num={$Info['order_num']}";
		}
		if($from){
			$sql.=" and date_format(add_time,'%Y-%m-%d')>='{$from}'";
		}
		if($to){
			$sql.=" and date_format(add_time,'%Y-%m-%d')<='{$to}'";
		}
		
		if(!class_exists('m_store_order')) require "model/store/table/m_store_order.php";
		$m_store_order=new m_store_order();
		$result['orderList']=$m_store_order->spPager($pageIndex,$pageSize)->findSql($sql);
		$result['pageInfo']=$m_store_order->spPager()->getPager();
		
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
		$result['errorCode']=0;
		echo json_encode($result);
	}
	
	/**
	 * 诊所列表信息页面
	 */
	function logisticsList(){
		$this->getSetMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "跳转诊所列表信息页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/logistics/logisticsList.html");
	}
	
	/**
     * 展示对应工作人员核销的订单
     */
    function showDealOrder(){
		$this->getMenu($this);
        $conditions = array( 'id' => $this->spArgs('uid'));
		if(!class_exists('lib_user'))include "model/base/lib_user.php";
		$lib_user=new lib_user();
        $result = $lib_user->findUser ($conditions);
        $this->userInfo = $result['data'];
        $this->log(__CLASS__, __FUNCTION__, "用户信息列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/logistics/logisticsUserList.html");
    }
	
	/**
	 * 添加工作人员
	 */
	function insertLogistics(){
	    $uid=$this->spArgs("uid");
		if(!class_exists('lib_user'))include "model/base/lib_user.php";
		$lib_user=new lib_user();
		$userInfo=$lib_user->findUserList("id in ($uid)");
		foreach($userInfo['data'] as $val){
			$arr['nick_name']=$val['nick_name'];
			$arr['user_id']=$val['id'];
			$arr['phone']=$val['phone'];
			$arr['head_img']=$val['head_img_url'];
			$res=$this->lib_logistics->addLogistics($arr);
		}
		if($res){
			if(!class_exists('lib_user'))include "model/base/lib_user.php";
			$lib_user=new lib_user();
			$lib_user->updateUser("id in ({$uid})",array("user_type"=>3));
			echo json_encode(common::errorArray(0, '添加成功', true));
		}
		else{
			echo json_encode(common::errorArray(1, '添加失败', true));
		}
	}
	
	/**
	 * 分页查询工作人员
	 */
	function pagingLogistics(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('nick_name' => 'like','phone' => '=','id' => 'in','from_add_time' => '>=','to_add_time' => '<=');
		$conditions = $this->getPagingList($this, $keyValueList);
		$sort = "add_time desc";

		$lib_logistics = new lib_logistics();
		$result = $lib_logistics->pagingGoods ( $page, $conditions, $sort);
		echo json_encode($result);
	}

	/**
	 * 删除工作人员
	 */
	function deleteLogistics(){		
		$id = $this->spArgs('id');
		//保留工作人员id
		$userInfo=$this->lib_logistics->getGoods($id);
		
		$result = $this->lib_logistics->deleteComplete($id);
		if($result['errorCode'] != 0){
			echo json_encode($result);
			return;
		}
		
		//删除后，重置user表的user_type
		if(!class_exists('lib_user'))include "model/base/lib_user.php";
		$lib_user=new lib_user();
		$lib_user->updateUser("id in ({$userInfo['data']['user_id']})",array("user_type"=>0));
		
		$this->log(__CLASS__, __FUNCTION__, "删除工作人员", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 批量删除工作人员
	 */
	function batchDelete(){
		$gids = $this->spArgs('ids');
		
		//获取删除的人员的用户id
		$res=$this->lib_logistics->findSql("select * from store_logistic where id in ({$gids})");
		
		if(!class_exists('lib_user'))include "model/base/lib_user.php";
		$lib_user=new lib_user();
		foreach($res as $val){
			$lib_user->updateUser("id={$val['user_id']}",array("user_type"=>0));
		}
		
		$result = $this->lib_logistics->batchDelete($gids);
		$this->log(__CLASS__, __FUNCTION__, "批量删除工作人员", 0, 'del');
		echo json_encode($result);
	}
}