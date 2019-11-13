<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_admin')) require 'model/base/lib_admin.php';
if(!class_exists('lib_admin_message')) require 'model/base/lib_admin_message.php';
/**
 *管理员管理
 * @name base_admin.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class base_admin extends admin_controller{
    private $lib_admin;
    private $lib_message;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->lib_admin = new lib_admin();
		$this->lib_message = new lib_admin_message();
	}
	
    //--------------------------------管理员管理-----------------------------------
	
	/**
	 * 管理员列表页面
	 */
	function adminList(){
		$this->getSetMenu($this);
		$result = $this->lib_admin->findAllAdmin();
		$this->adminList = $result['data'];
		$this->log(__CLASS__, __FUNCTION__, "管理员列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/admin/page/adminList.html");
	}
	
	/**
	 * 添加管理员页面
	 */
	function addAdmin(){
		$this->getMenu($this);

		//查询所有的代理
		if(!class_exists('lib_agent')) include "model/store/lib_agent.php";
		$lib_agent=new lib_agent;
		$agentResult=$lib_agent->findAllAgent();
		$this->agentResult=$agentResult['data'];

		$this->log(__CLASS__, __FUNCTION__, "添加管理员页面", 1, 'add');
		$this->display("../template/admin/{$this->theme}/base/admin/page/addAdmin.html");
	}
	
	/**
	 * 编辑管理员页面
	 */
	function editAdmin(){
	    $this->getMenu($this);
		$result = $this->lib_admin->findAdmin( array('id' => $this->spArgs('id')) );
		$this->admin = $result['data'];
	    $this->log(__CLASS__, __FUNCTION__, "编辑管理员页面", 1, 'edit');
		$this->display("../template/admin/{$this->theme}/base/admin/page/editAdmin.html");
	}
	
	/**
	 * 修改管理员密码页面
	 */
	function editPwd(){
		$this->getMenu($this);
		$result = $this->lib_admin->findAdmin( array('id' => $this->spArgs('id')) );
		$this->admin = $result['data'];
		$this->log(__CLASS__, __FUNCTION__, "修改管理员密码页面", 1, 'edit');
		$this->display("../template/admin/{$this->theme}/base/admin/page/editPwd.html");
	}
	
	/**
	 * 添加管理员
	 */
	function insertAdmin(){
	    $this->log(__CLASS__, __FUNCTION__, "添加管理员", 0, 'add');
		$adminInfo = $this->getArgsList($this, array(account,password,email,admin_name,type,agent_id));
		$result = $this->lib_admin->addAdmin( $adminInfo );
		echo json_encode($result);
	}
	
	/**
	 * 判断账号是否存在
	 */
	function isAdminExist(){
		$result = $this->lib_admin->isAccountExist( $this->spArgs('account') );
		echo json_encode($result['errorCode']);
	}
	
	/**
	 * 测试密码是否正确
	 */
	function testPassword(){
		$condition = array(
				'account' => $this->spArgs('account') ,
				'password' => md5($this->spArgs('password') )
				);
		$result = $this->lib_admin->findAdmin( $condition);
		if(json_encode($result['errorCode']) == 0){//密码正确时，返回true
		    echo 1;
		}else{//密码错误时，返回false
	    	echo 0;
		}
	}
	
	/**
	 * 修改管理员
	 */
	function updateAdmin(){
		$adminInfo = $this->getArgsList($this, array(email,admin_name,password),false);
		$result = $this->lib_admin->updateAdmin( array('id' => $this->spArgs('id')), $adminInfo);
		$this->log(__CLASS__, __FUNCTION__, "修改管理员", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 删除管理员
	 */
	function deleteAdmin(){
		$id = $this->spArgs('id');
		if($id == $_SESSION['admin']['id']){
			echo json_encode(common::errorArray(1, "不能删除自己", FALSE));
		}
		$result = $this->lib_admin->deleteAdmin(array('id' => $id));
		$this->log(__CLASS__, __FUNCTION__, "删除管理员", 0, 'del');
		echo json_encode($result);
	}
	
	//-----------------------------------------消息管理--------------------------------------
	
	/**
	 * 管理员消息列表页面
	 */
	function messageList(){
	    $this->getSetMenu($this);
	    $result = $this->lib_message->findMessageField('source');
	    $this->sources = $result['data'];
	    $this->log(__CLASS__, __FUNCTION__, "管理员消息列表页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/base/admin/page/messageList.html");
	}
	
	/**
	 * 获取最新未读消息个数
	 */
	function getNoReadCount(){
	    $result = $this->lib_message->getNoReadMessageCount($_SESSION['admin']['id']);
	    echo json_encode($result);
	}
	
	/**
	 * 置为已读
	 */
	function getRead(){
	    $id = $this->spArgs('id');
	    $result = $this->lib_message->getRead(array('id'=>$id));
	    echo json_encode($result);
	}
	
	/**
	 * 标记管理员消息已读
	 */
	function tagMessageRead(){
	    $ids = $this->spArgs('ids');
	    $result = $this->lib_message->tagMessageRead($ids);
	    $this->log(__CLASS__, __FUNCTION__, "标记管理员消息已读", 0, 'edit');
	    echo json_encode($result);
	}
	
	/**
	 * 批量删除管理员消息
	 */
	function deleteMessageBatch(){
	    $ids = $this->spArgs('ids');
	    $result = $this->lib_message->deleteMessage($ids);
	    $this->log(__CLASS__, __FUNCTION__, "删除管理员消息", 0, 'del');
	    echo json_encode($result);
	}
	
	/**
	 * 分页查询管理员消息
	 */
	function pagingMessage(){
	    $page = $this->getPageInfo($this);
	    $keyValueList = array('title'=>'like','content'=>'like','source'=>'like','admin_id'=>'=','is_read'=>'=','type'=>'=','from_add_time'=>'>=','to_add_time'=>'<=');
	    $conditionList = $this->getPagingList($this, $keyValueList);
	    $sort = "add_time desc";
	    $result = $this->lib_message->pagingMessage($page,$conditionList,$sort);
	    echo json_encode($result);
	}
    function test(){
        echo time("2015-10-11 10:49:11");
        echo strtotime("2015-10-11 10:49:11");
    }
	
    /**
     * 获取最新消息通知
     */
	function getNewMessage(){
	    $sec = $this->spArgs('sec');
	    if($_SESSION['admin']['notify_time'] && time()-strtotime($_SESSION['admin']['notify_time']) < $sec){//时间小于规定时间
	       exit(json_encode( common::errorArray(1,"还没到更新通知时间",$_SESSION['admin']['notify_time'])));
	    }else{//没有时间
	       $time = date('Y-m-d H:i:s',time()-$sec);
	       $_SESSION['admin']['notify_time'] = $time;
	    }
	    $result = $this->lib_message->findAllMessage("add_time > '{$_SESSION['admin']['notify_time']}'");
	    setcookie("notifyTime",common::getTime(),time()+1*1*3600);
	    $_SESSION['admin']['notify_time'] = common::getTime();
	    echo json_encode($result);
	}
}