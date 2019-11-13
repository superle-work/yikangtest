<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_agent')) require 'model/store/lib_agent.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
/**
 *代理管理
 * @name store_agent.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class store_agent extends admin_controller{
    private $lib_agent;

    /**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	    $this->lib_agent = new lib_agent();
	}
	
	/**
	 * 跳转添加代理信息页面
	 */
	function addAgent(){
		$this->getMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "跳转添加代理信息页面", 1, 'add');
		$this->display("../template/admin/{$this->theme}/store/page/agent/addAgent.html");
	}

	/**
	 * 代理列表信息页面
	 */
	function agentList(){
		$this->getSetMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "跳转代理列表信息页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/agent/agentList.html");
	}

	/**
	 * 编辑代理页面
	 */
	function editAgent(){
	    $this->getMenu($this);
	    $goodsResult = $this->lib_agent->getGoods($this->spArgs('id'));
	    $this->goods = $goodsResult['data'];
	    $this->log(__CLASS__, __FUNCTION__, "编辑代理页面", 1, 'edit');
	    $this->display("../template/admin/{$this->theme}/store/page/agent/editAgent.html");
	}
	
	
	/**
	 * 添加代理
	 */
	function insertAgent(){	    
	    $goodsInfo = $this->getArgsList($this, array(name,province,city,area,rate));
	    $where['province']=$goodsInfo['province'];
	    $where['city']=$goodsInfo['city'];
	    $where['area']=$goodsInfo['area'];

	    //判断表中是否已有
	    $result=$this->lib_agent->getAgentInfo($where);
	    if($result['errorCode']==0){
	    	echo json_encode(common::errorArray(1,'该区域代理商已存在',false));
	    	die;
	    }

		$userInfo = $_SESSION['admin'];
		$resultGoods = $this->lib_agent->addAgent ($goodsInfo,$userInfo );	
		$this->log(__CLASS__, __FUNCTION__, "添加代理", 0, 'add');
		echo json_encode($resultGoods);
	}
	
	/**
	 * 分页查询代理
	 */
	function pagingAgent(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('name' => 'like','area' => 'like','id' => 'in','from_add_time' => '>=','to_add_time' => '<=');
		$conditions = $this->getPagingList($this, $keyValueList);
		$sort = "add_time desc";

		$lib_agent = new lib_agent();
		$result = $lib_agent->pagingGoods ( $page, $conditions, $sort);
		echo json_encode($result);
	}
	
	/**
	 * 修改
	 */
	function  updateAgent(){
		$conditions = array('id' => $this->spArgs('id'));
		//更新基本信息
		$goodsInfo = $this->getArgsList($this, array(name,rate));

		$result = $this->lib_agent->updateAgent($conditions,$goodsInfo);
		$this->log(__CLASS__, __FUNCTION__, "修改信息", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 根据id获取代理信息
	 */
	function getGoodsDetail() {
		$result = $this->lib_agent->getGoods($this->spArgs('id'));
		$this->log(__CLASS__, __FUNCTION__, "根据id获取代理信息", 0, 'view');
		echo json_encode($result);
	}

	/**
	 * 删除代理
	 */
	function deleteAgent(){		
		$id = $this->spArgs('id');
		$result = $this->lib_agent->deleteComplete($id);
		if($result['errorCode'] != 0){
			echo json_encode($result);
			return;
		}
		$this->log(__CLASS__, __FUNCTION__, "删除商品", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 批量删除代理
	 */
	function batchDelete(){
		$gids = $this->spArgs('ids');
		$result = $this->lib_agent->batchDelete($gids);
		$this->log(__CLASS__, __FUNCTION__, "批量删除代理", 0, 'del');
		echo json_encode($result);
	}
	
	/*
	 * 结算
	 * */
	function payMoney(){
		$id=$this->spArgs("id");
		$cliInfo=$this->lib_agent->getGoods($id);
		$money=$cliInfo['data']['money'];
		
		//把可提佣金清零
		$sql="update store_agent set money=0 where id={$id}";
		$res=$this->lib_agent->runSql($sql);
		if($res==true){
			echo json_encode(common::errorArray(0, '结算成功', true));
		}
		else{
			echo json_encode(common::errorArray(1, '结算失败', true));
		}
	}
}