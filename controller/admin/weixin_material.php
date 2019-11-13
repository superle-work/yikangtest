<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
/**
 * 微信素材管理
 * @name weixin_material.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-13
 */
class  weixin_material extends admin_controller{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	}
	
	/**
	 * 素材列表页面
	 */
	function materialList(){
		$this->getSetMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "素材列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/weixin/material/page/materialList.html");
	}
	
	/**
	 * 分页查询素材
	 */
	function pagingMedia(){
		$type = $this->spArgs('type');//image news video voice
		$pageIndex = $this->spArgs('pageIndex');
		$pageSize = $this->spArgs('pageSize');
		$offset = ($pageIndex  - 1) *  $pageSize;
		$result = Media::getMediaList($type,$offset,$pageSize);
		echo json_encode($result);
	}
	
	/**
	 * 删除素材
	 */
	function deleteMedia(){
		$mediaId = $this->spArgs('mediaId');
		$this->log(__CLASS__, __FUNCTION__, "删除素材", 0, 'del');
		echo Media::deleteMedia($mediaId);
	}
	
	/**
	 * 更新素材
	 */
	function updateNews(){
		$jsonString = $this->spArgs('news');
		$this->log(__CLASS__, __FUNCTION__, "更新素材", 0, 'edit');
		echo Media::updateNews($jsonString);
	}
	
	/**
	 * 上传图文消息内的图片获取URL
	 * 需要传入id为media的post表单
	 */
	function addNewsImage(){
		echo Media::addNewsImage();
	}
	
	/**
	 * 添加素材
	 * 需要传入id为media的post表单
	 */
	function addMedia(){
		$type = $this->spArgs('type');
		$title = $this->spArgs('title');
		$introduction = $this->spArgs('introduction');
		$this->log(__CLASS__, __FUNCTION__, "添加素材", 0, 'add');
		echo Media::addMedia($type,$title,$introduction);
	}
	
	/**
	 * 通过mediaId获取素材内容
	 */
	function getMedia(){
		$mediaId = $this->spArgs('mediaId');
		echo Media::getMedia($mediaId);
	}
	
}