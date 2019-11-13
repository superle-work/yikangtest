<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_service')) require 'model/mall/lib_service.php';
/**
 *商城客服管理
 * @name store_service.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class store_service extends admin_controller{
	private $lib_service;
	
	/**
	 * 构造方法
	 */
	function __construct(){
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		$this->lib_service = new lib_service();
	}
	
	/**
	 * 客服人员列表
	 */
	function serviceList(){
		$this->getSetMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "客服人员列表", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/serviceList.html");
	}
	
	/**
	 * 添加客服页面
	 */
	function addService(){
		$this->setMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "添加客服", 1, 'add');
		$this->display("../template/admin/{$this->theme}/store/page/service/addService.html");
	}
	
	/**
	 * 编辑客服
	 */
	function editService(){
		$this->setMenu($this);
		$sid = $this->spArgs('id');
		$serviceResult = $this->lib_service->findService(array('id' => $sid));
		$this->serviceInfo = $serviceResult['data'];
		$this->log(__CLASS__, __FUNCTION__, "编辑客服", 1, 'edit');
		$this->display("../template/admin/{$this->theme}/store/page/service/editService.html");
	}
	
	/**
	 * 客服人员分页信息
	 */
	function pagingService(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('name'=>'like', 'type'=>'=', 'online'=>'=');
		$conditionList = $this->getPagingList($this, $keyValueList);
		$result = $this->lib_service->pagingService($page, $conditionList);
		echo json_encode($result);
		
	}
	
	/**
	 * 添加客服
	 */
	function insertService(){
		$keyList = array('name', 'type', 'sort', 'online', 'head_img');
		$serviceInfo = $this->getArgsList($this, $keyList);
		if($serviceInfo['type'] == '1'){
			$serviceInfo['number'] = $this->spArgs('qq_number');
		}else if($serviceInfo['type'] == '2'){
			if(!empty($_FILES)) $imageResult = common::uploadPhotoJust('weichat_number','image/mall/service/');
			if($imageResult){
				$serviceInfo['number'] = $imageResult;;
			}
		}else if($serviceInfo['type'] == '3'){
			$serviceInfo['number'] = $this->spArgs('phone_number');
		}
		$result = $this->lib_service->addservice( $serviceInfo );
		$this->log(__CLASS__, __FUNCTION__, "添加客服", 0, 'add');
		echo json_encode($result);
	}
	
	/**
	 * 更新客服
	 */
	function updateService(){
		$sid = $this->spArgs('sid');
		$keyList = array('name', 'type', 'sort', 'online', 'head_img');
		$serviceInfo = $this->getArgsList($this, $keyList);
		if($serviceInfo['type'] == '1'){
		    $result = $this->lib_service->getService(array('id' => $sid));
		    if($result['data']['type'] == 2){
		        @unlink($result['data']['number']);//删除原微信二维码图片
		    }
			$serviceInfo['number'] = $this->spArgs('qq_number');
		}else if($serviceInfo['type'] == '2'){
			if($this->spArgs('imgFlag') == 1){//判断是否需要重新上传图片
			    //更新图片
				$prevUrl = $this->spArgs('prevUrl');
				$resultImg = common::uploadPhotoJust('weichat_number', 'image/mall/service/main/' . date('Ymd'),300,300);
				if($resultImg){
					$serviceInfo['number'] = $resultImg;
					if($prevUrl){
					  @unlink($prevUrl);//delete url
					}
				}else{
					echo json_encode(common::errorArray(1, "修改主图失败", 0));
					exit;
				}
			}
		}else if($serviceInfo['type'] == '3'){
		    $result = $this->lib_service->getService(array('id' => $sid));
		    if($result['data']['type'] == 2){
		        @unlink($result['data']['number']);//删除原微信二维码图片
		    }
			$serviceInfo['number'] = $this->spArgs('phone_number');
		}
		$result = $this->lib_service->updateService( array('id' => $sid), $serviceInfo );
		$this->log(__CLASS__, __FUNCTION__, "更新客服", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 删除客服
	 */
	function deleteService(){
		$ids = $this->spArgs('ids');
		$result = $this->lib_service->deleteService($ids);
		$this->log(__CLASS__, __FUNCTION__, "删除客服", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 改变上下线
	 */
	function changeOnline(){
		$online = $this->spArgs('online');
		$id = $this->spArgs('id');
		$result = $this->lib_service->updateService(array('id' => $id), array('online' => $online));
		$this->log(__CLASS__, __FUNCTION__, "改变上下线", 0, 'edit');
		echo json_encode($result);
	}
}