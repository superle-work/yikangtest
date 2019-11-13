<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
/**
 *千界科技开发后台配置
 * @name base_config.php
 * @package cws
 * @category controller
 * @link http://www.changekeji.com
 * @author jianfang
 * @version 1.0
 * @since 2015-10-26
 */
class base_config extends admin_controller{
    private $lib_base_config;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		$this->lib_base_config = new UtilConfig('base_config');
	}
	
	/**
	 * 基础配置页面
	 */
	function baseConfig(){
	    $this->getSetMenu($this);
	    $configResult = $this->lib_base_config->findAllConfigByType();
	    $this->configInfo = $configResult['data'];
	    $this->display("./template/admin/{$this->theme}/common/page/baseConfig.html");
	}	
	
	/**
	 * 修改配置
	 */
	function setConfig(){
	    $page = $this->spArgs('page');
	    if($page == 'baseConfig'){//基础配置
	        $result = $this->setTableConfig('base_config');
	        echo json_encode($result);
	    }
	}
	
	/**
	 * 设置商城模板配置
	 */
	private function setTableConfig($table){
	    $configInfo = $this->getConfigInfo($lib_config);
	    $result = $this->lib_base_config->updateConfig($configInfo);
	    return $result;
	}
}