<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
/**
 * 提供各模块配置表管理服务
 * @name UtilConfig
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-29
 */
class UtilConfig extends base_model{
    public $table;
    public $pk;
    
    function __construct($table,$pk = 'id'){
        $this->table = $table;
        $this->pk = $pk;
        parent::__construct();
    }
	
    /**
	 * 根据配置列表修改
	 * @param array $configList kev=>value list
	 * @return array $result
	 */
	public function updateConfigList($configList){
		try{
			foreach($configList as  $key =>$value){
				$result = $this->update(
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
    public function updateConfig($configInfo){
		$result = array();
		try{
			foreach($configInfo as  $key =>$perConfig){
				$condition = array("item_key" =>$key);
				$row = array("item_value" => $perConfig);
				$result = $this->update ($condition,$row );
			}
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取所有配置用户页面配置元素提取
	 * @return array $result
	 */
	public function getConfigForGet(){
		try {
			$result = $this -> findSql("SELECT * FROM {$this->table} WHERE type != 'hidden' ORDER BY sort ASC");
			if($result){
				return common::errorArray(0, "查询成功",$result);
			}else{
				return common::errorArray(1, "查询为空", $result);
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
			$result = $this -> find(array('item_key'=>$key));
			if(true == $result){
				return common::errorArray(0, "查询成功",$result['item_value']);
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
	public function findAllConfig($condition){
		try {
			$result = $this-> findAll($condition);
			if(true == $result){
				return common::errorArray(0, "查询成功",$result);
			}else{
				return common::errorArray(1, "查询为空",$result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	/**
	 * 获取对应条件的配置
	 * @param array $condition
	 * @return array $result
	 */
	public function findConfig($condition){
	    try {
	        $result = $this-> find($condition);
	        if(true == $result){
	            return common::errorArray(0, "查询成功",$result);
	        }else{
	            return common::errorArray(1, "查询为空", $result);
	        }
	    }catch(Exception $ex){
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
			$keyResult = $this->find(array('item_key'=>$row['item_key']));
			if($keyResult){
				return common::errorArray(1, "key不能重复", false);
			}
			$this->clearCache();//清除对应模块缓存
			$addId = $this -> create($row);
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
	public function findConfigKeyValue($item_group = 'all'){
		try {
			//$sql = "SELECT * FROM base_config ORDER BY sort ASC";
			if($item_group == 'all'){
			    $sql = "SELECT * FROM {$this->table} WHERE type != 'hidden'  ORDER BY sort ASC";
			}else{
			    $sql = "SELECT * FROM {$this->table} WHERE type != 'hidden' AND item_group = '{$item_group}'  ORDER BY sort ASC";
			}
			$result = $this -> findSql($sql);
			foreach ($result as $per){
				$config[$per['item_key']] = $per['item_value'];
			}
			if(true == $result){
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
	 * 获取所有配置key=>value
	 * @return array $result
	 */
	public function findConfigThemeValue($item_group = 'all'){
		try {
			//$sql = "SELECT * FROM base_config ORDER BY sort ASC";
			if($item_group == 'all'){
			    $sql = "SELECT * FROM {$this->table} ORDER BY sort ASC";
			}else{
			    $sql = "SELECT * FROM {$this->table} WHERE item_group = '{$item_group}'  ORDER BY sort ASC";
			}
			$result = $this -> findSql($sql);
			foreach ($result as $per){
				$config[$per['item_key']] = $per['item_value'];
			}
			if(true == $result){
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
	 * 获取所有键值对配置
	 * @param array $tab all order sms ticket
	 * 查询与下列查询结果相同
	 * $result['ticket'] = $this->m_trip_config->findSql("SELECT * FROM trip_config WHERE type != 'hidden'AND item_group = 'ticket' ORDER BY sort ASC");
	 * $result['order'] = $this->m_trip_config->findSql("SELECT * FROM trip_config WHERE type != 'hidden' AND item_group = 'order' ORDER BY sort ASC");
	 * $result['sms'] = $this->m_trip_config->findSql("SELECT * FROM trip_config WHERE type != 'hidden'AND item_group = 'sms' ORDER BY sort ASC");
	 * $result['mail'] = $this->m_trip_config->findSql("SELECT * FROM trip_config WHERE type != 'hidden'AND item_group = 'mail' ORDER BY sort ASC");
	 * $result['template'] = $this->m_trip_config->findSql("SELECT * FROM trip_config WHERE type != 'hidden'AND item_group = 'template' ORDER BY sort ASC");
	 * $result['fee'] = $this->m_trip_config->findSql("SELECT * FROM trip_config WHERE type != 'hidden'AND item_group = 'fee' ORDER BY sort ASC");
	 * $result['points'] = $this->m_trip_config->findSql("SELECT * FROM trip_config WHERE type != 'hidden'AND item_group = 'points' ORDER BY sort ASC");
	 * $result['coupon'] = $this->m_trip_config->findSql("SELECT * FROM trip_config WHERE type != 'hidden'AND item_group = 'coupon' ORDER BY sort ASC");
	 * @return array $result
	 */
	public function findAllConfigByType(){
	    try {
            $sql = "SELECT * FROM {$this->table} WHERE type != 'hidden'  ORDER BY sort ASC";
	        $result = $this->findSql($sql);
	        foreach ($result as $per){
	            $config[$per['item_group']][] = $per;
	        }
	        if(true == $result){
	            return common::errorArray(0, "查询成功",$config);
	        }else{
	            return common::errorArray(1, "查询为空", $config);
	        }
	    }catch(Exception $ex){
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}
	
	/**
	 * 获取所有键值对配置 不是dev存在group组
	 * @param array $tab all order sms ticket
	 * 查询与下列查询结果相同
	 * $result['ticket'] 
	 * $result['order'] 
	 * $result['sms'] 
	 * $result['mail'] 
	 * $result['template'] 
	 * $result['fee'] 
	 * $result['points'] 
	 * $result['coupon'] 
	 * @return array $result
	 */
	public function findNotOftenConfig(){
	    try {
            $sql = "SELECT * FROM {$this->table} WHERE type != 'hidden' and item_group != 'ticket' and item_group != 'order'  and item_group != 'sms'
		   	and item_group != 'balance' and item_group != 'mail'  and item_group != 'template' and item_group != 'fee' and item_group != 'points'  
		   	and item_group != 'fen' and item_group != 'balance' and item_group != 'coupon' ORDER BY sort ASC";
	        $result = $this->findSql($sql);
	        foreach ($result as $per){
	            $config[$per['item_group']][] = $per;
	        }
	        if(true == $result){
	            return common::errorArray(0, "查询成功",$config);
	        }else{
	            return common::errorArray(1, "查询为空", $config);
	        }
	    }catch(Exception $ex){
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}	
	
	/**
	 * 获取所有配置 不带标题
	 * @param array $conditions
	 * @return array $result
	 */
	public function findAllConfigComplete($item_value = "all"){
	    try {
	    	if(is_array($item_value)){
	    		$strCondition=null;
	    		foreach ($item_value as $value) {
	    			$strCondition.="'".$value."',";
	    		}
				$strCondition = rtrim($strCondition,',');
				 $sql = "SELECT * FROM {$this->table} WHERE type != 'hidden' AND item_group in ({$strCondition})  ORDER BY sort ASC";
	    	}else{
	    		 if($item_value == 'all'){
		          		$sql = "SELECT * FROM {$this->table} WHERE type != 'hidden'  ORDER BY sort ASC";
		        	}else{
		        		$sql = "SELECT * FROM {$this->table} WHERE type != 'hidden' AND item_group = '{$item_value}'  ORDER BY sort ASC";
					}
	    	}
	       
	        $result = $this->findSql($sql);
	        if(true == $result){
	            return common::errorArray(0, "查询成功",$result);
	        }else{
	            return common::errorArray(1, "查询为空", $result);
	        }
	    }catch(Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}
	
	/**
	 * 清除配置缓存
	 * @param string $module
	 */
	public function clearCache($module = 'all'){
		spAccess('c',  "{$this->table}Cache");//config缓存必然修改
	}
	
}
