<?php
/**
 *控制器基类
 * @name base_controller.php
 * @package cws
 * @category base
 * @link http://www.changekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class base_controller extends spController{
	private $go = "./admin.php?c=base_main&a=index";
	
	//-----------------------------------系统配置-----------------------------
	/**
	 * 获取所有配置
	 * @return array data key=>value list
	 */
	protected function getConfig(){
		if(!class_exists('lib_config')) include 'model/base/lib_config.php';
		$lib_config = new lib_config();
		$result = $lib_config->findConfigKeyValue();
		return $result['data'];
	}
	
	/**
	 * 通过配置键名获取配置值
	 * @param string $key
	 * @return string $value
	 */
	protected function getConfigValue($key){
		if(!class_exists('lib_config')) include 'model/base/lib_config.php';
		$lib_config = new lib_config();
		$result = $lib_config->getConfigValue($key);
		return $result['data'];
	}
	
	/**
	 * 设置配置值
	 * @param string $key
	 * @param string $value
	 * @return Ambigous <>
	 */
	protected function setConfigKV($key,$value){
		if(!class_exists('lib_config')) include 'model/base/lib_config.php';
		$lib_config = new lib_config();
		$result = $lib_config->updateConfig(array('item_key'=>$key), array('item_value'=>$value));
		return $result;
	}
	
	//------------------日志管理-----------------
	
	/**
	 * 添加操作日志
	 * @param string $controller 控制器
	 * @param string $action 动作
	 * @param string $desc 描述
	 * @param int $is_page 0操作1页面2无权限
	 * @param string $type add添加del删除edit修改view查看
	 */
	protected function log($controller,$action,$desc,$is_page,$type){
		if(!$this->getConfigValue('is_debug') && $_SESSION['admin']['account'] != 'change'){//不为调试模式时开启日志记录
			$row['controller'] = $controller;
			$rawModeule = explode('_', $controller);
			$row['module'] =$rawModeule[0];
			$row['action'] = $action;
			$row['simple_desc'] = $desc;
			$row['is_page'] = $is_page;
			$row['type'] = $type;
			$row['account'] = $_SESSION['admin']['account'];
			$row['name'] = $_SESSION['admin']['admin_name'];
			$row['ip'] = common::getRealIp();
			$row['province'] = $_SESSION['province'];
			$row['city'] = $_SESSION['city'];
			if(!class_exists('lib_log')) include 'model/base/lib_log.php';
			$lib_log = new lib_log();
			$lib_log->deleteSomeLogs();//清除第999条之后的日志记录
			$lib_log->addLog($row);
			if($desc == '登录成功'){//清除之前的日志记录
				$lib_log->clearLog($this->getConfigValue('log_days'));
			}
		}
	}
	
	//-------------------------------------------权限管理-----------------------------------------
	
	/**
	 * 控制器权限验证
	 * @param array $session
	 * @param string $go
	 */
	protected function rightVerify($session,$go = ''){
		if (!isset($session)){
			if('' == $go){
				$go = $this->go;
			}
			echo "<html><head><meta http-equiv='refresh' content='0;url=".$go."'></head><body></body><ml>";
			echo "<script type='text/javascript'>window.location.href('".$go."');</script>";
			exit;
		}
	}
	
	//-------------------------------------------------构造提交表单------------------------------
	
	/**
	 * 构造页面提交数据
	 * @param object $controller
	 * @param array $keyList array('name','title','sort')
	 * @param bool $nullAllowed true defalut 允许传入空值 该参数在update的时候会用到
	 * @return array $argsList
	 */
	protected function getArgsList($controller,$keyList,$nullAllowed = true){
		foreach ($keyList as $key){
		    if($nullAllowed){
		        $argsList[$key] = $controller->spArgs($key);
		    }else{
		        if(null != $controller->spArgs($key) && '' != $controller->spArgs($key)){
		            $argsList[$key] = $controller->spArgs($key);
		        }
		    }
		}
		return $argsList;
	}
	
	/**
	 * 构造分页页面提交数据
	 * @param object $controller
	 * @param array $keyValueList array('name'=>'like','add_time'=>'>=','sort'=>'=')
	 * @return array
	 */
	protected function getPagingList($controller,$keyValueList){
		$conditionList = array();
		foreach ($keyValueList as $key=>$operator){
			if(null != $controller->spArgs($key) && '' != $controller->spArgs($key)){
				$dateKey = $this->isDateArea($key);
				if($dateKey){//判断是否时间段
					array_push($conditionList,  array("field" => $dateKey,"operator" => $operator,"value" => $this->spArgs($key)));
				}else{
					array_push($conditionList,  array("field" => $key,"operator" => $operator,"value" => $this->spArgs($key)));
				}
			}
		}
		return $conditionList;
	}
	
	/**
	 * 判断是否是时间区域
	 * @param string $key
	 * @return boolean
	 */
	private function isDateArea($key){
		$keyList = explode('_',$key);
		if($keyList[0] == 'from' || $keyList[0] == 'to'){
			unset($keyList[0]);
			$newKey = implode('_',$keyList);
			return $newKey;
		}else{
			return false;
		}
	}
	
	/**
	 * 获取分页参数
	 * @param object $controller key pageIndex ,pageSize
	 * @return array
	 */
	protected function getPageInfo($controller){
		// var_dump($controller);
		if(null != $controller->spArgs('pageIndex') && '' != $controller->spArgs('pageIndex')){
			$page['pageIndex'] = $controller->spArgs('pageIndex');
		}else{
			$page['pageIndex'] = 1;
		}
		if(null != $controller->spArgs('pageSize') && '' != $controller->spArgs('pageSize')){
			$page['pageSize'] = $controller->spArgs('pageSize');
		}else{
			$page['pageSize'] = 10;
		}
		return $page;
	}
	
	//-------------------------------------设置验证码--------------------------------------------
	/**
	 * 设置验证码验证码
	 * @param string $key $_SESSION[$key]
	 * @param float $width
	 * @param float $height
	 * @return fileStream 文件流
	 * $_SESSION['captcha']
	 */
	protected function setCaptcha($key = 'captcha',$width = 70,$height = 25){
	    if(!class_exists('Captcha')) include 'include/captcha.php';
	    // 实例化验证码
	    $captcha = new Captcha($width, $height);
	    // 清除之前出现的多余输入
	    @ob_end_clean();
	    $captcha->createCaptcha($key);
	}
	
}