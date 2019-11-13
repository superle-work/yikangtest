<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
/**
 * 微信粉丝管理
 * @name weixin_fans.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-13
 */
class  weixin_fans extends admin_controller{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	}
	
	/**
	 * 粉丝列表页面
	 */
	function fansList(){
		$this->getSetMenu($this);
		//粉丝列表
		if(!spAccess('r' , 'openidList')){
			$rawFansList = FansManage::getAllFansList();
			$openIdList = $rawFansList['data']['openid'];
			$pageSize = 10;
			$totalPage = ceil($rawFansList['total'] / $pageSize);
			$result = array_chunk($openIdList, $pageSize);
			spAccess('w' , 'openidList',  array('total_count' => $rawFansList['total'],'total_page'=> $totalPage,'data'=>$result), 3600);
		}
		//分组列表
		if(!spAccess('r' , 'groupList')){
			$groups = FansManage::getGroupList();
			spAccess('w' , 'groupList',$groups['groups']);
		}
		$this->log(__CLASS__, __FUNCTION__, "粉丝列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/weixin/fans/page/fansList.html");
	}
	
	/**
	 * 分页查询粉丝信息
	 */
	function pagingFans(){
		if($this->spArgs('pageIndex')){
			$pageIndex = $this->spArgs('pageIndex');
		}else{
			$pageIndex = 1;
		}
		$openidList = spAccess('r' , 'openidList');
		if($openidList){
			$uesrInfoList = FansManage::getFansInfoBatch($openidList['data'][$pageIndex - 1]);
			foreach ($uesrInfoList['user_info_list'] as &$user){
				$groupInfo = FansManage::getGroupById($user['groupid'], spAccess('r' , 'groupList'));
				$user['groupname'] = $groupInfo['name'];
				$user['groupcount'] = $groupInfo['count'];
			}
			$openidList = spAccess('r' , 'openidList');
			$result = array('page_index'=>$pageIndex,'total_page'=>$openidList['total_page'],'data'=>$uesrInfoList['user_info_list']);
			echo json_encode(common::errorArray(0, "查询成功", $result));
		}else{
			echo json_encode(common::errorArray(1, "无数据", false));
		}
	}
	
}