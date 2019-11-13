<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
/**
 *公共的卡券、积分、分销、积分提现设置等
 * @name duo_config.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-09-01
 */
class common_config extends admin_controller{
    private $lib_config;
    private $module = 'hotel';
    private $moduleConfig = 'hotel_config';
	/**
	 * 构造函数
	 */
	function __construct($module) {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		$this->lib_config = new UtilConfig($moduleConfig);
	}
	
	//--------------------------百货配置---------------------------------------
	/**
	 ** 百货配置页面
	 **/
	function commonConfig(){
	    $this->getSetMenu($this);
		$arr=array('fee','points','coupon','balance','fen');
        $configResult = $this->lib_config->findAllConfigComplete($arr);
        $this->configInfo = $configResult['data'];
	    //日志记录
        $this->log(__CLASS__, __FUNCTION__, "配置页面", 1, 'view');
        $tempName = $this->module . "Config.html";
		$this->display("../template/admin/{$this->theme}/{$this->module}/page/config/{$tempName}");
	}
    
	/**
	 * 修改配置文件
	 */
	function setFeeRatioConfig(){
        $configInfo = $this->getConfigInfo($this->lib_config);
        $result = $this->lib_config->updateConfig($configInfo);
	    //日志记录
        $this->log(__CLASS__, __FUNCTION__, "设置配置信息", 0, 'edit');
        echo json_encode($result);
    }
	
	/**
     * 构造修改信息
     * @param string lib_config
     * @return array
     */
    private function getConfigInfo($libObj){
        $configInfo = array();
		$arr=array('fee','points','coupon','balance','fen');
        $result = $libObj->findAllConfigComplete($arr);         //获取所有配置$result['data']
        foreach ($result['data'] as $per){
            $configInfo[$per['item_key']] = $this->spArgs($per['item_key']);
        }
        return $configInfo;
    }
}