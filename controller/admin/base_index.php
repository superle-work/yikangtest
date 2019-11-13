<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
/**
 *后台首页
 * @name base_index.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class base_index extends admin_controller{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
	}
	
    /**
	 * 主页
	 */
	function index(){
		$this->getSetMenu($this);
		//获取控制面板按钮
	    $sql = "SELECT a.* FROM base_menu as a left join base_menu_top as b on a.menu_top_id = b.id WHERE a.is_use = 1 and b.is_use = 1 and a.id !=1 ORDER BY sort asc LIMIT 12";
		if(!class_exists("lib_menu")) require "model/base/lib_menu.php";
		$lib_menu = new lib_menu();
	    $result = $lib_menu->findSql($sql);
	    $this->panel = $result;		
	    $this->display("../template/admin/{$this->theme}/base/index.html");
	}
	
	/**
	 * 清除缓存
	 */
	function clearCache(){
		//清除speed缓存
		$dir = "tmp/";
		if (false != ($handle = opendir ( $dir ))) {
			$i=0;
			while ( false !== ($file = readdir ( $handle )) ) {
				//去掉"“.”、“..”以及带“.xxx”后缀的文件
				if ($file != "." && $file != ".."&&strpos($file,".")) {
					@unlink($dir . $file);
				}
			}
			//关闭句柄
			closedir ( $handle );
		}
		//清除token
		$dir = "include/wechatUtil/data/";
		if (false != ($handle = opendir ( $dir ))) {
			$i=0;
			while ( false !== ($file = readdir ( $handle )) ) {
				//去掉"“.”、“..”以及带“.xxx”后缀的文件
				if ($file != "." && $file != ".."&&strpos($file,".")) {
					@unlink($dir . $file);
				}
			}
			//关闭句柄
			closedir ( $handle );
		}
		echo json_encode(common::errorArray(0, "清除完毕", true));
	}

	/**
	 * 切换皮肤
	 */
	function changeSkin(){
		$skin = $this->spArgs('admin_skin');
		$result = $this->setConfigKV('admin_skin', $skin);
        $this->log(__CLASS__, __FUNCTION__, "切换皮肤", 0, 'edit');
		echo json_encode($result);
	}

	/**
	 * 左边栏缩放
	 */
	function leftBarScale(){
		$left_bar_scale = $this->spArgs('left_bar_scale');
		$result = $this->setConfigKV('left_bar_scale', $left_bar_scale);
		echo json_encode($result);
	}
	
}