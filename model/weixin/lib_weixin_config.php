<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_weixin_config')) require 'model/weixin/table/m_weixin_config.php';
/**
 * 微信配置业务
 * @name lib_config.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-05
 */
class lib_weixin_config extends base_model{
    private $m_config;
    
    /**
     * 构造函数
     */
    function __construct(){
        parent::__construct();
        $this->m_config = new m_weixin_config();
    }
    
    /**
	 * 根据配置列表修改
	 * @param array $configList kev=>value list
	 * @return array $result
	 */
	public function updateConfigList($configList){
		try{
			foreach($configList as  $key =>$value){
				$result = $this->m_config->update(
							array("item_key" => $key),array("item_value" => $value)
						);
			}
			return common::errorArray(0, "修改成功", true);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}

	/**
	 * 根据配置列表修改
	 * @param array $configList kev=>value list
	 * @return array $result
	 */
	public function updateConfig($conditions,$row){
		try{
			$result = $this->m_config->update($conditions, $row);
			if(true == $result){
				return common::errorArray(0, "修改成功",$result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取所有配置用户页面配置元素提取
	 * @return array $result
	 */
	public function getConfigForGet(){
		try {
			$reslut = $this->m_config -> findSql("SELECT * FROM weixin_config WHERE type != 'hidden' ORDER BY sort ASC");
			if($reslut){
				return common::errorArray(0, "查询成功",$reslut);
			}else{
				return common::errorArray(1, "查询为空", $reslut);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 通过键名获取配置值
	 * @param string $key 键名
	 * @return array $result
	 */
	public function getConfigValue($key){
		try {
			$reslut = $this->m_config -> find(array('item_key'=>$key));
			if(true == $reslut){
				return common::errorArray(0, "查询成功",$reslut['item_value']);
			}else{
				return common::errorArray(1, "查询为空", '');
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取多个配置
	 * @param array $condition
	 * @return array $result
	 */
	public function findAllConfig($condition,$sort){
		try {
			$reslut = $this->m_config -> findAll($condition,$sort);
			if(true == $reslut){
				return common::errorArray(0, "查询成功",$reslut);
			}else{
				return common::errorArray(1, "查询为空",$reslut);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取对应条件的配置
	 * @param array $condition
	 * @return array $reslut
	 */
	public function findConfig($condition){
		try {
			$reslut = $this->m_config -> find($condition);
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
	 * 添加配置
	 * @param array $row
	 * @return array $result
	 */
	public function addConfig($row){
		try {
			$keyResult = $this->m_config->find(array('item_key'=>$row['item_key']));
			if($keyResult){
				return common::errorArray(1, "key不能重复", false);
			}
			$this->clearCache();//添加时，清除对应模块缓存
			$addId = $this->m_config -> create($row);
			if(true == $addId){
				return common::errorArray(0, "添加成功",$addId);
			}else{
				return common::errorArray(1, "添加失败", false);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取所有配置key=>value
	 * @return array $result
	 */
	public function findConfigKeyValue(){
		try {
			$sql = "SELECT * FROM weixin_config ORDER BY sort ASC";
			$reslut = $this->m_config -> findSql($sql);
			foreach ($reslut as $per){
				$config[$per['item_key']] = $per['value'];
			}
			if(true == $reslut){
				return common::errorArray(0, "查询成功",$config);
			}else{
				return common::errorArray(1, "查询为空", $config);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 清除配置缓存
	 * @param string $module
	 */
	public function clearCache($module = 'all'){
		spAccess('c',  'weixinConfigCache');//config缓存必然修改
	}
}