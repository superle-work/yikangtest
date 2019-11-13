<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_label')) require 'model/store/lib_label.php';
/**
 *首页图片轮播管理
 * @name store_label.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class store_label extends admin_controller{
    private $lib_label;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	    $this->lib_label = new lib_label();
	}
	/**
     * 标签列表页面
     */
    function labelList(){
        $this->getSetMenu($this);
        $result = spClass ( "lib_label" )->findAllLabel();
        $this->labelList = $result['data'];
        $this->log(__CLASS__, __FUNCTION__, "标签列表页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/store/page/label/labelList.html");
    }
    /**
     * 绑定商品页面
     **/
    function showBindGoods(){
        //一级分类
        $this->getMenu($this);
        $conditions = array('rank' => 1);
        if(!class_exists('lib_category')) include'model/store/lib_category.php';
        $lib_category = new lib_category();
        $firCateResult = $lib_category->getCategorys($conditions, "order_index asc");
        $this->categoryList = $firCateResult['data'];
    	$this->log(__CLASS__, __FUNCTION__, "绑定商品页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/store/page/label/bindGoods.html");
    }
    
	/**
	 * 添加标签页面
	 */
	function addLabel(){
		$this->getMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "添加标签页面", 1, 'add');
		$this->display("../template/admin/{$this->theme}/store/page/label/addLabel.html");
	}

    /**
     * 编辑标签页面
     */
    function editLabel(){
		$this->getMenu($this);
        $conditions = array('id' => $this->spArgs('id'));
        $result = $this->lib_label->findLabel ($conditions);
        $this->labelInfo = $result['data'];
        $this->log(__CLASS__, __FUNCTION__, "管理员列表页面", 1, 'edit');
        $this->display("../template/admin/{$this->theme}/store/page/label/editLabel.html");
    }

    /**
     * 标签商品列表页面
     */
    function labelGoodsList(){
		$this->getMenu($this);
        $conditions = array( 'id' => $this->spArgs('id'));
        $result = $this->lib_label->findLabel ($conditions);
        $this->labelInfo = $result['data'];
        $this->log(__CLASS__, __FUNCTION__, "标签商品列表页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/store/page/label/labelGoodsList.html");
    }
    
	/**
	 * 更新标签
	 */
	function updateLabel(){	   
	    //验证图片能否上传
	    if($_FILES) $verify = UtilImage::verifyImage();
	    if($verify['errorCode'] == 1) exit(json_encode($verify));
		$conditions = array('id' => $this->spArgs('id'));
		$labelInfo = $this->getArgsList($this,array(name,english_name,order_index,gids,link,goods_num));
		if($this->spArgs('imgFlag') == 1){//判断是否需要重新上传图片
			//更新图片
			$prevUrl = $this->spArgs('prevurl');
			if($prevUrl){
				$resultImg = UtilImage::uploadPhoto('imgurl','upload/image/store/label/',100,100);
				if($resultImg){
					$labelInfo['image'] = $resultImg['url'];
					$labelInfo['thumb'] = $resultImg['thumb'];
					$prevThumbUrl = substr($prevUrl, 0,strripos($prevUrl,'.')) . "_thumb" .  substr($prevUrl, strripos($prevUrl,'.'));		
					@unlink($prevUrl);//delete url
					@unlink($prevThumbUrl);//delete thumb
				}else{
					echo json_encode(common::errorArray(1, "修改标签图片失败", 0));
					exit;
				}
			}else{
				echo json_encode(common::errorArray(1, "原图路径未传，不能删除", 0));
				exit;
			}
		}
		if($this->spArgs('imgFlag2') == 1){//判断广告图片是否需要重新上传图片
			//更新图片
			$prevUrl = $this->spArgs('prevurl2');
			$resultImg = UtilImage::uploadPhotoJust('ad_image','upload/image/store/label/');
			if($resultImg){
				$labelInfo['ad_image'] = $resultImg;
				@unlink($prevUrl);//delete url
			}
		}
		$result = $this->lib_label->updateLabel($conditions,$labelInfo);
		$this->log(__CLASS__, __FUNCTION__, "更新标签", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 添加标签
	 */
	function insertLabel(){
	    //验证图片能否上传
	    if($_FILES) $verify = UtilImage::verifyImage();
	    if($verify['errorCode'] == 1) exit(json_encode($verify));
	    $labelInfo = $this->getArgsList($this, array(name,english_name,order_index,gids,link,goods_num));
		//广告图
		$url = UtilImage::uploadPhotoJust('ad_image','upload/image/store/label/');
		if($url){
			$labelInfo['ad_image'] = $url;
		}
		//上传图片
		$resultImg =  UtilImage::uploadPhoto('imgurl','upload/image/store/label/',100,100);
		if($resultImg){
			$labelInfo['image'] = $resultImg['url'];
			$labelInfo['thumb'] = $resultImg['thumb'];
			$result = $this->lib_label->addLabel ($labelInfo );
			
			$this->log(__CLASS__, __FUNCTION__, "添加标签", 0, 'add');
			echo json_encode($result);	
		}else{
			echo json_encode(common::errorArray(1, "上传标签图片失败", 0));
		}
	}
	
	/**
	 * 删除标签
	 */
	function delLabel(){
		$conditions = array('id' => $this->spArgs('id'));
		$result = $this->lib_label->findLabel ($conditions);
		@unlink($result['data']['image']);//delete url
		@unlink($result['data']['thumb']);//delete thumb
		@unlink($result['data']['ad_image']);//delete thumb
		$result = $this->lib_label->deleteLabel ($conditions);
		$this->log(__CLASS__, __FUNCTION__, "删除标签", 0, 'del');
		echo json_encode($result);
	}
	
}