<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_service')) require 'model/base/lib_service.php';
/**
 *客服管理
 * @name base_service.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class base_service extends admin_controller{
    private $lib_service;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->lib_service = new lib_service();
	}
	
    /**
	 * 客服列表页面
	 */
	function serviceList(){
		$this->getSetMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "客服列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/service/page/serviceList.html");
	}
	
	/**
	 * 添加客服页面
	 */
	function addService(){
	    $this->getMenu($this);
	    $this->log(__CLASS__, __FUNCTION__, "添加客服页面", 1, 'add');
		$this->display("../template/admin/{$this->theme}/base/service/page/addService.html");
	}
	
	/**
	 * 编辑客服页面
	 */
	function editService(){
	    $this->getMenu($this);
	    $sid = $this->spArgs('id');
	    $serviceResult = $this->lib_service->findService(array('id' => $sid));
	    $this->serviceInfo = $serviceResult['data'];
	    $this->log(__CLASS__, __FUNCTION__, "编辑客服页面", 1, 'edit');
	    $this->display("../template/admin/{$this->theme}/base/service/page/editService.html");
	}
	
	/**
	 * 添加客服
	 */
	function insertService(){
	    //验证图片能否上传
	    if($_FILES) $verify = UtilImage::verifyImage();
	    if($verify['errorCode'] == 1) exit(json_encode($verify));
		$keyList = array(name,type,number,head_img,sort,online);
		$serviceInfo = $this->getArgsList($this, $keyList);
		if($serviceInfo['type'] == '1'){
			$serviceInfo['number'] = $this->spArgs('qq_number');
		}else if($serviceInfo['type'] == '2'){
			if(!empty($_FILES)) $imageResult = UtilImage::uploadPhotoJust('weichat_number','upload/image/base/service/');
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
	 * 编辑客服
	 */
	function updateService(){
	    //验证图片能否上传
	    if($_FILES) $verify = UtilImage::verifyImage();
	    if($verify['errorCode'] == 1) exit(json_encode($verify));
		$id = $this->spArgs('sid');
		$serviceInfo = $this->getArgsList($this, array('name', 'type', 'sort', 'online', 'head_img'));
		if($serviceInfo['type'] == '1'){
		    $result = $this->lib_service->findService(array('id' => $id));
		    if($result['data']['type'] == 2){
		        unlink($result['data']['number']);//删除原微信二维码图片
		    }
			$serviceInfo['number'] = $this->spArgs('qq_number');
		}else if($serviceInfo['type'] == '2'){
			if($this->spArgs('imgFlag') == 1){//判断是否需要重新上传图片
			    //更新图片
				$prevUrl = $this->spArgs('prevUrl');
				$resultImg = UtilImage::uploadPhotoJust('weichat_number', 'image/mall/service/main/' . date('Ymd'),300,300);
				if($resultImg){
					$serviceInfo['number'] = $resultImg;
					if($prevUrl){
					  unlink($prevUrl);//delete url
					}
				}else{
					echo json_encode(common::errorArray(1, "修改主图失败", 0));
					exit;
				}
			}
		}else if($serviceInfo['type'] == '3'){
		    $result = $this->lib_service->findService(array('id' => $id));
		    if($result['data']['type'] == 2){
		        unlink($result['data']['number']);//删除原微信二维码图片
		    }
			$serviceInfo['number'] = $this->spArgs('phone_number');
		}
		$result = $this->lib_service->updateService( array('id' => $id), $serviceInfo );
		$this->log(__CLASS__, __FUNCTION__, "编辑客服", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 删除客服
	 */
	function deleteService(){
		$result = $this->lib_service->deleteService($this->spArgs('ids'));
		$this->log(__CLASS__, __FUNCTION__, "删除客服", 0, '');
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
	
	/**
	 * 分页查询客服人员
	 */
	function pagingService(){
	    $conditionList = $this->getPagingList($this, array('name'=>'like', 'type'=>'=', 'online'=>'='));
	    $result = $this->lib_service->pagingService($this->getPageInfo($this), $conditionList);
	    echo json_encode($result);
	}
}