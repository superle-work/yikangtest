<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_market_config')) require 'model/market/config/table/m_market_config.php';
/**
 * 互动营销配置
 * @name lib_market_config.php
 * @package message
 * @category modle
 * @link /model/lib_m_config.php
 * @author Lay
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-09-05
 */
class lib_market_config extends base_model{
    private $market_config;
    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct ();
        $this->market_config = new m_market_config();
    }
    
    /**
	 * 配置信息修改
	 * @param array $configInfo 
	 * @return array $result
	 */
	public function updateConfig($configInfo){
		$result = array();
		try{
			foreach($configInfo as  $key =>$perConfig){
				$condition = array("item_key" =>$key);
				$row = array("item_value" => $perConfig);
				$result = $this->market_config->update ($condition,$row );
			}
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 单个修改配置项
	 * @param array $condition
	 * @param array $row
	 * @return array $result
	 */
	public function updateConfigPer($condition,$row){
		try{
			$result = $this->market_config->update ($condition,$row );
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}

	/**
	 * 获取所有配置
	 * @param array $conditions
	 * @return array $reslut
	 */
	public function findAllConfig($item_value = 'all'){
		try {
			if(!$item_value){
				$reslut['share'] = $this->market_config -> findSql("SELECT * FROM market_config WHERE item_group != 'hidden'  AND tab = 'share' ORDER BY sort ASC");
			}else {
				$reslut = $this->market_config -> findSql("SELECT * FROM market_config WHERE item_group = '{$item_value}' ORDER BY sort ASC");
			}
			foreach ($reslut as $per){
			    $config[$per['item_key']] = $per['item_value'];
			}
			if(true == $reslut){
				return common::errorArray(0, "查询成功",$config);
			}else{
				return common::errorArray(1, "查询为空", $config);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取所有配置 不带标题
	 * @param array $conditions
	 * @return array $reslut
	 */
	public function findAllConfigComplete(){
		try {
			$reslut = $this->market_config -> findSql("SELECT * FROM market_config WHERE item_group != 'hidden'  ORDER BY sort ASC");
			if(true == $reslut){
				return common::errorArray(0, "查询成功",$reslut);
			}else{
				return common::errorArray(1, "查询为空", $reslut);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}

	/**
	 * 获取对应条件的配置
	 * @param array $condition
	 * @return array $reslut
	 */
	public function findConfig($condition){
		try {
			$reslut = $this->market_config -> find($condition);
			if(true == $reslut){
				return common::errorArray(0, "查询成功",$reslut);
			}else{
				return common::errorArray(1, "查询为空", $reslut);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
}