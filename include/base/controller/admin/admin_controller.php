<?php
if(!class_exists('base_controller')) require 'include/base/controller/base_controller.php';
/**
 *管理员控制器基类
 * @name admin_controller.php
 * @package cws
 * @category base
 * @link http://www.changekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class admin_controller extends base_controller{
    private $go = "./admin.php?c=base_admin&a=login";
	//---------------------------------菜单管理-----------------------------------
	/**
	 * 获取菜单
	 * @param object $controler
	 */
	protected function getMenu($controler){
		//加入缓存
		// $menuCache = spAccess('r' , 'menuCache');
		// if(!$menuCache){
		    if(!class_exists('lib_menu')) include 'model/base/lib_menu.php';
			$lib_menu = new lib_menu();
			$result = $lib_menu->getMenuList($_SESSION['admin']['type']);
			spAccess('w' , 'menuCache', $result['data'], 3600);
			$controler->menuList = $result['data'];
		// }else{
		// 	$controler->menuList = $menuCache;
		// }
		$controler->theme = $this->getConfigValue('admin_theme');//后台主题
		$controler->skin = $this->getConfigValue('admin_skin');//后台主题
	}
	
	/**
	 * 获取并设置菜单
	 * @param object $controler
	 */
	protected function getSetMenu($controler){
		$this->getMenu($controler);
		$this->setMenu($controler);
	}
	
	/**
	 * 设置选中菜单
	 * @param object $controller
	 */
	private function setMenu($controller){
		if(!class_exists('lib_menu')) include 'model/base/lib_menu.php';
		$lib_menu = new lib_menu();
		$mid = $controller->spArgs('mid');
        if(!$mid){
            if($_SESSION['admin']['type']==1){
        		$mid = 1;//默认菜单主键
        	}
            else{
            	$mid=29;
            }
        }
		$menuResult = $lib_menu->findMenu(array('id'=>$mid));
		$menuTopResult = $lib_menu->findMenuTop(array('id'=>$menuResult['data']['menu_top_id']));
		$_SESSION['currentMenu'] = array(
				"navName"=>$menuTopResult['data']['alias'],
				"navTitle"=>$menuTopResult['data']['name'],
				"menuName"=>$menuResult['data']['unique_name'],
				"menuTitle"=>$menuResult['data']['name'],
				"icon"=>$menuResult['data']['icon'],
				"mid"=>$menuResult['data']['id'],
				"menu_top_id"=>$menuResult['data']['menu_top_id']
		);
	}
    
	//----------------------------------------消息通知-------------------------------
	
	/**
	 * 发送通知信息
	 * @param String $title
	 * @param String $content(link:超链接)
	 * @param int $type
	 * @return array $result
	 */
	function sendMessage($title,$content,$type = 0){
		$message = array(
						'title' => $title,
						'content' => $content,
						'type' => $type
					);
		if(!class_exists('lib_admin_message')) include 'model/base/lib_admin_message.php';
		$lib_message = new lib_admin_message();
		$result = $lib_message->sendMessage($message);
		return $result;
	}

}