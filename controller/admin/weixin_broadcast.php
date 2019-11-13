<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
/**
 * 微信群发消息管理
 * @name weixin_broadcast.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-13
 */
class  weixin_broadcast extends admin_controller{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	}
	
	/**
	 * 群发页面
	 */
	function broadcastPage(){
		$this->getSetMenu($this);
		$groupList = FansManage::getGroupList();
		$this->groupList = $groupList['groups'];
		$this->display("../template/admin/{$this->theme}/weixin/material/page/broadcastPage.html");
	}
	
	/**
	 * 发送给对应的粉丝预览
	 */
	function previewMaterialByGroup(){
		$openId = $this->spArgs("opdeId");
		$type = $this->spArgs("type");
		if($type == "news"){
			$mediaId = $this->spArgs('mediaId');
			$result = Broadcast::previewNewsByGroup($openId, $mediaId);
		}else if($type == "text"){
			$content = $this->spArgs('content');
			$result = Broadcast::previewTextByGroup($openId, $content);
		}else if($type == "voice"){
			$mediaId = $this->spArgs('mediaId');
			$result = Broadcast::previewVoiceByGroup($openId, $mediaId);
		}else if($type == "image"){
			$mediaId = $this->spArgs('mediaId');
			$result = Broadcast::previewImageByGroup($openId, $mediaId);
		}else if($type == "video"){
			$mediaId = $this->spArgs('mediaId');
			$title = $this->spArgs('title');
			$description = $this->spArgs('description');
			$result = Broadcast::previewVideoByGroup($mediaId, $title, $description, $openId);
		}
		echo $result;
	}
	
	/**
	 * 群发接口
	 */
	function broadcast(){
		$groupId = $this->spArgs('groupId');
		$isToAll = $this->spArgs('isToAll');
		$type = $this->spArgs('type');
		if($type == 'news'){//图文消息
			$mediaId = $this->spArgs('mediaId');
			$result = Broadcast::sentNewsByGroup($groupId, $mediaId, $isToAll);
		}else if($type == 'text'){//文本消息
			$content = $this->spArgs('content');
			$result = Broadcast::sentTextByGroup($groupId, $content, $isToAll);
		}else if($type == 'voice'){//声音消息
			$mediaId = $this->spArgs('mediaId');
			$result = Broadcast::sentVoiceByGroup($groupId, $mediaId, $isToAll);
		}else if($type == 'image'){//图片消息
			$mediaId = $this->spArgs('mediaId');
			$result = Broadcast::sentImageByGroup($groupId, $mediaId, $isToAll);
		}else if($type == 'video'){//视频消息
			$mediaId = $this->spArgs('mediaId');
			$title = $this->spArgs('title');
			$description = $this->spArgs('description');
			$result = Broadcast::sentVideoByGroup($mediaId, $title, $description, $groupId, $isToAll);
		}
		echo $result;
	}
	
	/**
	 * 新建图文消息
	 */
	function uploadNews(){
		$articles = $this->spArgs('articles');
		$mediaId = Broadcast::uploadNews($articles);
		echo $mediaId;
	}
	
}