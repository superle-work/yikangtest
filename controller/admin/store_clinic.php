<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_clinic')) require 'model/store/lib_clinic.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
/**
 *诊所管理
 * @name store_clinic.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class store_clinic extends admin_controller{
    private $lib_clinic;

    /**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	    $this->lib_clinic = new lib_clinic();
	}
	
	/**
	 * 跳转添加诊所信息页面
	 */
	function addClinic(){
		$this->getMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "跳转添加诊所信息页面", 1, 'add');
		$this->display("../template/admin/{$this->theme}/store/page/clinic/addClinic.html");
	}
	
	/**
     * 绑定用户页面
     **/
    function showBindUser(){
        //一级分类
        $this->getMenu($this);
    	$this->log(__CLASS__, __FUNCTION__, "绑定用户页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/store/page/clinic/bindUser.html");
    }

	/**
	 * 诊所列表信息页面
	 */
	function clinicList(){
		$this->getSetMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "跳转诊所列表信息页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/clinic/clinicList.html");
	}
	
	/**
     * 展示用户页面
     */
    function showUser(){
		$this->getMenu($this);
		
		$id=$this->spArgs('id');
		$this->id=$id;
		
		$uid=$this->spArgs('uid');
		if($uid){
			$conditions ="id in ({$uid})";
			if(!class_exists('lib_user'))include "model/base/lib_user.php";
			$lib_user = new lib_user();
	        $result = $lib_user->findUserList($conditions);
	        $this->userInfo = $result['data'];
		}
		else{
			$this->userInfo = '';
		}
        
        $this->log(__CLASS__, __FUNCTION__, "用户信息列表页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/store/page/clinic/clinicUserList.html");
    }
	
	/*
	 * 添加工作人员
	 * */
	function addUser(){
		$id=$this->spArgs('id');
		$uid=$this->spArgs('uid');
		$info=$this->lib_clinic->getGoods($id);
		if($info['data']['uid']){
			$uids=$info['data']['uid'].','.$uid;  //拼接uid
		}
		else{
			$uids=$uid;  //拼接uid
		}
		//直接覆盖之前的工作人员id
		$result=$this->lib_clinic->updateClinic(array('id'=>$id),array('uid'=>$uids));
		
		//更改user表的user_type的字段值为1，表示属于诊所
		if($result['errorCode']==0){
			if(!class_exists('lib_user'))include "model/base/lib_user.php";
			$lib_user=new lib_user();
			
			//清除之前用户的user_type
			//$lib_user->updateUser("id={$info['data']['uid']}",array("user_type"=>0));
			
			$lib_user->updateUser("id={$uid}",array("user_type"=>1));
			$result['data']=array('id'=>$id,'uid'=>$uids);
		}
		echo json_encode($result);
	}
	
	/*
	 * 删除工作人员
	 * */
	function deleteUser(){
		$id=$this->spArgs('id');
		$uid=$this->spArgs('uid');
		$info=$this->lib_clinic->getGoods($id);
		$user=explode(',',$info['data']['uid']);
		foreach($user as $k=>$u){
			if($u==$uid){
				unset($user[$k]);
			}
		}
		$user_str=implode(',',$user);
		$res=$this->lib_clinic->updateClinic(array('id'=>$id),array('uid'=>$user_str));
		if($res['errorCode']==0){
			if(!class_exists('lib_user'))include "model/base/lib_user.php";
			$lib_user=new lib_user();
			$lib_user->updateUser("id={$uid}",array("user_type"=>0));
			$res['data']=array('id'=>$id,'uid'=>$user_str);
		}
		echo json_encode($res);
	}
	
	/*
	 * 展示核销记录
	 * */
	function cancelOrder(){
		$this->getMenu($this);
		$id=$this->spArgs('id');
		$this->id=$id;
		$this->log(__CLASS__, __FUNCTION__, "核销记录", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/clinic/cancelOrder.html");
	}
	
	/*
	 * 异步展示核销记录列表
	 * */
	function pagingCancelOrder(){
		$page = $this->getPageInfo($this);
		extract($page);
		$Info = $this->getArgsList($this,array(user_name,id,order_num,state,from_add_time,to_add_time));
		$from=$Info['from_add_time'];
		$to=$Info['to_add_time'];
		$sql="select * from store_order where clinic_id={$Info['id']}";
		if($Info['state']){
			$sql.=" and state={$Info['state']}";
		}
		if($Info['order_num']){
			$sql.=" and order_num={$Info['order_num']}";
		}
		if($from){
			$sql.=" and date_format(add_time,'%Y-%m-%d')>='{$from}'";
		}
		if($to){
			$sql.=" and date_format(add_time,'%Y-%m-%d')<='{$to}'";
		}
		if($Info['user_name']){
			$name=$Info['user_name'];
			$sql.=" and clinic_worker_name like '%{$name}%'";
		}
		
		if(!class_exists('m_store_order')) require "model/store/table/m_store_order.php";
		$m_store_order=new m_store_order();
		$result['orderList']=$m_store_order->spPager($pageIndex,$pageSize)->findSql($sql);
		$result['pageInfo']=$m_store_order->spPager()->getPager();
		
		if($result['pageInfo']==NULL){
			$all_pages=count($result['orderList'])%$pageSize==0?intval(count($result['orderList'])/$pageSize):intval(count($result['orderList'])/$pageSize)+1;
			$result['pageInfo']['current_page']=$pageIndex;
			$result['pageInfo']['first_page']=1;
			$result['pageInfo']['prev_page']=($pageIndex-1)>0?($pageIndex-1):1;
			$result['pageInfo']['next_page']=($pageIndex+1)<=$all_pages?($pageIndex+1):$all_pages;
			$result['pageInfo']['last_page']=$all_pages;
			$result['pageInfo']['total_count']=count($result['orderList']);
			$result['pageInfo']['total_page']=$all_pages;
			$result['pageInfo']['page_size']=$pageSize;
			$result['pageInfo']['all_pages']=$all_pages;
		}
		$result['errorCode']=0;
		echo json_encode($result);
	}
	
	/**
     * 诊所详情页面
     */
    function clinicDetail(){
        $this->getMenu($this);
        //商品基本信息
        $result = $this->lib_clinic->getGoods($this->spArgs('id'));
        $this->goodsInfo = $result['data'];
        //辅图信息
        $imgListResult = $this->lib_clinic->getGoodsImageList($this->spArgs('id'));
        $totalImage = count($imgListResult['data']);
        for($i = 0;$i  <  $totalImage ;$i++){
            $j = $i +1;
            $str = "sideImg{$j}";
            $this->$str= $imgListResult['data'][$i];
        }
        $this->log(__CLASS__, __FUNCTION__, "商品详情页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/store/page/clinic/clinicDetail.html");
    }
	
	/**
	 * 编辑诊所页面
	 */
	function editClinic(){
	    $this->getMenu($this);
	    $goodsResult = $this->lib_clinic->getGoods($this->spArgs('id'));
	    $this->goods = $goodsResult['data'];
	    //辅图信息
	    $imgListResult = $this->lib_clinic->getGoodsImageList($this->spArgs('id'));
	    $totalImage = count($imgListResult['data']);
	    for($i = 0;$i  <  $totalImage ;$i++){
	        $j = $i +1;
	        $str = "sideImg{$j}";
	        $this->$str= $imgListResult['data'][$i];
	    }
	    $this->log(__CLASS__, __FUNCTION__, "编辑诊所页面", 1, 'edit');
	    $this->display("../template/admin/{$this->theme}/store/page/clinic/editClinic.html");
	}
	
	
	/**
	 * 添加诊所
	 */
	function insertClinic(){
	    //验证图片能否上传
	    if($_FILES) $verify = UtilImage::verifyImage();
	    if($verify['errorCode'] == 1) exit(json_encode($verify));
	    
	    $goodsInfo = $this->getArgsList($this, array(name,phone,address,detail_desc,longitude,latitude,clinic_ratio,province,city,area,sort_num));
		$goodsInfo['clinicId']='QJ'.time();
		
		//上传图片
		$resultImg = UtilImage::uploadPhoto('imgurl', 'upload/image/store/clinic/',300,300);
		if($resultImg){
			$goodsInfo['img_url'] = $resultImg['url'];
			$goodsInfo['thumb'] = $resultImg['thumb'];
			$userInfo = $_SESSION['admin'];
			
			$resultGoods = $this->lib_clinic->addClinic ($goodsInfo,$userInfo );
			
			//上传辅图
			$imgInfo['cid'] = $resultGoods['data']['cid'];
			$resultImg = UtilImage::uploadPhoto('sideImg1', 'upload/image/store/clinicSide/',300,300);
			if($resultImg){
				$imgInfo['img_url'] = $resultImg['url'];
				$imgInfo['thumb'] = $resultImg['thumb'];
				$this->lib_clinic->addClinicImage($imgInfo);
			}
			$resultImg = UtilImage::uploadPhoto('sideImg2', 'upload/image/store/clinicSide/',300,300);
			if($resultImg){
				$imgInfo['img_url'] = $resultImg['url'];
				$imgInfo['thumb'] = $resultImg['thumb'];
				$this->lib_clinic->addClinicImage($imgInfo);
			}
			$resultImg = UtilImage::uploadPhoto('sideImg3', 'upload/image/store/clinicSide/',300,300);
			if($resultImg){
				$imgInfo['img_url'] = $resultImg['url'];
				$imgInfo['thumb'] = $resultImg['thumb'];
				$this->lib_clinic->addClinicImage($imgInfo);
			}
			
			$this->log(__CLASS__, __FUNCTION__, "添加诊所", 0, 'add');
			echo json_encode($resultGoods);
		}else{
			$this->log(__CLASS__, __FUNCTION__, "添加诊所", 0, 'add');
			echo json_encode(common::errorArray(1, "上传主图失败", 0));
		}
	}
	
	/**
	 * 分页查询诊所
	 */
	function pagingClinic(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('name' => 'like','id' => 'in','from_add_time' => '>=','to_add_time' => '<=');
		$conditions = $this->getPagingList($this, $keyValueList);
		$sort = "sort_num asc,add_time desc";

		$lib_clinic = new lib_clinic();
		$result = $lib_clinic->pagingGoods ( $page, $conditions, $sort);
		echo json_encode($result);
	}
	
	/*
	 * 结算
	 * */
	function payMoney(){
		$id=$this->spArgs("id");
		$cliInfo=$this->lib_clinic->getGoods($id);
		$money=$cliInfo['data']['money'];
		
		//把可提佣金清零
		$sql="update store_clinic set money=0 where id={$id}";
		$res=$this->lib_clinic->runSql($sql);
		if($res==true){
			echo json_encode(common::errorArray(0, '结算成功', true));
		}
		else{
			echo json_encode(common::errorArray(1, '结算失败', true));
		}
	}
	
	/**
	 * 修改商品信息
	 */
	function  updateClinic(){
	    //验证图片能否上传
	    if($_FILES) $verify = UtilImage::verifyImage();
	    if($verify['errorCode'] == 1) exit(json_encode($verify));
		$conditions = array('id' => $this->spArgs('id'));
		
		//先保留原本的诊所信息
		$clinicInfo=$this->lib_clinic->getGoods($conditions['id']);
		
		//更新商品基本信息
		$goodsInfo = $this->getArgsList($this, array(name,phone,address,detail_desc,longitude,latitude,clinic_ratio,province,city,area,sort_num));
		
		if($this->spArgs('imgFlag') == 1){//判断是否需要重新上传图片
		    //更新图片
			$prevUrl = $this->spArgs('prevurl');
			if($prevUrl){
				$resultImg = UtilImage::uploadPhoto('imgurl', 'upload/image/store/clinic/',300,300);
				if($resultImg['errorCode'] == 1) return $resultImg; //判断图片能否上传
				if($resultImg){
					$goodsInfo['img_url'] = $resultImg['url'];
					$goodsInfo['thumb'] = $resultImg['thumb'];
					$prevThumbUrl = substr($prevUrl, 0,strripos($prevUrl,'.')) . "_thumb" .  substr($prevUrl, strripos($prevUrl,'.'));
					@unlink($prevUrl);//delete url
					@unlink($prevThumbUrl);//delete thumb
				}else{
					echo json_encode(common::errorArray(1, "修改主图失败", 0));
					exit;
				}
			}else{
				echo json_encode(common::errorArray(1, "原图url未传，不能删除", 0));
				exit;
			}
		}
		$this->updateGoodsImage($this->lib_clinic,  $this->spArgs('id'));//辅图修改
		$result = $this->lib_clinic->updateClinic($conditions,$goodsInfo);
		
		$this->log(__CLASS__, __FUNCTION__, "修改商品信息", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 三个辅图业务处理
	 * @param object $lib_clinic
	 * @param int $gid
	 */
	private function updateGoodsImage($lib_clinic,$gid){
		if($this->spArgs('imgFlag1')){//0不变,1修改,2新增,3删除
			$this->perImage('imgFlag1', 'prevSideImg1','sideImg1' ,'sid1', $lib_clinic, $gid);
		}
		if($this->spArgs('imgFlag2')){//0不变,1修改,2新增,3删除
			$this->perImage('imgFlag2', 'prevSideImg2', 'sideImg2' ,'sid2', $lib_clinic, $gid);
		}
		if($this->spArgs('imgFlag3')){//0不变,1修改,2新增,3删除
			$this->perImage('imgFlag3', 'prevSideImg3', 'sideImg3' ,'sid3', $lib_clinic, $gid);
		}
	}
	
	/**
	 * 每个辅图业务处理
	 * @param string $imgFlag
	 * @param string $prevprevSideImg 原辅图地址
	 * @param string $sideImg 文件流名称
	 * @param string $sid 辅图主键id
	 * @param object $lib_clinic
	 * @param int $gid 商品id
	 */
	private function perImage($imgFlag,$prevprevSideImg,$sideImg,$sid,$lib_clinic,$gid){
	    //验证图片能否上传
	    if($_FILES) $verify = UtilImage::verifyImage();
	    if($verify['errorCode'] == 1) exit(json_encode($verify));
		if($this->spArgs($imgFlag) == 1){//修改
			//更新图片
			$prevUrl = $this->spArgs($prevprevSideImg);
			$resultImg = UtilImage::uploadPhoto($sideImg, 'upload/image/store/clinicSide/',300,300);
			if($resultImg){
				$prevThumbUrl = substr($prevUrl, 0,strripos($prevUrl,'.')) . "_thumb" .  substr($prevUrl, strripos($prevUrl,'.'));
				@unlink($prevUrl);//delete url
				@unlink($prevThumbUrl);//delete thumb
				$lib_clinic->updateGoodsImage(array('id'=>$this->spArgs($sid)),array('img_url'=>$resultImg['url'],"thumb"=>$resultImg['thumb']));
			}else{
				echo json_encode(common::errorArray(1, "修改辅图失败", 0));
				exit;
			}
		}else if($this->spArgs($imgFlag) == 2){//新增
			$resultImg = UtilImage::uploadPhoto($sideImg, 'upload/image/store/clinicSide/',300,300);
			if($resultImg){
				$imgInfo['cid'] = $gid;
				$imgInfo['img_url'] = $resultImg['url'];
				$imgInfo['thumb'] = $resultImg['thumb'];
				$lib_clinic->addClinicImage($imgInfo);
			}
		}else if($this->spArgs($imgFlag) == 3){//删除
			$prevUrl = $this->spArgs($prevprevSideImg);
			$prevThumbUrl = substr($prevUrl, 0,strripos($prevUrl,'.')) . "_thumb" .  substr($prevUrl, strripos($prevUrl,'.'));
			@unlink($prevUrl);//delete url
			@unlink($prevThumbUrl);//delete thumb
			$lib_clinic->deleteGoodsImage(array('id'=>$this->spArgs($sid)));
		}
	}
	
	
	/**
	 * 根据id获取诊所信息
	 */
	function getGoodsDetail() {
		$result = $this->lib_clinic->getGoods($this->spArgs('id'));
		$this->log(__CLASS__, __FUNCTION__, "根据id获取诊所信息", 0, 'view');
		echo json_encode($result);
	}

	/**
	 * 删除诊所
	 */
	function deleteClinic(){		
		$id = $this->spArgs('id');
		//保留诊所中的工作人员id
		$clinicInfo=$this->lib_clinic->getGoods($id);
		
		$result = $this->lib_clinic->deleteComplete($id);
		if($result['errorCode'] != 0){
			echo json_encode($result);
			return;
		}
		//删除诊所后，重置user表的user_type
		if(!class_exists('lib_user'))include "model/base/lib_user.php";
		$lib_user=new lib_user();
		$lib_user->updateUser("id in ({$clinicInfo['data']['uid']})",array("user_type"=>0));
		
		
		//删除诊所图片
		$imgUrl = $this->spArgs('imgurl');
		@unlink($imgUrl);//delete url
		$thumbUrl = substr($imgUrl, 0,strripos($imgUrl,'.')) . "_thumb" .  substr($imgUrl, strripos($imgUrl,'.'));
		@unlink($thumbUrl);//delete thumb
		//删除诊所辅图
		$imageListResult = $this->lib_clinic->getGoodsImageList($id);
		if($imageListResult['errorCode'] == 0){
			foreach ($imageListResult['data'] as $per){
				@unlink($per['img_url']);
				@unlink($per['thumb']);
			}
		}
		$this->lib_clinic->deleteGoodsImage(array('cid'=>$id));
		$this->log(__CLASS__, __FUNCTION__, "删除商品", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 批量删除诊所
	 */
	function batchDelete(){
		$gids = $this->spArgs('ids');
		$result = $this->lib_clinic->batchDelete($gids);
		$this->log(__CLASS__, __FUNCTION__, "批量删除诊所", 0, 'del');
		echo json_encode($result);
	}
}