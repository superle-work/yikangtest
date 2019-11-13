<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
/**
 *用户管理
 * @name duo_config.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-09-01
 */
class store_config extends admin_controller{
    private $lib_store_config;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		$this->lib_store_config = new UtilConfig('store_config');
	}
	
	//--------------------------百货配置---------------------------------------
	/**
	 ** 百货配置页面
	 **/
	function storeConfig(){
	    $this->getSetMenu($this);
		$arr=array('fee','points','coupon','balance','fen','store');
        $configResult = $this->lib_store_config->findAllConfigComplete($arr);
        $this->configInfo = $configResult['data'];
	    //日志记录
        $this->log(__CLASS__, __FUNCTION__, "百货配置页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/config/storeConfig.html");
	}
    
	/**
	 * 设置夺宝币配置
	 */
	function setFeeRatioConfig(){
        $configInfo = $this->getConfigInfo($this->lib_store_config);
        $result = $this->lib_store_config->updateConfig($configInfo);
	    //日志记录
        $this->log(__CLASS__, __FUNCTION__, "设置夺宝币配置", 0, 'edit');
        echo json_encode($result);
    }
	
	/**
     * 构造修改信息
     * @param string lib_config
     * @return array
     */
    private function getConfigInfo($libObj){
        $configInfo = array();
		$arr=array('fee','points','coupon','balance','fen','store');
        $result = $libObj->findAllConfigComplete($arr);         //获取所有配置$result['data']
        foreach ($result['data'] as $per){
            $configInfo[$per['item_key']] = $this->spArgs($per['item_key']);
        }
        return $configInfo;
    }
}