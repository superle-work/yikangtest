<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_news')) require 'model/store/lib_news.php';
/**
 *商城消息管理
 * @name store_news.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-09-01
 */
class store_news extends admin_controller{
    private $lib_news;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		$this->lib_news = new lib_news();
	}
	
	//--------------------------百货配置---------------------------------------
	/**
	 ** 商城消息列表页面
	 **/
	function newsList(){
	    $this->getSetMenu($this);
	    //日志记录
        $this->log(__CLASS__, __FUNCTION__, "消息列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/news/newsList.html");
	}
   
   	/**
     * 添加商城消息
     */
    function addNews(){
		$this->getMenu($this);
    	$this->log(__CLASS__, __FUNCTION__, "添加消息页面", 1, 'add');
    	$this->display("../template/admin/{$this->theme}/store/page/news/addNews.html");
    }
	
	/**
	 * 编辑消息
	 */
	function editNews(){
		$this->getMenu($this);
		$id = $this->spArgs('id');
		//查找消息
		$result = $this->lib_news->findNews(array('id'=>$id));
		$this->newsInfo = $result['data'];
		$this->log(__CLASS__, __FUNCTION__ ,"编辑消息页面",1,'edit');
		$this->display("../template/admin/{$this->theme}/store/page/news/editNews.html");
	} 
	
	
	/**
	 * 插入商城消息
	 */
	function insertNews(){
		$newsInfo = $this->getArgsList($this, array(title,link,sort));
		$result = $this->lib_news->addNews($newsInfo);
		echo json_encode($result);
	}
	
	/**
	 * 更新商城信息
	 */
	function updateNews(){
		$newsInfo = $this->getArgsList($this, array(title,link,sort));
		$id = $this->spArgs('id');
		$conditions = array('id'=>$id);
		$result = $this->lib_news->updateNews($conditions,$newsInfo);
		echo json_encode($result);
	}
	
	/**
	 * 删除消息
	 */
	function deleteNews(){
		$id = $this->spArgs('id');
		$conditions = array('id'=>$id);
		$result = $this->lib_news->deleteNews($conditions);
		echo json_encode($result);
	}
	
	/**
	 * 批量删除商品
	 */
	function batchDeleteNews(){
		$gids = $this->spArgs('ids');
		$result = $this->lib_news->batchDeleteNews($gids);
		$this->log(__CLASS__, __FUNCTION__, "批量删除商品", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 分页查询商城消息
	 */
	function pagingNews(){
		$page = $this->getPageInfo($this);
		$sort ="sort asc";
		$conditionList = $this->getPagingList($this, array('title' => 'like'));
		$result = $this->lib_news->pagingNews( $page, $conditionList, $sort);
		echo json_encode($result);
	}
}