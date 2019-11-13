<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_promise')) require 'model/store/lib_promise.php';
/**
 *商家承诺管理
 * @name store_promise.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author mengchen
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2017-07-17
 */
class store_promise extends admin_controller{
    private $lib_promise;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		$this->lib_promise = new lib_promise();
	}
	
	//--------------------------百货配置---------------------------------------
	/**
	 * 商家承诺列表页面
	 */
	function promiseList(){
	    $this->getSetMenu($this);
	    //日志记录
        $this->log(__CLASS__, __FUNCTION__, "消息列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/promise/promiseList.html");
	}
   
   	/**
     * 添加商家承诺
     */
    function addPromise(){
		$this->getMenu($this);
    	$this->log(__CLASS__, __FUNCTION__, "添加消息页面", 1, 'add');
    	$this->display("../template/admin/{$this->theme}/store/page/promise/addPromise.html");
    }
	
	/**
	 * 编辑商家承诺
	 */
	function editPromise(){
		$this->getMenu($this);
		$id = $this->spArgs('id');
		//查找消息
		$result = $this->lib_promise->findPromise(array('id'=>$id));
		$this->promiseInfo = $result['data'];
		$this->log(__CLASS__, __FUNCTION__ ,"编辑消息页面",1,'edit');
		$this->display("../template/admin/{$this->theme}/store/page/promise/editPromise.html");
	} 
	
	
	/**
	 * 插入商家承诺
	 */
	function insertPromise(){
		$promiseInfo = $this->getArgsList($this, array(content,sort));
		$result = $this->lib_promise->addPromise($promiseInfo);
		echo json_encode($result);
	}
	
	/**
	 * 更新商家承诺
	 */
	function updatePromise(){
		$newsInfo = $this->getArgsList($this, array(content,sort));
		$id = $this->spArgs('id');
		$conditions = array('id'=>$id);
		$result = $this->lib_promise->updatePromise($conditions,$newsInfo);
		echo json_encode($result);
	}
	
	/**
	 * 删除商家承诺
	 */
	function deletePromise(){
		$id = $this->spArgs('id');
		$conditions = array('id'=>$id);
		$result = $this->lib_promise->deletePromise($conditions);
		echo json_encode($result);
	}
	
	/**
	 * 批量删除商家承诺
	 */
	function batchDeletePromise(){
		$gids = $this->spArgs('ids');
		$result = $this->lib_promise->batchDeletePromise($gids);
		$this->log(__CLASS__, __FUNCTION__, "批量删除商家承诺", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 分页查询商家承诺
	 */
	function pagingPromise(){
		$page = $this->getPageInfo($this);
		$sort ="sort asc";
		$conditionList = $this->getPagingList($this, array('content' => 'like'));
		$result = $this->lib_promise->pagingPromise( $page, $conditionList, $sort);
		echo json_encode($result);
	}
}