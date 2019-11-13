<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
/**
 * 微信自定义菜单
 * @name weixin_menu.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-13
 */
class  weixin_menu extends admin_controller{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	}
	
	/**
	 * 微信菜单列表页面
	 */
	function menuList(){
		$this->getSetMenu($this);
		//获取微信服务器上的微信菜单
		$menuList =  Menu::getMenu();
		$this->wechatMenuList = $menuList['menu']['button'];
		$this->log(__CLASS__, __FUNCTION__, "微信菜单列表页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/weixin/menu/page/menuList.html");
	}
	
	/**
	 * 设置自定义菜单
	 */
	function setWechatMenu(){
		$rawMenuList = $this->spArgs('menuList');
		$menuList = json_decode($rawMenuList);
		foreach ($menuList as &$menu){
			$menu = (array)$menu;
		}
		if(Menu::setMenu($menuList)){
			$this->log(__CLASS__, __FUNCTION__, "设置自定义菜单成功", 0, 'edit');
			echo json_encode(common::errorArray(0, "保存成功！", 1));
		}else{
			$this->log(__CLASS__, __FUNCTION__, "设置自定义菜单失败", 0, 'edit');
			echo json_encode(common::errorArray(1, "保存失败，请稍后再试！", 0));
		}
	}
}