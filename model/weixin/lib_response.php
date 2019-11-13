<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_weixin_message')) require 'model/weixin/table/m_weixin_message.php';
if(!class_exists('m_weixin_rule')) require 'model/weixin/table/m_weixin_rule.php';
/**
 * 提供自动回复管理服务
 * @name lib_response.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-05
 */
class lib_response extends base_model{
    
    public $fileConfigObj;//文件配置对象

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct ();
        include 'include/UtilJson.php';
        $this->fileConfigObj = new UtilJson('model/weixin/response.json');
    }
    
	/**
	 * 添加自动回复消息
	 * @param array $messageInfo
	 * @return array $result
	 */
	function addMessage($messageInfo){
		$m_message = new m_weixin_message();
		try {
			$messageInfo['add_time'] = date("Y-m-d H:i:s",time());
			$id = $m_message->create($messageInfo);
			if($id){
				return common::errorArray(0, "添加成功", $id);
			}else{
				return common::errorArray(1, "添加失败", $id);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 查看某个自动回复消息
	 * @param array $condition
	 * @return array $result
	 */
	function findMessage($condition){
		$m_message = new m_weixin_message();
		try {
			$result = $m_message->find($condition);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 删除自动回复消息
	 * @param string $ids
	 * @return array $result
	 */
	function deleteMessage($ids){
		$m_message = new m_weixin_message();
		try {
			$sql = "DELETE FROM weixin_message where id in({$ids})";
			$result = $m_message->runSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 更新自动回复消息
	 * @param array $condition
	 * @param array $messageInfo
	 * @return array $result
	 */
	function updateMessage($condition,$messageInfo){
		$m_message = new m_weixin_message();
		try {
			$result = $m_message->update($condition,$messageInfo);
			if($result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 更新回复规则包括规则回复的消息
	 * @param array $condition
	 * @param array $ruleInfo
	 * @param array $messageInfo
	 * @return array $result
	 */
	function updateRule($condition,$ruleInfo,$messageInfo){
		$m_rule = new m_weixin_rule();
		$m_message = new m_weixin_message();
		try {
			$result = $m_rule->update($condition,$ruleInfo);
			$m_message->update(array('id'=>$ruleInfo['message_id']),$messageInfo);
			if($result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 更新回复规则
	 * @param array $condition
	 * @param array $ruleInfo
	 * @return array $result
	 */
	function updateRuleJust($condition,$ruleInfo){
		$m_rule = new m_weixin_rule();
		$m_message = new m_weixin_message();
		try {
			$result = $m_rule->update($condition,$ruleInfo);
			if($result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 添加自动回复消息
	 * @param array $ruleInfo
	 * @param array $messageInfo 构造messageInfo
	 * @return array $result
	 */
	function addRule($ruleInfo,$messageInfo){
		$m_rule = new m_weixin_rule();
		try {
			$messageInfo['use_scene'] = "2";//.关键词自动回复
			$result = $this->addMessage($messageInfo);
			$ruleInfo['message_id'] = $result['data'];
			$ruleInfo['add_time'] = date("Y-m-d H:i:s",time());
			$id = $m_rule->create($ruleInfo);
			if($id){
				return common::errorArray(0, "添加成功", $id);
			}else{
				return common::errorArray(1, "添加失败", $id);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}

	/**
	 * 查看所有自动回复规则
	 * @param array $condition
	 * @return array $result
	 */
	function findAllRule($condition){
		$m_rule = new m_weixin_rule();
		try {
			if($condition['is_use'] != null && $condition['is_use'] != ''){
				$sql = "SELECT r.id,r.rule_name,r.keywords,r.message_id,r.add_time,r.is_use as r_is_use,
						m.content,m.media_id,m.type,m.url,m.use_scene,m.is_use as m_is_use FROM weixin_rule as r 
						left join weixin_message as m 
						on r.message_id = m.id where r.is_use = {$condition['is_use']} ORDER BY r.add_time DESC";
			}else{
				$sql = "SELECT r.*,m.content,m.media_id,m.type,m.url,m.use_scene FROM weixin_rule as r left join weixin_message as m on r.message_id = m.id  ORDER BY r.add_time DESC";
			}
			$result = $m_rule->findSql($sql);
			if(!$condition['is_match']){//后台展示需要对象结果，而匹配是在需要|类型的正则
				foreach ($result as &$per){
					$per['keywords'] = explode('|', $per['keywords']);
				}
			}
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 删除自动回复规则
	 * @param string $ids
	 * @return array $result
	 */
	function deleteRule($ids){
		$m_rule = new m_weixin_rule();
		$m_message = new m_weixin_message();
		try {
			//删除信息
			$idList = split(',', $ids);
			$mids = "";
			foreach ($idList  as $id){
				$ruleInfo = $m_rule->find(array('id'=>$id));
				$mids .= ",{$ruleInfo['message_id']}";
			}
			$mids = trim($mids,',');
			$this->deleteMessage($mids);
			//删除规则
			$sql = "delete from weixin_rule where id in({$ids})";
			$result = $m_rule->runSql($sql);
			if($result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取配置
	 * @return array 配置项列表 key=>value
	 */
	public function getResponseConfig(){
	    if(!class_exists('UtilJson')) include 'include/UtilJson.php';
	    return UtilJson::getJsonFile("model/weixin/response.json");
	}
	
	/**
	 * 设置配置
	 * @param string $json
	 */
	public function setResponseConfig($json){
	    if(!class_exists('UtilJson')) include 'include/UtilJson.php';
	    return UtilJson::setJsonFile("model/weixin/response.json",$json);
	}
	
}
