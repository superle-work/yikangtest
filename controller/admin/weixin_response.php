<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_response')) require 'model/weixin/lib_response.php';

/**
 * 微信智能回复
 * @name weixin_response.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-13
 */
class  weixin_response extends admin_controller{
	private $lib_response;
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		$this->lib_response = new lib_response();
	}
	
	/**
	 * 智能回复页面
	 */
	function responsePage(){
		$this->getSetMenu($this);
		$useResult =  $this->lib_response->fileConfigObj->getFileConfigValue('is_use');
		$this->isUse = $useResult['data'];//智能回复功能开关标识，1 启用，0 停用
		if($useResult['data'] ){
			$messageResult = $this->lib_response->findMessage(array('use_scene'=>0));//被关注自动回复
			$this->attentionMessage = $messageResult['data'];
			$messageResult = $this->lib_response->findMessage(array('use_scene'=>1));//统一回复
			$this->allMessage = $messageResult['data'];
			$messageResult = $this->lib_response->findAllRule();//关键词回复
			$this->keywordsMessage = $messageResult['data'];
		}
		//对应页面的某个导航模块 attentionMessage，allMessage，keywordsMessage
		$navFlag = $this->spArgs("navFlag");
		$this->navFlag = $navFlag;
		$this->log(__CLASS__, __FUNCTION__, "智能回复页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/weixin/response/page/responsePage.html");
	}
	
	/**
	 * 启用停用智能回复
	 */
	function useResponse(){
		$responseConfig['is_use'] =  $this->spArgs('is_use');
		$result = $this->lib_response->fileConfigObj->setFileKeyValue('is_use', $this->spArgs('is_use'));
		$this->log(__CLASS__, __FUNCTION__, "启用停用智能回复", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 修改回复消息
	 */
	function updateMessage(){
		$messageInfo['type'] = $this->spArgs('type');//text文字image图片news图文连接voice音频vedio视频
		$messageInfo['content'] = $this->spArgs('content');
		$messageInfo['media_id'] = $this->spArgs('media_id');
		$messageInfo['url'] = $this->spArgs('url');

		$messageInfo['use_scene'] = $this->spArgs('use_scene');
		if($this->spArgs('id')){
			$result  = $this->lib_response->updateMessage(array('id'=>$this->spArgs('id')), $messageInfo);
		}else{
			$result  = $this->lib_response->addMessage($messageInfo);
		}
		$this->log(__CLASS__, __FUNCTION__, "修改回复消息", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 启用停用消息
	 */
	function useMessage(){
		$messageInfo['is_use'] = $this->spArgs('is_use');
		$result  = $this->lib_response->updateMessage(array('id'=>$this->spArgs('id')), $messageInfo);
		$this->log(__CLASS__, __FUNCTION__, "启用停用消息", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 添加回复规则
	 */
	function addRule(){
		//规则信息
		$ruleInfo['rule_name'] = $this->spArgs('rule_name');
		$ruleInfo['keywords'] = $this->spArgs('keywords');
		$ruleInfo['is_use'] = $this->spArgs('is_use');
		//消息信息
		$messageInfo['type'] = $this->spArgs('type');//text文字image图片news图文连接voice音频vedio视频
		$messageInfo['content'] = $this->spArgs('content');
		$messageInfo['media_id'] = $this->spArgs('media_id');
		$messageInfo['url'] = $this->spArgs('url');
		$messageInfo['is_use'] = $this->spArgs('is_use');
		$result  = $this->lib_response->addRule($ruleInfo, $messageInfo);
		$this->log(__CLASS__, __FUNCTION__, "添加回复规则", 0, 'add');
		echo json_encode($result);
	}
	
	/**
	 * 修改回复规则
	 */
	function updateRule(){
		//规则基本信息
		$ruleInfo['rule_name'] = $this->spArgs('rule_name');
		$ruleInfo['keywords'] = $this->spArgs('keywords');
		$ruleInfo['is_use'] = $this->spArgs('is_use');
		$ruleInfo['message_id'] = $this->spArgs('message_id');
		//回复消息信息
		$messageInfo['type'] = $this->spArgs('type');//text文字image图片news图文连接voice音频vedio视频
		$messageInfo['content'] = $this->spArgs('content');
		$messageInfo['media_id'] = $this->spArgs('media_id');
		$messageInfo['url'] = $this->spArgs('url');
		$result  = $this->lib_response->updateRule(array('id'=>$this->spArgs('id')), $ruleInfo,$messageInfo);
		$this->log(__CLASS__, __FUNCTION__, "修改回复规则", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 启用停用规则
	 */
	function useRule(){
		$ruleInfo['is_use'] = $this->spArgs('is_use');
		$result  = $this->lib_response->updateRuleJust(array('id'=>$this->spArgs('id')), $ruleInfo);
		$this->log(__CLASS__, __FUNCTION__, "启用停用回复规则", 0, 'edit');
		echo json_encode($result);
	}

	/**
	 ** 删除规则
	 */
	 function deleteRule(){
	    $result  = $this->lib_response->deleteRule($this->spArgs('id'));
	    $this->log(__CLASS__, __FUNCTION__, "删除回复规则", 0, 'del');
        echo json_encode($result);
	 }
	 
	 /**
	  * 上传自定义图文图片
	  */
	 function uploadMyMessageImage(){ 
	     //验证图片能否上传
	     if($_FILES) $verify = UtilImage::verifyImage();
	     if($verify['errorCode'] == 1) exit(json_encode($verify));
	     $prevUrl = $this->spArgs('prevUrl');
	     if($prevUrl){
	      unlink($prevUrl);   
	     }
	     $url = UtilImage::uploadPhotoJust('imgurl', 'upload/image/weixin/myNews/'.date('Ymd'));
	     if($url){
	         echo json_encode(common::errorArray(0, "上传成功", $url));
	     }else{
	         echo json_encode(common::errorArray(1, "上传失败", false));
	     }
	 }
	 
	 /**
	  * 删除自定义图文图片
	  */
	 function deleteMyMessageImage(){
	     $picUrl = $this->spArgs('picUrl');
	     if(file_exists($picUrl)){
	         if(unlink($picUrl)){
	             echo json_encode(common::errorArray(0, "删除成功", true));
	         }else{
	             echo json_encode(common::errorArray(1, "删除失败", false));
	         }
	     }else{
	         echo json_encode(common::errorArray(0, "文件不存在", true));
	     }
	 }
}