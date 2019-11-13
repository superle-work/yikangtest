<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_slide')) require 'model/base/lib_slide.php';
/**
 *幻灯片管理
 * @name base_slide.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class base_slide extends admin_controller{
	private $lib_slide;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->lib_slide = new lib_slide();
	}
	
	/**
     * 幻灯片列表页面
     */
    function slideList(){
        $this->getSetMenu($this);
        $result = $this->lib_slide->findAllSlide (array('type' => $this->spArgs('type')));
        $this->type = $this->spArgs('type');
        $this->slideList = $result['data'];
        $this->log(__CLASS__, __FUNCTION__, "{$this->getTypeName($this->spArgs('type'))}幻灯片列表页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/base/slide/page/slideList.html");
    }
    
	/**
	 * 添加商城幻灯片页面
	 */
	function addSlide(){
		$this->getMenu($this);
		$this->type = $this->spArgs('type'); 
		$this->log(__CLASS__, __FUNCTION__, "{$this->getTypeName($this->spArgs('type'))}幻灯片列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/slide/page/addSlide.html");
	}

    /**
     * 编辑幻灯片页面
     */
    function editSlide(){
        $this->getMenu($this);
        $result = $this->lib_slide->findSlide (array('id' => $this->spArgs('id')));
        $this->slideInfo = $result['data'];
        $this->log(__CLASS__, __FUNCTION__, "编辑{$this->getTypeName($this->spArgs('type'))}幻灯片页面", 1, 'edit');
        $this->display("../template/admin/{$this->theme}/base/slide/page/editSlide.html");
    }
    
	/**
	 * 修改幻灯片
	 */
	function updateSlide(){
	    //验证图片能否上传
	    if($_FILES) $verify = UtilImage::verifyImage();
	    if($verify['errorCode'] == 1) exit(json_encode($verify));
		$conditions = array('id' => $this->spArgs('id'));
		$slideInfo = $this->getArgsList($this, array(name,url,sort,type,target));
		if($this->spArgs('imgFlag') == 1){//判断是否需要重新上传图片
		    //更新图片
			$prevUrl = $this->spArgs('prevurl');
			if($prevUrl){
			    $resultImg = UtilImage::uploadPhoto('imgurl',"upload/image/{$slideInfo['type']}/slide",86.4,48);
				if($resultImg){
					$slideInfo['img_url'] = $resultImg['url'];
					$slideInfo['thumb'] = $resultImg['thumb'];
					$prevThumbUrl = substr($prevUrl, 0,strripos($prevUrl,'.')) . "_thumb" .  substr($prevUrl, strripos($prevUrl,'.'));
					unlink($prevUrl);//delete url
					unlink($prevThumbUrl);//delete thumb

				}else{
					echo json_encode(common::errorArray(1, "修改图片失败", 0));
				}
			}else{
				echo json_encode(common::errorArray(1, "原图url未传，不能删除", 0));
			}
		}
		$result = $this->lib_slide->updateSlide($conditions,$slideInfo);
		$this->log(__CLASS__, __FUNCTION__, "修改{$this->getTypeName($this->spArgs('type'))}幻灯片", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 添加幻灯片
	 */
	function insertSlide(){
	    //验证图片能否上传
	    if($_FILES) $verify = UtilImage::verifyImage();
	    if($verify['errorCode'] == 1) exit(json_encode($verify));
		$slidenfo = $this->getArgsList($this, array('name','url','sort','type','target')) ;
		//上传图片
		$resultImg = UtilImage::uploadPhoto('imgurl',"upload/image/{$this->spArgs('type')}/slide",86.4,48);
		if($resultImg){
			$slidenfo['img_url'] = $resultImg['url'];
			$slidenfo['thumb'] = $resultImg['thumb'];
			$result = $this->lib_slide->addSlide ($slidenfo );
			$this->log(__CLASS__, __FUNCTION__, "添加{$this->spArgs('type_name')}幻灯片", 0, 'add');
			echo json_encode($result);
		}else{
			echo json_encode(common::errorArray(1, "上传图片失败", 0));
		}
	}
	
	/**
	 * 删除幻灯片
	 */
	function delSlide(){
		$conditions = array('id' => $this->spArgs('id'));
		$result = $this->lib_slide->deleteSlide ($conditions);
		$this->log(__CLASS__, __FUNCTION__, "删除{$this->getTypeName($this->spArgs('type'))}幻灯片", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 获取类型名称
	 * @param string $type
	 * @return string|boolean
	 */
	private function getTypeName($type){
	    $data = array(
	        'site'=>'微官网',
	        'store'=>'百货商城',
	        'dining'=>'微订餐',
	        'trip'=>'微旅游',
	        'points'=>'积分商城',
	        'rob'=>'抢购商城',
	        'group'=>'拼团',
	        'duo'=>'夺宝商城',
	        'donate'=>'微募捐'
	    );
	    foreach ($data as $key=>$value){
	        if($type == $key){
	            return $value;
	        }
	    }
	    return false;
	}
	
}