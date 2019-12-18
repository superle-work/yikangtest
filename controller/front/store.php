<?php
if(!class_exists('front_controller')) require 'include/base/controller/front/front_controller.php';
if(!class_exists('lib_common')) require 'model/base/lib_common.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
/**
 * 前台微商城主页
 * @name mall.php
 * @package cws
 * @category controller
 * @link http://www.changekeji.com
 * @author Lay
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-29
 */
class store extends front_controller{
    private $lib_common;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
	    $this->lib_common = new lib_common();
	}

	public function test(){
		if(!class_exists('lib_order')) include 'model/store/lib_order.php';
		$this->lib_order = new lib_order();
    	$order = $this->lib_order->getOrderList("id>0");
    	if(!class_exists('lib_check_member')) include 'model/store/lib_check_member.php';
		$lib_check_member = new lib_check_member();
    	foreach($order['data'] as $v){
			$memInfo=$lib_check_member->getGoods($v['checkMember_id']);
			if($memInfo['errorCode']==0){
				$m = json_encode($memInfo['data']);
    			$this->lib_order->updateOrder("id={$v['id']}",array("member_info"=>$m));
			}
    	}
    }
		
	/*
	 * 二维数组排序
	 * 参数：二维数组、排序字段、升降序
	 * */
	function arraySort($arr, $keys, $type = 'asc') {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v){
            $keysvalue[$k] = $v[$keys];
        }
        $type == 'asc' ? asort($keysvalue) : arsort($keysvalue);
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
           $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }
	
	/**
	 * 主页跳转
	 * 
	 */
	function index(){
		$this->verifyLogin($this);
		$uid=$_SESSION['user']['id'];
		if(!class_exists("lib_user")) include "model/base/lib_user.php";
		$lib_user=new lib_user();
		$userInfo=$lib_user->findUser(array('id'=>$uid));
		
		if($userInfo['data']['is_login']==0){
			$this->jump(ROOT_URL."/index.php?c=base_user&a=login");
		}
		
		
		//计算购物车中的商品数量
//		if(!$_SESSION['user']['cart_num']){
//		    if(!class_exists('lib_cart')) include 'model/store/lib_cart.php';
//		    $lib_cart = new lib_cart();
//		    $carNum = $lib_cart->getCartGoodsCount($_SESSION['user']['account']);
//		    $_SESSION['user']['cart_num'] = $carNum['data'];
//		}

		$this->lib_common->getMobileCommonDataFront($this,'store_theme');

		//获取到当前所在城市
		$this->signPackage = JsSdk::getSignPackage();

		//获取到当前所在城市
		if(isset($_SESSION['area'])){
			$latitude = $_SESSION['area']['latitude'];
			$longitude = $_SESSION['area']['longitude'];
	        $this->latitude = $latitude;
	        $this->longitude = $longitude;
		}
		
		//获取幻灯片
		if(!class_exists('lib_slide')) include "model/base/lib_slide.php";
		$m_slide = new lib_slide();
		$slideResult = $m_slide->findAllSlide(array('type' => 'store'));
		$this->slideList = $slideResult['data'];
		//获取商城消息
		if(!class_exists('lib_news')) include "model/store/lib_news.php";
		$m_news = new lib_news();
		$newsResult = $m_news->findAllNews();
		$this->newsList = $newsResult['data'];
		//一级分类
		$conditions = array('rank' => 1);
		if(!class_exists('lib_category')) include 'model/store/lib_category.php';
		$lib_category = new lib_category();
		$firCateResult = $lib_category->getCategorys($conditions, "order_index asc");
		$this->categoryList = $firCateResult['data'];
		
		//附近诊所
		if(!class_exists('lib_clinic')) include 'model/store/lib_clinic.php';
		$lib_clinic = new lib_clinic();
		$clinicList=$lib_clinic->findAllClinic();
		
		$ip_arr['latitude']=$_SESSION['area']['latitude'] ;
		$ip_arr['longitude']=$_SESSION['area']['longitude'] ;
		foreach($clinicList['data'] as $k=> &$v){
			$v['distance']=$this->getDistance($v['latitude'],$v['longitude'],$ip_arr['latitude'],$ip_arr['longitude']);
		}
		$clinicList['data']=$this->arraySort($clinicList['data'],'distance','desc');
		
		foreach($clinicList['data'] as $k=>&$v){
			if($v['distance']>1000){
				$v['distance']=round($v['distance']/1000,1).'km';
			}
			else{
				$v['distance']=$v['distance'].'m';
			}
		}
		$this->labelList = $clinicList['data'];
		
		//推荐商品筛选
		//$cids = $this->spArgs('cids');
		if(!class_exists('lib_goods')) include 'model/store/lib_goods.php';
		$lib_goods = new lib_goods();
		//推荐商品显示个数
		$store_config = new UtilConfig('store_config');
		$recommend = $store_config->getConfigValue('recommend_num');
		$limit = $recommend['data'];
		$recommend = 1;
		$recommendGoodsResult = $lib_goods->getGoodsRecommendCids($recommend,$limit);
		
		//底部获取购物车信息
		if(!class_exists('lib_cart')) include 'model/store/lib_cart.php';
		$lib_cart = new lib_cart();
		$cartInfo = $lib_cart->getBottomCartInfo($_SESSION['user']['account']);

		//购物车商品种类数量
		if(!class_exists('lib_cart')) include 'model/store/lib_cart.php';
		$lib_cart = new lib_cart();
		$account=$_SESSION['user']['account'];
		$cartCountResult = $lib_cart->findSql("select count(c.id) as num from store_cart_goods c inner join store_goods g on c.gid=g.id where c.account='{$account}' and g.updown=1");
		$_SESSION['cart']['cartNum'] = $cartCountResult[0]['num'];
		
 		//客服工作人员信息
		if(!class_exists('lib_service')) include 'model/base/lib_service.php';
		$lib_service = new lib_service();
		$serviceResult = $lib_service->findAllService( array('online' => 1,), 'sort asc');
		//$this->serviceList =  $serviceResult['data'];
		$this->bottomCartInfo = $cartInfo['data'];//底部悬浮栏购物车信息
		$this->recommendGoodsList = $recommendGoodsResult['data'];
		$this->bottomNav = "index";//底部导航选中标识
		$this->log(__CLASS__, __FUNCTION__, "主页跳转", 1, "view");
		$this->display("../template/front/store/{$this->theme}/page/index.html");
	}

	/**
	 * 附近诊所页
	 * 
	 */
	function moreClinic(){
		$this->verifyLogin($this);
		$this->lib_common->getMobileCommonDataFront($this,'store_theme');
		
		$this->bottomNav = "getMoreClinic";//底部导航选中标识
		$this->log(__CLASS__, __FUNCTION__, "附近诊所页", 1, "view");
		$this->display("../template/front/store/{$this->theme}/page/clinic/clinicInfo.html");
	}
	
	/**
	 * 获取附近诊所
	 * 
	 */
	function getMoreClinic(){
		$this->verifyLogin($this);
		$this->lib_common->getMobileCommonDataFront($this,'store_theme');
			
		//全部诊所
		if(!class_exists('lib_clinic')) include 'model/store/lib_clinic.php';
		$lib_clinic = new lib_clinic();
		$clinicList=$lib_clinic->findAllClinic();
		
		$ip_arr['latitude']=$_SESSION['area']['latitude'] ;
		$ip_arr['longitude']=$_SESSION['area']['longitude'] ;
		
		foreach($clinicList['data'] as $k=> &$v){
			$v['distance']=$this->getDistance($v['latitude'],$v['longitude'],$ip_arr['latitude'],$ip_arr['longitude']);
		}
		$clinicList['data']=$this->arraySort($clinicList['data'],'distance');
		
		$result['data']=array();
		foreach($clinicList['data'] as $k=>&$v){
			if($v['distance']>1000){
				$v['distance']=round($v['distance']/1000,1).'km';
			}
			else{
				$v['distance']=$v['distance'].'m';
			}
			$result['data'][]=$v;
		}
		$clinicList['data']=$result['data'];
		echo json_encode($clinicList);
	}

	/**
      * 火星坐标系 (GCJ-02) 与百度坐标系 (BD-09) 的转换算法   将 BD-09 坐标转换成GCJ-02 坐标 
      * 
      * @param bd_lon
      * @param bd_lat
      * @return
      */
    function bd09_To_Gcj02($bd_lon, $bd_lat) {
    	$p = 3.14159265358979324 * 3000.0 / 180.0; 
        $x = $bd_lon - 0.0065;
        $y = $bd_lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $p);         
        $theta = atan2($y, $x) - 0.000003 * cos($x * $p);
        $longitude = $z * cos($theta);
        $latitude = $z * sin($theta);
      	return array('latitude' => $latitude,'longitude' => $longitude);
    }
	
	/**
	 * 诊所详情页
	 * 
	 */
	function clinicDetail(){
		$this->verifyLogin($this);
		$this->lib_common->getMobileCommonDataFront($this,'store_theme');
		
		//获取商品信息
		$cid = $this->spArgs('id');
		if(!class_exists('lib_clinic')) include 'model/store/lib_clinic.php';
		$lib_clinic = new lib_clinic();
		$clinicList=$lib_clinic->getGoods($cid);
		//将百度地图坐标转换为腾讯地图坐标
		$res=$this->bd09_To_Gcj02($clinicList['data']['longitude'],$clinicList['data']['latitude']);
		$clinicList['data']['longitude']=$res['longitude'];
		$clinicList['data']['latitude']=$res['latitude'];

		$this->clinicList=$clinicList['data'];
		//辅图列表
		$sideListResult = $lib_clinic->getGoodsImageList($cid);
		if($sideListResult['errorCode'] == 0){
			$this->sideList = $sideListResult['data'];
		}
		$this->signPackage = JsSdk::getSignPackage();
		$this->log(__CLASS__, __FUNCTION__, "商品详情页", 1, "view");
		$this->display("../template/front/store/{$this->theme}/page/clinic/printerDetail.html");
	}
	
	/**
	 * 诊所中心
	 * 
	 */
	function clinicCenter(){
		$this->verifyLogin($this);
		$this->lib_common->getMobileCommonDataFront($this,'store_theme');
		
		$id =$_SESSION['user']['id'];
		
		if(!class_exists("lib_clinic"))include "model/store/lib_clinic.php";
		$lib_clinic=new lib_clinic();
		$sql="select * from store_clinic where find_in_set({$id},uid)";
		$result=$lib_clinic->findSql($sql);
		if($result){
			$this->clinicInfo = $result[0];
		}
		
		//分享功能
      	$this->signPackage = JsSdk::getSignPackage();
		$this->log(__CLASS__, __FUNCTION__, "诊所中心", 1, "view");
		$this->display("../template/front/store/{$this->theme}/page/clinic/clinicCenter.html");
	} 
	
	/**
	 * 医院中心
	 * 
	 */
	function hospitalCenter(){
		$this->verifyLogin($this);
		$this->lib_common->getMobileCommonDataFront($this,'store_theme');
		
		$id =$_SESSION['user']['id'];
		
		if(!class_exists("lib_hospital"))include "model/store/lib_hospital.php";
		$lib_hospital=new lib_hospital();
		$sql="select * from store_hospital where find_in_set({$id},uid)";
		$result=$lib_hospital->findSql($sql);
		if($result){
			$this->hospitalInfo = $result[0];
		}
		
		//分享功能
      	$this->signPackage = JsSdk::getSignPackage();
		$this->log(__CLASS__, __FUNCTION__, "诊所中心", 1, "view");
		$this->display("../template/front/store/{$this->theme}/page/hospital/hospitalCenter.html");
	}
	
	/*
	 * 诊所中心、医院中心核销订单
	 * 展示对应诊所或医院核销的订单
	 * */
	function cancelOtherOrder(){
		$this->verifyLogin($this);
		$this->lib_common->getMobileCommonDataFront($this,'store_theme');
		
		//诊所id
		if($this->spArgs('cid')){
			$this->id=$this->spArgs('cid');
			$this->type=2;
		}
		
		//医院id
		if($this->spArgs('hid')){
			$this->id=$this->spArgs('hid');
			$this->type=3;
		}
		$this->log(__CLASS__, __FUNCTION__, "诊所、医院核销订单", 1, "view");
		$this->display("../template/front/store/{$this->theme}/page/clinic/cancelOtherOrder.html");
	}
	
	/*
	 * 异步展示诊所中心、医院中心核销订单
	 * */
	function pagingCancelOtherOrder(){
		$page = $this->getPageInfo($this);
		$id=$this->spArgs("id");
		$type=$this->spArgs("type");
		
		//判断是诊所id
		if($type==2){
			$condition=array("clinic_id"=>$id,"is_show"=>1);
		}
		//判断是医院id
		else if($type==3){ 
			$condition=array("hospital_id"=>$id,"is_show"=>1);
		}
		
		$sort = array(
	        array('field' => 'state', 'orderby' => 'asc'),
	        array('field' => 'add_time', 'orderby' => 'desc')
	    );
		
		if(!class_exists('lib_order')) include 'model/store/lib_order.php';
        $lib_order = new lib_order();
        $result = $lib_order->pagingOrder( $page, $condition, $sort);
		
        $this->log(__CLASS__, __FUNCTION__, "诊所中心、医院中心显示核销订单", 0, "view");
        echo json_encode($result);
	}
	
	/*
	 * 物流中心
	 * */ 
	function logistics(){
		$this->verifyLogin($this);
		$this->lib_common->getMobileCommonDataFront($this,'store_theme');
		
		//分享功能
      	$this->signPackage = JsSdk::getSignPackage();
		$this->log(__CLASS__, __FUNCTION__, "物流中心", 1, "view");
		$this->display("../template/front/store/{$this->theme}/page/logistics/logisticsCenter.html");
	}
	
	/*
	 * 物流中心核销订单页面
	 * */
	function cancelOrder(){
		$this->verifyLogin($this);
		$this->lib_common->getMobileCommonDataFront($this,'store_theme');
		
		$this->log(__CLASS__, __FUNCTION__, "核销订单", 1, "view");
		$this->display("../template/front/store/{$this->theme}/page/logistics/cancelOrder.html");
	}
	
	
	/***_-----------=------------------商品-----------------------------------***/
	/**
	 * 商品分类页面
	 **/
	function goodsCate(){
	    $this->verifyLogin($this);
        $this->lib_common->getMobileCommonDataFront($this,'store_theme'); //17004
		if($this->spArgs('cid') !=''){
			$this->cid = $this->spArgs('cid');		
		}
		if($this->spArgs('keywords') !=''){
			$this->keywords = $this->spArgs('keywords');		
		}

        //获取所有分类信息
        if(!class_exists('lib_category')) include 'model/store/lib_category.php';
        $lib_category = new lib_category();
		$cateResult = $lib_category->findAllCategory(array('rank'=>1,'is_use'=>1),'order_index asc,add_time desc');
    	$this->cateList = $cateResult['data'];
		$this->imgFir = $cateResult['data']['0']['img_url'];
		
		//传递分类信息		
		$this->store_config = new UtilConfig('store_config');
		$cateConfig = $this->store_config->getConfigValue('cate_level');
		$this->cateLevel = $cateConfig['data'];

		//购物车商品种类数量
		if(!class_exists('lib_cart')) include 'model/store/lib_cart.php';
		$lib_cart = new lib_cart();
		$account=$_SESSION['user']['account'];
		$cartCountResult = $lib_cart->findSql("select count(c.id) as num from store_cart_goods c inner join store_goods g on c.gid=g.id where c.account='{$account}' and g.updown=1");
		$_SESSION['cart']['cartNum'] = $cartCountResult[0]['num'];
			
        $this->bottomNav = "goodsCate";//底部导航选中标识
        $this->log(__CLASS__, __FUNCTION__, "商品分类页面", 1, "view");
        $this->display("../template/front/store/{$this->theme}/page/goods/goodsCate.html");
	 }

	/**
	 * 点击一级分类 异步获取商品分类信息
	 */
	function getCateInfo(){
		$cid = $this->spArgs('cid');
		$page = $this->getPageInfo($this);
		//传递分类模板信息(使用了category(1/2/3))		
		$this->store_config = new UtilConfig('store_config');
		$cateConfig = $this->store_config->getConfigValue('cate_level');
		
		if($cid=='' || $cid==null){
			$gids='';
		}
		else{
			if(!class_exists('lib_category')) include 'model/store/lib_category.php';
			//查找该分类的商品id
	        $lib_category = new lib_category();
			$cate = $lib_category->findCategory(array('id'=>$cid));
			if(!$cate['data']['gids']){    //分类下无商品
				$result = common::errorArray(1, '该分类无体检项目', '');
				echo json_encode($result);
				die;
			}
			$gids=$cate['data']['gids'];
		}
		
		if(!class_exists('lib_goods')) include 'model/store/lib_goods.php';
		$m_goods = new lib_goods();
		$sort= $this->spArgs('sort');
		if($sort){
			$sort.=",sort_num asc";
		}
		else{
			$sort="sort_num asc";
		}
		$keywords= $this->spArgs('keywords');
		$page['pageIndex'] = $this->spArgs('pageIndex');
		$page['pageSize'] = $this->spArgs('pageSize');
		
		$result = $m_goods->searchGoods($keywords, $gids, $page, $sort);
		
		if($result['errorCode'] ==0){
			$result['data']['level'] = 1;
		}
		echo json_encode($result);
	}

	/**
	 * 商品详情页
	 */
	function goodsDetail(){
		$this->verifyLogin($this);
		$this->lib_common->getMobileCommonDataFront($this,'store_theme');
		
		//获取商城的相关配置
	    $lib_config = new UtilConfig('store_config');
	    $result = $lib_config->findConfigKeyValue();
	    $config = $result['data'];
	    $this->configInfo = $config;
		
		//获取商品信息
		$gid = $this->spArgs('id');
		if(!class_exists('lib_goods')) include 'model/store/lib_goods.php';
		$m_goods = new lib_goods();
		$goodsInfoResult = $m_goods->getGoods($gid);
		$this->goodsInfo = $goodsInfoResult['data'];
			
		//查找推荐商品
		//$cids = $this->spArgs('cids');
		if(!class_exists('lib_goods')) include 'model/store/lib_goods.php';
		$lib_goods = new lib_goods();
		$recommend = 1;
		$recommendGoodsResult = $lib_goods->getGoodsRecommendCids($recommend,$config['recommend_num']);
		$this->recommendGoodsList = $recommendGoodsResult['data'];
		
		//判断是否收藏
		if(!class_exists('lib_favorite')) include 'model/store/lib_favorite.php';
		$lib_favorite = new lib_favorite();
		$favoriteResult = $lib_favorite->findFavorite(array('user_id'=>$_SESSION['user']['id'],"thing_id"=>$gid,"type"=>0));
		if($favoriteResult['errorCode'] == 0){
			$this->isFavorite = true;
		}else{
			$this->isFavorite = false;
		}
		
		//获取辅图列表
		$sideListResult = $m_goods->getGoodsImageList($gid);
		if($sideListResult['errorCode'] == 0){
			$this->sideList = $sideListResult['data'];
		}
		
		//购物车商品种类数量
		if(!class_exists('lib_cart')) include 'model/store/lib_cart.php';
		$lib_cart = new lib_cart();
		$account=$_SESSION['user']['account'];
		$cartCountResult = $lib_cart->findSql("select count(c.id) as num from store_cart_goods c inner join store_goods g on c.gid=g.id where c.account='{$account}' and g.updown=1");
		$this->cartCount = $cartCountResult[0]['num'];
		
		//分享功能
      	$this->signPackage = JsSdk::getSignPackage();
		$this->log(__CLASS__, __FUNCTION__, "商品详情页", 1, "view");
		$this->display("../template/front/store/{$this->theme}/page/goods/goodsDetail.html");
	}
	
	/**
	 * 单个标签下商品列表(未用)
	 */
	function labelGoodsList(){
		$this->verifyLogin($this);
		$this->lib_common->getMobileCommonDataFront($this,'store_theme');
		$this->labelId = $this->spArgs('id');
		$this->log(__CLASS__, __FUNCTION__, "单个标签商品列表", 1, "view");
		$this->display("../template/front/store/{$this->theme}/page/goods/labelGoodsList.html");
	}
	
	/**
	 * 标签商品分页请求(未用)
	 */
	function pagingLabelGoods(){
		//获取标签下的gids
		$condition = array('id' => $this->spArgs('id'));
		if(!class_exists('lib_label')) include 'model/store/lib_label.php';
		$lib_label = new lib_label();
		$labelResult = $lib_label->findLabel($condition);
		if($labelResult['errorCode'] != 0){
			echo json_encode($labelResult);
			exit;
		}
		$gids = $labelResult['data']['gids'];
		if($gids == ""){
			$gids = 0;
		}
		//获取该标签商品分页信息
		$page = $this->getPageInfo($this);
		$conditionList = array();
		$conditionList[] = array('field' => 'id','operator' => 'in','value' => $gids);
		$conditionList[] = array('field' => 'updown','operator' => '=','value' => 1);
		$sort = $this->spArgs('sort');
		if(!class_exists('lib_goods')) include 'model/store/lib_goods.php';
		$lib_goods = new lib_goods();
		$labelResult = $lib_goods->pagingGoods($page, $conditionList, $sort);
		echo json_encode($labelResult);
	}
	
	/*-------------------------我的收藏---------------------------*/
	
	/*
	 * 我的收藏
	 * 展示收藏的商品
	 * */
	function favorite(){
		$this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');
		$this->log(__CLASS__, __FUNCTION__, "我的收藏", 1, "view");
		$this->display("../template/front/store/{$this->theme}/page/user/favorite.html");
	}
	
	/**
	 * 收藏商品异步请求
	 */
	function pagingSearchFavorite(){
	    $user_id = $_SESSION['user']['id'];
	    $page['pageIndex'] = $this->spArgs('pageIndex');
	    $page['pageSize'] = $this->spArgs('pageSize');
	    if(!class_exists('lib_favorite')) include 'model/store/lib_favorite.php';
	    $lib_favorite = new lib_favorite();
	    $favoriteList = $lib_favorite->searchFavorite($user_id, $page);
	    echo json_encode($favoriteList);
	}
	
	/**
	 * 添加收藏夹
	 */
	function addFavorite(){
		if(!class_exists('lib_favourite')) include 'model/store/lib_favorite.php';
		$lib_favorite = new lib_favorite();
		$favoriteInfo['user_id'] = $_SESSION['user']['id'];
		$favoriteInfo['nick_name'] = $_SESSION['user']['nick_name'];
		$favoriteInfo['thing_id'] = $this->spArgs('gid');
		$result = $lib_favorite->addFavorite($favoriteInfo);
		$this->log(__CLASS__, __FUNCTION__, "添加收藏夹", 0, "add");
		echo json_encode($result);
	}
	
	/**
	 * 删除当前收藏的商品
	 */
	function deleteFavorite(){
		if(!class_exists('lib_favourite')) include 'model/store/lib_favorite.php';
		$lib_favorite = new lib_favorite();
		$user_id = $_SESSION['user']['id'];
		$gid = $this->spArgs('gid');
		$result = $lib_favorite->deleteSingleFavorite(array('thing_id'=>$gid,'user_id'=>$user_id,'type'=>0));
		$this->log(__CLASS__, __FUNCTION__, "删除收藏商品", 0, "delete");
		echo json_encode($result);
	}
	
		/*----------------------------收藏夹结束---------------------*/
	
	
	/* ***************************************************************下面是购物车begin*********************
	 */
	 
	/**
	 * 获取底部购物车商品信息
	 */
	function getBottomCartInfo(){
		if(!class_exists('lib_cart')) include 'model/store/lib_cart.php';
		$lib_cart = new lib_cart();
		$result = $lib_cart->getBottomCartInfo($_SESSION['user']['account']);
		$this->log(__CLASS__, __FUNCTION__, "获取底部购物车商品信息", 0, "view");
		echo json_encode($result);
	}
	 
	/**
	 * 购物车页面
	 */
	function cartList(){
	    $this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');
	
	    //获取购物车商品列表
	    if(!class_exists('lib_cart')) include 'model/store/lib_cart.php';
	    $lib_cart = new lib_cart();
		
		//如果是从添加到购物车跳转来，则判断购物车商品id，使其默认选中
		if($this->spArgs('id')){
			$this->id=$this->spArgs('id');
		}
		//购物车的分类(本地医院、第三方医院)
		$type=$this->spArgs("type");
		
		$account=$_SESSION['user']['account'];
	    $cartListResult = $lib_cart->getCartList(array('account'=>$account,'type'=>$type));
		
		//去除掉购物车中已下架的商品
		foreach ($cartListResult['data']['dataList'] as $k=>$per){
			if(!class_exists('lib_goods')) include 'model/store/lib_goods.php';
			$lib_goods=new lib_goods();
			$goodsInfo=$lib_goods->getGoods($per['gid']);
			
			if($goodsInfo['data']['updown']==0){
				unset($cartListResult['data']['dataList'][$k]);
			}
		}
		
	    $this->cartList = $cartListResult['data']['dataList'];
//		$this->total_counts = $cartListResult['data']['total_count'];
		
		//查询另一分类的商品种类数量
		$numResult=$lib_cart->findSql("select count(c.id) as num from store_cart_goods c inner join store_goods g on c.gid=g.id WHERE g.updown=1 and c.account = '{$account}' and c.type!=$type");
		$this->num=$numResult[0]['num'];
		$this->type=$type;
	    $this->bottomNav = "cartList";//底部导航选中标识
	    $this->log(__CLASS__, __FUNCTION__, "购物车页面", 1, "view");
	    $this->display("../template/front/store/{$this->theme}/page/cart/cartList.html");
	}
	
	
	
	/**
	 * 添加商品到购物车
	 */
	function addCartGoods(){
	    $this->verifyLogin($this);
	    if(!class_exists('lib_cart')) include 'model/store/lib_cart.php';
	    $lib_cart = new lib_cart();
	    $account =  $this->spArgs('account');
	    $gid = $this->spArgs('gid');
	    $count = 1;
		
		$result = $lib_cart->addCartGoods($account,$gid,$count);

	    $this->log(__CLASS__, __FUNCTION__, "添加商品到购物车", 0, "add");
	    echo json_encode($result);
	}
	
	/**
	 * 删除购物车商品
	 */
	function deleteCartGoods(){
	    $this->verifyLogin($this);
	    $cgids = $this->spArgs('cgids');
	    if(!class_exists('lib_cart')) include 'model/store/lib_cart.php';
	    $lib_cart = new lib_cart();
	    $result = $lib_cart->delCartGoodsByIds($cgids);
	    if($result['errorCode'] == 0){
	        $num = count(explode(',', $cgids));
	        $_SESSION['user']['cart_num'] -= $num;
	    }
	
	    $this->log(__CLASS__, __FUNCTION__, "删除购物车商品", 0, "del");
	
	    echo json_encode($result);
	}
	
	/**
	 * 修改购物车商品数量
	 */
	function updateGoodsCount(){
	    $this->verifyLogin($this);
	    $cgid = $this->spArgs('id');
	    $goods_count = $this->spArgs('goods_count');
	    if(!class_exists('lib_cart')) include 'model/store/lib_cart.php';
	    $lib_cart = new lib_cart();
	    $result = $lib_cart->updateGoodCount($cgid,$goods_count);
	    $this->log(__CLASS__, __FUNCTION__, "修改购物车商品数量", 0, "edit");
	    echo json_encode($result);
	}
	
	/* *******************************上面是购物车end**********************/
	
	/**
	 * 订单确认页面
	 */
	function confirmOrder(){
	    $this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');
		
		//判断是否从购物车提交过来
		if($this->spArgs('type')){
			$this->type = false;
		}else{
			$this->type = true;
		}
			
	    //商品信息
	    if(!class_exists('lib_goods')) include 'model/store/lib_goods.php';
	    $lib_goods = new lib_goods();
	    $goodsResult = $lib_goods->findGoodsIn($this->spArgs('gids'));
	    $goodsCountList = explode(',', $this->spArgs('counts'));
		
		/*
	    $gpidList = explode(',', $this->spArgs('gpids'));
	    if(!class_exists('lib_goods_property')) include 'model/store/lib_goods_property.php';
	    $lib_goods_property = new lib_goods_property();
		*/
	    $sum = 0;
		$counts = 0;
	    for($i = 0;$i < count($goodsCountList);$i++){
	        $goodsResult['data'][$i]['goods_count'] = $goodsCountList[$i];
	        $sum += $goodsResult['data'][$i]['price'] * $goodsCountList[$i];
	        /*
	        if($gpidList[$i]){//多规格
	            $propertyResult = $lib_goods_property->findGoodsProperty(array('id'=>$gpidList[$i]));
	            if($propertyResult['errorCode'] == 0){
	                $propertyResult['data']['property_json'] = json_decode($propertyResult['data']['property_json']);
	                $goodsResult['data'][$i]['property'] = $propertyResult['data'];
	                $goodsResult['data'][$i]['price'] = $propertyResult['data']['price'];
					$counts += $goodsCountList[$i];
	                $sum += $propertyResult['data']['price'] * $goodsCountList[$i];
	                //库存分配
	                $inventory = $propertyResult['data']['inventory'];
	            }
	        }else{//统一规格
	            $goodsResult['data'][$i]['property'] = '';
	            $sum += $goodsResult['data'][$i]['price'] * $goodsCountList[$i];
				$counts += $goodsCountList[$i];
				//库存分配
				$inventory = $goodsResult['data'][$i]['inventory'];
	        }
			*/
	    }
//	    $this->inventory = $inventory;
	    $this->goodsList = $goodsResult['data'];
	    $this->totalPrice = $firNum = $sum;
		/*
	    //收货地址
	    if(!class_exists('lib_user_address')) include 'model/base/lib_user_address.php';
	    $lib_user_address = new lib_user_address();
	    //兼容16004模板
	    $getAddressFunction = 'getAddressInfo';
	    $condition = array('user_id'=>$_SESSION['user']['id'],'is_default'=>1);
	    if($this->theme != 'ma17004') {
	    	$getAddressFunction = 'getAddressList';    unset($condition['is_default']);
	    }
	    $addressResult = $lib_user_address->$getAddressFunction($condition);
	    $this->addressList = $addressResult['data'];
		*/
		
		/*下面有
	    //获取百货商城的相关配置
	    $lib_config = new UtilConfig('store_config');
	    $result = $lib_config->findConfigKeyValue();
	    $config = $result['data'];
	    $this->configInfo = $config;
		*/
		
		/*--------------获取默认体检用户信息--------------*/
		if(!class_exists('lib_check_member')) include 'model/store/lib_check_member.php';
		$lib_check_member=new lib_check_member();
		$condition['uid']=$_SESSION['user']['id'];
		$condition['state']=1;
		$memberInfo=$lib_check_member->getMemberByUser($condition);
		$this->memberInfo=$memberInfo['data'];
		
	    //隐藏表单数据，提交订单时使用
	    $this->gids = $this->spArgs('gids');
	    $this->counts = $this->spArgs('counts');
	    $this->cgids = $this->spArgs('cgids');
	    //$this->gpids = $this->spArgs('gpids');
//		$this->total_money = ($this->spArgs('counts'))*$goodsResult['data'][0]['price'];

	    //查找会员卡
	    if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
	    $lib_config = new UtilConfig('store_config');
	    $result = $lib_config->findConfigKeyValue();
	    $config = $result['data'];
	    $this->configInfo = $config;
		
		/*
	    //配置表是否开启会员卡属性
	    if($config['mall_card'] != 0){
	        if(!class_exists('lib_card')) include 'model/market/card/lib_card.php';
	        $lib_card = new lib_card();
	        $cardInfo = $lib_card->findUserCard(array('user_id'=>$_SESSION['user']['id']));
	        if($cardInfo['errorCode'] == 0){
	            $cardLevel = $lib_card->findCardLevel(array('id'=>$cardInfo['data']['level_id']));
	            $cardLevel['data']['user_card_id'] = $cardInfo['data']['id'];
	            $this->card = $cardLevel['data'];
	        }
	    }
		
		
		//获取打折后的价格
		if($this->card !=null && $this->card !=''){
			$firNum = round($firNum*($cardLevel['data']['discount']/10),2);
		}
		//微商城优惠券是否启用
	    if($config['mall_coupon'] == 1){
	        if(!class_exists('lib_coupon')) include 'model/market/coupon/lib_coupon.php';
	        $lib_coupon = new lib_coupon();
	        $couponListResult = $lib_coupon->findAllMallCoupon($_SESSION['user']['id'],$firNum);
	        $this->couponList = $couponListResult['data'];
	    }

	    //运费
	    $fee = 0;
		if($config['order_fee'] == 1){
			if( $sum <= $config['order_below_fee']){
	            $fee = $config['order_fee_acount'];
	            $this->order_fee = true;
				$this->fee = $config['order_fee_acount'];       //配置的运费
	        }
		}
        
        $this->standardFee = $config['order_fee_acount'];   //标准fee
        $this->below_fee = $config['order_below_fee'];  //不大于此值，均需要运费
        
	    //积分抵现
	    if($config['mall_points'] == 1 && $config['is_points_value'] == 1){//积分抵现是否已经开启
            //$this->is_points = true;
	        //查找用户的总积分
	        if(!class_exists('lib_user')) include 'model/base/lib_user.php';
	        $lib_user = new lib_user();
	        $userResult = $lib_user->findUser(array('id'=>$_SESSION['user']['id']));
	        $points = $userResult['data']['points'];
	        $this->points = $points;
	        if($points>0){
	        	$this->is_points = true;
	        }
	        $base_config = new UtilConfig('base_config');
	        //积分总的基础配置
	        $configInfoResult = $base_config->findConfigKeyValue();
	        $baseconfig = $configInfoResult['data'];
	        $this->baseconfigInfo =  $baseconfig;
	        //本次最大可用积分
	        if($cardLevel['data'] !='' && $cardLevel['data'] !=null){
	        	$total_price = $sum * $cardLevel['data']['discount']/10;
	        }else{
	        	$total_price = $sum;
	        }
			//打完会员卡后+运费的总价格
			if($baseconfig['is_points_fee']){ //是否开启积分抵消运费
				 $total_price += $fee;
			}
			//全部抵现需要的积分
			$need_points = $total_price / $baseconfig['points_value'];
			//允许使用积分抵现的 最大积分
			$allow_points = $need_points*($baseconfig['use_points_rate']/10);
			if( $baseconfig['use_points_rate'] >= 10 && $baseconfig['is_points_fee'] ){
				if($points >= $allow_points){
					$this->max_use_points = $allow_points;
					if($baseconfig['use_points_rate'] == 10){
						$this->points_state = 1; 
					}else{
						$this->points_state = 0;
					}				  
				}else{
					$this->max_use_points = $points;
					$this->points_state = 0;
				}
			}else{
				$this->max_use_points = $allow_points;
				$this->points_state = 0;
			}
			//积分全抵状态补充：如果达到免运费条件， 且  $points > $allow_points ; 且积分充足，全额支付
			if( $sum > $config['order_below_fee'] && $points >= $allow_points && $baseconfig['use_points_rate'] >=10){
			    $this->points_state = 1;
			}
				    
	        $this->max_use_points = $points > $allow_points ? $allow_points:$points; //可用积分 ： firTotalPoints			       
	        $this->validMoney = $this->max_use_points * $baseconfig['points_value']; //积分抵用的现金总额 ： firPoints
		}
	   	
		//余额抵现
		if($config["is_balance"] == 1){//余额抵现是否开启
//			$this->is_balance = true;
			//查找用户的总余额
			if(!class_exists('lib_user')) include 'model/base/lib_user.php';
		    $lib_user = new lib_user();
		    $userResult = $lib_user->findUser(array('id'=>$_SESSION['user']['id']));
		    $balance = $userResult['data']['balance'];
		    $this->balance = $balance;
			if($balance>0){
				$this->is_balance = true;
			}
			$base_config = new UtilConfig('base_config');
		    //余额总的基础配置
			$configInfoResult = $base_config->findConfigKeyValue();
		    $baseconfig = $configInfoResult['data'];
		    $this->baseconfigInfo =  $baseconfig;			
		}
		
		// 获取   可用余额总额 并判断可用余额是否大于订单金额
		// 使用余额的量的判断：
		if($config["is_balance"] == 1){
			if($config['mall_card'] != 0){
				$use_balance = $sum * $cardLevel['data']['discount']/10+$fee;  
			}else{
				$use_balance = $sum +$fee;
			}
			//全用余额需要的金额：$this->paid_money  <=>  firTotalPrice  需要付款的实际金额 ( >= $this->use_balance )
			$this->paid_money = $use_balance;      
			if($this->balance){
				if($use_balance <= $balance){
					$this->use_balance = $use_balance; //$this->use_balance  <=>  firBalance 实际可用的余额
					$this->allBalance = 1;
				}else{
					$this->use_balance = $balance;
					$this->allBalance = 0;				
				}
			}
		}else{
			if($config['mall_card'] != 0){
				$this->paid_money = $sum * $cardLevel['data']['discount']/10+$fee;
			}else{
				$this->paid_money = $sum +$fee;
			}
		}
		*/
		
	    $this->log(__CLASS__, __FUNCTION__, "订单确认页面", 1, "view");
	    $this->display("../template/front/store/{$this->theme}/page/order/confirmOrder.html");
	}
		
	/**
	 * 提交订单,并支付
	 */
	function payOrder(){
		//获取用户信息
		if(!class_exists('lib_user')) include "model/base/lib_user.php";
		$lib_user = new lib_user();
		$userInfo = $lib_user->findUser(array('id' => $this->spArgs('uid'))); 
		
		//提交订单信息		
	    if(!class_exists('lib_order')) include 'model/store/lib_order.php';
	    $lib_order = new lib_order();
	    $gids = $this->spArgs('gids');
	    $counts = $this->spArgs('counts');
	    $cgids = $this->spArgs('cgids');
	    $total_price = $this->spArgs('total_price');
	    $checkMember_id = $this->spArgs('checkMember_id');
	    $clinicID = $this->spArgs('clinicID');
	    $pay_method = 1;
	    $result = $lib_order->addOrder($userInfo['data'], $gids, $counts, $cgids,$total_price,$checkMember_id,$clinicID,$pay_method);
		
		//获取订单信息	
        $orderResult = $lib_order->getOrderInfo(array('id'=>$result['data']['addOrderId']));
        $orderInfo = $orderResult['data'];
		
        //微信支付标题
        if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
        $bidding_config = new UtilConfig('store_config');
        $wechatPayTitle = $bidding_config->getConfigValue('wechat_pay_title');

        //微信支付接口
        include_once "pay/notify/lib/WxPay.Api.php";
		//include $_SERVER['DOCUMENT_ROOT']."/pay/notify/lib/WxPay.Api.php";
        $orderArgsObj = new WxPayUnifiedOrder();
        
        //设置必填参数
        $orderArgsObj->SetOut_trade_no( $orderInfo['order_num']);
        $orderArgsObj->SetBody($wechatPayTitle['data']);
        $orderArgsObj->SetTotal_fee($orderInfo['total_price'] * 100);//单位是分
        $orderArgsObj->SetTrade_type('JSAPI');
        $orderArgsObj->SetOpenid($userInfo['data']['account']);
        $orderArgsObj->SetTime_start(date("YmdHis"));
        $orderArgsObj->SetTime_expire(date("YmdHis", time() + 30000));

        //异步通知url未设置，则使用配置文件中的url
        $orderArgsObj->SetNotify_url(WxPayConfig::STORE_NOTIFY_URL);//异步通知url
        $payResult = WxPayApi::unifiedOrder($orderArgsObj);
		
        include_once "pay/notify/lib/WxPay.JsApiPay.php";
        $pay = new JsApiPay();
        try{
        	//file_put_contents('aa.txt', $userInfo['data']['account']);
            $jsApiParameters = $pay->GetJsApiParameters($payResult);
            echo json_encode( common::errorArray(0, "统一下单成功",$jsApiParameters) );
            return false;
        }catch (Exception $ex){//发生异常重定向
            echo json_encode( common::errorArray(1, "统一下单失败",'') );
            return false;
        }
	}
	/**
	 *用户订单删除
	 *参数用户订单id
	 */
	function deleteUserOrder(){

	    $this->verifyLogin($this);
	    $oid = $this->spArgs('id');
	    if(!class_exists('m_store_order')) include 'model/store/table/m_store_order.php';
	    $m_order = new m_store_order();
	    $orderResult = $m_order->delete(array('id' => $oid));
		echo json_encode($orderResult);

	}
	/*
	 * 订单详细里支付
	 * */
	function payOrderDetail(){
		//获取用户信息
		if(!class_exists('lib_user')) include "model/base/lib_user.php";
		$lib_user = new lib_user();
		$userInfo = $lib_user->findUser(array('id' => $_SESSION['user']['id'])); 
		
		$oid=$this->spArgs('oid');		
				
		//获取订单信息
		if(!class_exists('lib_order')) include 'model/store/lib_order.php';	
		$lib_order = new lib_order();
        $orderResult = $lib_order->getOrderInfo(array('id'=>$oid));
        $orderInfo = $orderResult['data'];
		
        //微信支付标题
        if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
        $bidding_config = new UtilConfig('store_config');
        $wechatPayTitle = $bidding_config->getConfigValue('wechat_pay_title');

        //微信支付接口
        include_once "pay/notify/lib/WxPay.Api.php";
		//include $_SERVER['DOCUMENT_ROOT']."/pay/notify/lib/WxPay.Api.php";
        $orderArgsObj = new WxPayUnifiedOrder();
        
        //设置必填参数
        $orderArgsObj->SetOut_trade_no( $orderInfo['order_num']);
        $orderArgsObj->SetBody($wechatPayTitle['data']);
        $orderArgsObj->SetTotal_fee($orderInfo['total_price'] * 100);//单位是分
        $orderArgsObj->SetTrade_type('JSAPI');
        $orderArgsObj->SetOpenid($userInfo['data']['account']);
        $orderArgsObj->SetTime_start(date("YmdHis"));
        $orderArgsObj->SetTime_expire(date("YmdHis", time() + 30000));

        //异步通知url未设置，则使用配置文件中的url
        $orderArgsObj->SetNotify_url(WxPayConfig::STORE_NOTIFY_URL);//异步通知url
        $payResult = WxPayApi::unifiedOrder($orderArgsObj);
		
        include_once "pay/notify/lib/WxPay.JsApiPay.php";
        $pay = new JsApiPay();
        try{
        	//file_put_contents('aa.txt', $userInfo['data']['account']);
            $jsApiParameters = $pay->GetJsApiParameters($payResult);
            echo json_encode( common::errorArray(0, "统一支付成功",$jsApiParameters) );
            return false;
        }catch (Exception $ex){//发生异常重定向
            echo json_encode( common::errorArray(1, "统一支付失败",'') );
            return false;
        }
	}
	
	/**
	 * 关闭订单(不确定是否有)
	 */
	function closeOrder(){
	    $this->verifyLogin($this);
	    $oid = $this->spArgs('id');
	    if(!class_exists('m_store_order')) include 'model/store/m_store_order.php';
	    $m_order = new m_store_order();
	    $orderResult = $m_order->find(array('id' => $oid));
	    $state = $orderResult['data']['state'];
	    if($state == 0){//待付款时可以关闭
	        if($orderResult['data']['user_coupon_id']){//释放优惠券
	            if(!class_exists('lib_coupon')) include 'model/market/lib_coupon.php';
	            $lib_coupon = new lib_coupon();
	            $lib_coupon->updateCoupon(array('id'=>$orderResult['data']['user_coupon_id']), array('is_use'=>0));
	        }
	        $result = $m_order->update(array('id' => $oid),array('state'=>4));
	        if($result){
	            echo json_encode(common::errorArray(0, "关闭成功", true));
	        }else{
	            echo json_encode(common::errorArray(1, "关闭失败", false));
	        }
	    }else{
	        echo json_encode(common::errorArray(1, "不允许关闭该订单", false));
	    }
	    $this->log(__CLASS__, __FUNCTION__, "关闭订单", 0, "del");
	}

	/*
	 *  ***************************************************************上面是order**************/
	
	
	/*
	 * -----------------------------------用户中心--------------------------------------
	 * */
	/**
	 * 用户中心主页
	 */
	function userCenter(){
	    $this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');  //获取前台模板所在文件夹
		
		//获取当前登录用户的信息
	    $condition = array('id'=>$_SESSION['user']['id']);
	    if(!class_exists('lib_user')) include 'model/base/lib_user.php';
	    $lib_user = new lib_user();
	    $userResult = $lib_user->findUser($condition);
	    $this->userInfo = $userResult['data'];
		
		//判断当前用户的类型(普通用户、诊所中心、医院中心、物流中心)
		$id=$condition['id'];
		
		//判断该用户是否是诊所工作人员
		if(!class_exists("lib_clinic"))include "model/store/lib_clinic.php";
		$lib_clinic=new lib_clinic();
		$sql="select * from store_clinic where find_in_set({$id},uid)";
		$clinicRes=$lib_clinic->findSql($sql);
		if($clinicRes){
			$this->type=1;
		}
		else{
			//判断该用户是否是医院工作人员
			if(!class_exists("lib_hospital"))include "model/store/lib_hospital.php";
			$lib_hospital=new lib_hospital();
			$sql="select * from store_hospital where find_in_set({$id},uid)";
			$hosRes=$lib_hospital->findSql($sql);
			if($hosRes){
				$this->type=2;
			}
			else{
				//判断该用户是否是物流工作人员
				if(!class_exists("lib_logistics"))include "model/store/lib_logistics.php";
				$lib_logistic=new lib_logistics();
				$sql="select * from store_logistic where user_id={$id}";
				$logisticRes=$lib_logistic->findSql($sql);
				if($logisticRes){
					$this->type=3;
				}
				else{
					$this->type=0;
				}
			}
		}
		
		/*
	    //代付款 待发货 已发货 交易成功
	    if(!class_exists('lib_order')) include 'model/store/lib_order.php';
	    $lib_order = new lib_order();
	    $waitPay = $lib_order->getNumOfState($_SESSION['user']['account'],0);
	    $this->waitPay = $waitPay['data'];//待付款数目
	
	    $waitSend = $lib_order->getNumOfState($_SESSION['user']['account'],1);
	    $this->waitSend = $waitSend['data'];//待发货数目
	
	    $hasSend = $lib_order->getNumOfState($_SESSION['user']['account'],2);
	    $this->hasSend = $hasSend['data'];//已发货数目
	
	    $dealSuccess = $lib_order->getNumOfState($_SESSION['user']['account'],5);
	    $this->dealSuccess = $dealSuccess['data'];//交易成功数目
	    
	    $cancel = $lib_order->getNumOfState($_SESSION['user']['account'],4);
	    $this->cancel = $cancel['data'];//已取消数目
		*/
	
	    $lib_config = new UtilConfig('store_config');
	    //获取微商城相关配置
	    $result = $lib_config->findConfigKeyValue();
	    $config = $result['data'];
	    $this->configInfo = $config;
		
	    //获取总体基础配置信息
	    $base_config = new UtilConfig('base_config');
	    $baseResult = $base_config->findConfigKeyValue();
	    $baseconfig = $baseResult['data'];
	    $this->baseconfigInfo = $baseconfig;

	    //购物车商品种类数量
		if(!class_exists('lib_cart')) include 'model/store/lib_cart.php';
		$lib_cart = new lib_cart();
		$account=$_SESSION['user']['account'];
		$cartCountResult = $lib_cart->findSql("select count(c.id) as num from store_cart_goods c inner join store_goods g on c.gid=g.id where c.account='{$account}' and g.updown=1");
		$_SESSION['cart']['cartNum'] = $cartCountResult[0]['num'];
		
		
	    //是否开启分销中心
	    if($baseResult['data']['plat_is_fen'] == 1 && $result['data']['is_fen'] == 1){
	        //用户是否分销商
	        if($userResult['data']['type'] == 3){
    	        if(!class_exists('lib_distributor')) include 'model/fen/lib_distributor.php';
    	        $lib_distributor = new lib_distributor();
    	        $distributor = $lib_distributor->findDistributor(array('user_id'=>$_SESSION['user']['id']));
	            $this->is_distributor = $distributor['data'];
	        }
	    }
		
		/*
	    //为了返回个人中心，传值
	    $this->moduleName ='store';
		
	    //是否管理员 进入订单管理
        if( strpos(','.$config['order_admin_openid'].',' , ','.$_SESSION['user']['open_id'].',' ) !== FALSE ){
	        $this->isAdmin = 1;
	    }else{
	        $this->isAdmin = 0;
	    }
		*/
	    $this->bottomNav = "userCenter";//底部导航选中标识
	    $this->log(__CLASS__, __FUNCTION__, "用户中心页面", 1, "view");  		$this->display("../template/front/store/{$this->theme}/page/user/userCenter.html");
	}
	
	/*
	 * 显示体检人员信息
	 * */
	function checkMember(){
		$this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');  //获取前台模板所在文件夹
		if(!class_exists('lib_check_member')) include 'model/store/lib_check_member.php';
		$lib_check_member=new lib_check_member();
		
		//显示当前用户所添加的体检人员信息
		$id=$_SESSION['user']['id'];
		$memRes=$lib_check_member->findAllMember(array('uid'=>$id));
		$this->memList=$memRes['data'];
		
		$this->bottomNav = "userCenter";//底部导航选中标识
	    $this->log(__CLASS__, __FUNCTION__, "体检人员信息", 1, "view");  		$this->display("../template/front/store/{$this->theme}/page/user/checkMemberList.html");
	}
	
	/*
	 * 修改体检人员默认选项
	 * */
	function changeMemState(){
		$condition['id']=$this->spArgs("id");
		$info['state']=$this->spArgs("state");
		if(!class_exists('lib_check_member')) include 'model/store/lib_check_member.php';
		$lib_check_member=new lib_check_member();
		if($info['state']==0){
			$result=$lib_check_member->updateMember($condition,$info);
		}
		else{
			$conditions['uid']=$_SESSION['user']['id'];
			$res=$lib_check_member->updateMember($conditions,array("state"=>0));
			if($res==true){
				$result=$lib_check_member->updateMember($condition,$info);
			}
		}
		$this->log(__CLASS__, __FUNCTION__, "修改体检人员默认选项", 0, "view");
        echo json_encode($result);
	}
	
	/*
	 * 添加体检人员信息
	 * */
	function addMember(){
		$this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');  //获取前台模板所在文件夹
		$this->bottomNav = "userCenter";//底部导航选中标识
	    $this->log(__CLASS__, __FUNCTION__, "添加体检人员信息", 1, "view");  		$this->display("../template/front/store/{$this->theme}/page/user/addMember.html");
	}
	
	/*
	 * 处理添加体检人员信息
	 * */
	function insertMember(){
		$memInfo = $this->getArgsList($this, array(name,phone,idCard,sex));
		if(!class_exists('lib_check_member')) include 'model/store/lib_check_member.php';
		$lib_check_member=new lib_check_member();
		$memInfo['uid']=$_SESSION['user']['id'];
		$result=$lib_check_member->addMember($memInfo);
		$this->log(__CLASS__, __FUNCTION__, "处理添加体检人员信息", 0, "view");
        echo json_encode($result);
	}
	
	/*
	 * 删除体检人员信息
	 * */
	function deleteMember(){
		$id=$this->spArgs('id');
		if(!class_exists('lib_check_member')) include 'model/store/lib_check_member.php';
		$lib_check_member=new lib_check_member();
		$result=$lib_check_member->deleteComplete($id);
		$this->log(__CLASS__, __FUNCTION__, "处理添加体检人员信息", 0, "view");
        echo json_encode($result);
	}
	
	/*
	 * 展示对应id的体检人员信息
	 * */
	function showMember(){
		$this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');  //获取前台模板所在文件夹
	    
	    $id=$this->spArgs("id");
		if(!class_exists('lib_check_member')) include 'model/store/lib_check_member.php';
		$lib_check_member=new lib_check_member();
		$memberInfo=$lib_check_member->getGoods($id);
		$this->memberInfo=$memberInfo['data'];
		$this->bottomNav = "userCenter";//底部导航选中标识
	    $this->log(__CLASS__, __FUNCTION__, "查看体检人员信息", 1, "view");  		$this->display("../template/front/store/{$this->theme}/page/user/showMember.html");
	}
	
	/*
	 * 编辑对应id的体检人员信息
	 * */
	function editMember(){
		$this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');  //获取前台模板所在文件夹
	    
	    $id=$this->spArgs("id");
		if(!class_exists('lib_check_member')) include 'model/store/lib_check_member.php';
		$lib_check_member=new lib_check_member();
		$memberInfo=$lib_check_member->getGoods($id);
		$this->memberInfo=$memberInfo['data'];
		$this->bottomNav = "userCenter";//底部导航选中标识
	    $this->log(__CLASS__, __FUNCTION__, "编辑体检人员信息", 1, "view");  		$this->display("../template/front/store/{$this->theme}/page/user/editMember.html");
	}
	
	/*
	 * 处理编辑的体检人员信息
	 * */
	function updateMember(){
		$memInfo = $this->getArgsList($this, array(name,phone,idCard,sex));
		if(!class_exists('lib_check_member')) include 'model/store/lib_check_member.php';
		$lib_check_member=new lib_check_member();
		$id=$this->spArgs('id');
		$result=$lib_check_member->updateMember(array('id'=>$id),$memInfo);
		$this->log(__CLASS__, __FUNCTION__, "处理编辑体检人员信息", 0, "view");
        echo json_encode($result);
	}
	
	/**
	 * --------------------------------------订单列表--------------------------------
	 * 
	 * 订单状态:0待付款 1待采样 2待送检 3送检中 4检测中 5已完成
	 */
	function orderList(){
	    $this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');
	    $state = $this->spArgs("state");
	    $this->state = $state;
	    $this->HttpHost = $_SERVER['HTTP_HOST'];
	    //获取百货商城配置
	    $lib_config = new UtilConfig('store_config');
	    $result = $lib_config->findConfigKeyValue();
	    $config = $result['data'];
		$this->bottomNav = "orderList";//底部导航选中标识
	    $this->configInfo = $config;//支持支付方式0仅货到付款1仅微信支付2货到付款和微信支付
	    $this->log(__CLASS__, __FUNCTION__, "订单列表页面", 1, "view");
	    $this->display("../template/front/store/{$this->theme}/page/user/orderList.html");
	}
	
	/**
	 * 异步显示登录用户的订单列表
	 */
	function pagingOrder(){
	    $page['pageIndex'] = $this->spArgs('start');
        $page['pageSize'] = $this->spArgs('num');
	    $sort = array(
	        array('field' => 'state', 'orderby' => 'asc'),
	        array('field' => 'add_time', 'orderby' => 'desc')
	    );
		
	    $conditions['account'] = $_SESSION['user']['account'];
	    $conditions['is_show'] = 1;
	
	    if(NULL != $this->spArgs('state') && "" != $this->spArgs('state')){
	        $conditions['state'] =  $this->spArgs('state');
	    }
		
//	    if(NULL != $this->spArgs('address') && "" != $this->spArgs('address')){
//	        $keywords['address'] = $this->spArgs('address');
//	    }
//      if(NULL != $this->spArgs('from') && "" != $this->spArgs('from')){
//          $createTime = array(
//              'from' =>$this->spArgs('from')
//          );
//      }
//      if(NULL != $this->spArgs('to') && "" != $this->spArgs('to')){
//          $createTime['to'] = $this->spArgs('to');
//      }

        if(!class_exists('lib_order')) include 'model/store/lib_order.php';
        $lib_order = new lib_order();
        $result = $lib_order->pagingOrder( $page, $conditions, $sort);
		
        $this->log(__CLASS__, __FUNCTION__, "用户订单列表", 0, "view");
        echo json_encode($result);
	}
	
	/*
	 * 物流中心显示所有状态在2、3、4、5的订单（未根据当前登录物流工作人员显示）
	 * 异步查询核销订单
	 * */
	function pagingCancelOrder(){
		$page = $this->getPageInfo($this);
		$state=$this->spArgs("state");
		
		if($state == '' || $state ==null){
			$condition="state in (2,3,4,5) and is_show=1";
		}
		else{
			$condition="state={$state} and is_show=1";
		}

		$sort = array(
	        array('field' => 'state', 'orderby' => 'asc'),
	        array('field' => 'add_time', 'orderby' => 'desc')
	    );
		
		if(!class_exists('lib_order')) include 'model/store/lib_order.php';
        $lib_order = new lib_order();
        $result = $lib_order->pagingOrder( $page, $condition, $sort);
		
        $this->log(__CLASS__, __FUNCTION__, "物流中心显示核销订单", 0, "view");
        echo json_encode($result);
	}
	
	/**
	 * 订单详情
	 * 存在多个订单详情页面，且展示效果有区别，为区分添加了判断条件
	 * 参数:from(来源):1:订单列表，2:医院诊所核销订单，3:物流中心核销订单
	 */
	function orderDetail(){
	    $this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');
		
		$this->from=$this->spArgs("from");
		
		//订单数据
		$oid=$this->spArgs("oid");
		if(!class_exists('lib_order')) include 'model/store/lib_order.php';
		$lib_order = new lib_order();
		$orderResult = $lib_order->getOrderInfo(array('id' => $oid));
		//获取订单中商品对应的销量
		foreach($orderResult['data']['goods_list'] as &$val){
			if(!class_exists('lib_goods')) include 'model/store/lib_goods.php';
			$lib_goods = new lib_goods();
			$goodsInfo=$lib_goods->getGoods($val['gid']);
			$val['sale_quantity']=$goodsInfo['data']['sale_quantity'];
		}
		
		/*根据订单id查询报告*/
		$this->reportInfo='';
		//判断订单状态是已完成
		if($orderResult['data']['state']==5){
			if(!class_exists('lib_report')) include 'model/store/lib_report.php';
			$lib_report=new lib_report();
			$reportInfo=$lib_report->getReport($oid);
			$reportInfo['data']['imgInfo']=json_decode($reportInfo['data']['img_str'],true);
			$this->reportInfo=$reportInfo['data'];
		}
		
		//体检人员信息
		if(!class_exists('lib_check_member')) include 'model/store/lib_check_member.php';
		$lib_check_member = new lib_check_member();
		if($orderResult['data']['member_info']){
			$memInfo['data'] = json_decode($orderResult['data']['member_info'],true);
		}
		else{
			$memInfo=$lib_check_member->getGoods($orderResult['data']['checkMember_id']);
		}
		
		//如已采样，显示采样的诊所名称
		$this->clinicInfo='';
		if($orderResult['data']['clinic_id']){
			if(!class_exists('lib_clinic')) include 'model/store/lib_clinic.php';
			$lib_clinic = new lib_clinic();
			$clinicInfo=$lib_clinic->getGoods($orderResult['data']['clinic_id']);
			$this->clinicInfo=$clinicInfo['data'];
		}
		
		//如已送检，显示送检的医院名称
		$this->hospitalInfo='';
		if($orderResult['data']['hospital_id']){
			if(!class_exists('lib_hospital')) include 'model/store/lib_hospital.php';
			$lib_hospital = new lib_hospital();
			$hospitalInfo=$lib_hospital->getGoods($orderResult['data']['hospital_id']);
			$this->hospitalInfo=$hospitalInfo['data'];
		}
		
		$this->orderInfo = $orderResult['data'];
		$this->memberInfo = $memInfo['data'];
		
		/*
		 *
		if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
		$lib_config = new UtilConfig('store_config');
		$result = $lib_config->findConfigKeyValue();
		$config = $result['data'];
		if($config['order_admin_openid'] == $_SESSION['user']['account']){
		   	$this->flag = 1;
		}else{
		    $this->flag = 0;
		}
		*/
		
		$this->log(__CLASS__, __FUNCTION__, "订单详情", 1, "view");
		$this->display("../template/front/store/{$this->theme}/page/order/orderDetail.html");
	}
	
	/*
	 * 上传订单报告
	 * */
	function addReport(){
		$this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');
	    $this->oid=$this->spArgs("oid");
	    $this->log(__CLASS__, __FUNCTION__, "上传订单报告", 1, "view");
	    $this->display("../template/front/store/{$this->theme}/page/order/addReport.html");
	}
	
	/*
	 * 上传报告图片
	 * */
	function upLoadImage(){
		//验证图片能否上传   
        if($_FILES) $verify = UtilImage::verifyImage();
        if($verify['errorCode'] == 1) exit(json_encode($verify));
        //上传图片生成缩略图
        if(!empty($_FILES)) $imageResult = UtilImage::uploadPhoto('imgUrl','upload/image/report/',300,300);
        
        if($imageResult){
            $goodsInfo['img_url'] = $imageResult['url'];
            $goodsInfo['thumb'] = $imageResult['thumb'];
        }
        if($goodsInfo['img_url']){
            echo json_encode(common::errorArray(0,'图片上传成功',$goodsInfo));
        }else{
            echo json_encode(common::errorArray(1,'图片上传失败',''));
        }
	}
	
	/*
	 * 删除上传图片
	 * */
	function deleteUpLoadImage(){
		$url = $this->spArgs('img_url');
        $thumb = substr($url, 0,strripos($url,'.')) . "_thumb" .  substr($url, strripos($url,'.'));
        unlink($url);
        unlink($thumb);
        echo json_encode(common::errorArray(0,'图片删除成功',''));
	}
	
	/*
	 * 处理上传报告 
	 * */
	function insertReport(){
		$this->verifyLogin($this);
		$reportInfo = $this->getArgsList($this, array(report_desc,oid,img_str));
		$reportInfo['user_id']=$_SESSION['user']['id'];
		
		if(!class_exists('lib_report')) include 'model/store/lib_report.php';
	    $lib_report = new lib_report();
	    //判断是否重复提交
	    $arr=$lib_report->getReport($reportInfo['oid']);
		if($arr['errorCode']==0){
			echo json_encode(common::errorArray(1, "报告已提交", false));
			die;
		}

		$result=$lib_report->addReport($reportInfo);
		//dump($result);die;
		if($result['errorCode']==0){
			if(!class_exists('lib_order')) include 'model/store/lib_order.php';
	    	$lib_order = new lib_order();
			$update=array("state"=>5,"end_time"=>date("Y-m-d H:i:s"));
			
			//设置订单状态为5,添加订单完成时间，标识订单已完成
			$res=$lib_order->updateOrder(array("id"=>$reportInfo['oid']),$update); 
			
			if($res['errorCode']==0){
				//微信模板通知用户订单已完成
				$orderResult = $lib_order->getOrderInfo(array('id' => $reportInfo['oid']));
				
				if(!class_exists('lib_hospital')) include 'model/store/lib_hospital.php';
				$lib_hospital = new lib_hospital();
				$hosInfo=$lib_hospital->getGoods($orderResult['data']['hospital_id']);
				
				$goodName="";
				foreach($orderResult['data']['goods_list'] as $val){
					$goodName.=$val['goods_name'].',';
				}
				$goodName=substr($goodName,0,-1);
				$url=ROOT_URL.'/index.php?c=store&a=orderList';
				//微信模板通知
				$data = array(
	              	'first'=>array('value'=>'您好，您的体检报告已产生', 'color'=>'#000000'),
	               	'keyword1'=>array('value'=>$hosInfo['data']['name'], 'color'=>'#173177'),
	             	'keyword2'=>array('value'=>$orderResult['data']['nick_name'], 'color'=>'#173177'),
	             	'keyword3'=>array('value'=>$goodName, 'color'=>'#173177'),
	             	'keyword4'=>array('value'=>date('Y-m-d'), 'color'=>'#173177'),
	             	'remark'=>array('value'=>'查看详情', 'color'=>'#1A1AC9'),
	      		);
				
				if(!class_exists('lib_user')) include 'model/base/lib_user.php';
				$lib_user = new lib_user();
				$userInfo=$lib_user->findUser(array('account'=>$orderResult['data']['account']));
				
				$touser = $userInfo['data']['open_id'];
				$templateId="8OB0NNRFpW-FCskez2nwjsP8p-a9yfhK4AEbo0a-6Jc";
				if(!class_exists('TemplateMessage')) include 'include/wechatUtil/TemplateMessage.php';
			 	TemplateMessage::sendTemplateMessage($data, $touser, $templateId,$url);
				
				//结算佣金
				$this->makeMoney($reportInfo['oid']);
				echo json_encode($result);
			}
			else{
				$lib_report->deleteComplete($result['data']['rid']);
				echo json_encode(common::errorArray(1, "更新订单状态失败", false));
			}
		}
		else{
			echo json_encode($result);
		}
	}
	
	/*
	 * 处理佣金
	 * 参数oid：订单id
	 * */
	function makeMoney($oid){
		//获取订单信息
		if(!class_exists('lib_order')) include 'model/store/lib_order.php';
	    $lib_order = new lib_order();
		$orderInfo=$lib_order->getOrderInfo(array('id'=>$oid));
		
		//根据订单表的account找到下单的用户id
		if(!class_exists('lib_user')) include 'model/base/lib_user.php';
	    $lib_user = new lib_user();
		$userInfo=$lib_user->findUser(array('account'=>$orderInfo['data']['account']));
		
		//根据用户id找到对应的分销商信息
		if(!class_exists('lib_distributor')) include 'model/fen/lib_distributor.php';
	    $lib_distributor = new lib_distributor();
		$disInfo=$lib_distributor->findDistributor(array('user_id'=>$userInfo['data']['id']));
		
		//找到需返佣的上级分销商id和上上级分销商id
		$parent_id=$disInfo['data']['parent_id'];
		$grand_id=$disInfo['data']['grand_id'];
		
		//找到分销商配置,查询对应的比例和限期天数
		if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
	    $lib_config = new UtilConfig('fen_config');
		$configResult = $lib_config->findConfigThemeValue();
		
		
		//交易记录表(存储分佣的佣金明细)
		if(!class_exists('lib_deal_record')) include 'model/fen/lib_deal_record.php';
	    $lib_deal_record = new lib_deal_record();
		$addInfo['oid']=$oid;
		$addInfo['order_num']=$orderInfo['data']['order_num'];
		$addInfo['total_price']=$orderInfo['data']['total_price'];
		
		//判断是否已过规定的分销分佣限制日期
		$day=(time()-strtotime($disInfo['data']['add_time']))/3600/24;
		if($day<=$configResult['data']['distribute_day']){    //满足分佣条件
			//判断上级存在
			if($parent_id != 0){
				//计算上级的分佣金额
				$parent_money=round($orderInfo['data']['total_price']*$configResult['data']['self_rank_ratio'],2);
				//将该金额加到对应分销商的可提金额中
				$sql="update fen_distributor set my_fee=my_fee+{$parent_money},total_fee=total_fee+{$parent_money} where id={$parent_id}";
				$res=$lib_distributor->runSql($sql);
				if($res){
					$parentInfo=$lib_distributor->findDistributor(array('id'=>$parent_id));
					
					$addInfo['distributor_name']=$parentInfo['data']['name'];
					$addInfo['distributor_id']=$parent_id;
					$addInfo['distributor_type']=1;
					$addInfo['user_id']=$parentInfo['data']['user_id'];
					$addInfo['money']=$parent_money;
					$lib_deal_record->addRecord($addInfo);
				}
			}
			
			//判断上上级存在
			if($grand_id != 0){
				//计算上上级的分佣金额
				$grand_money=round($orderInfo['data']['total_price']*$configResult['data']['parent_rank_ratio'],2);
				//将该金额加到对应分销商的可提金额中
				$sql="update fen_distributor set my_fee=my_fee+{$grand_money},total_fee=total_fee+{$grand_money} where id={$grand_id}";
				$res2=$lib_distributor->runSql($sql);
				if($res2){
					$grandInfo=$lib_distributor->findDistributor(array('id'=>$grand_id));
					$addInfo['distributor_name']=$grandInfo['data']['name'];
					$addInfo['distributor_id']=$grand_id;
					$addInfo['distributor_type']=1;
					$addInfo['user_id']=$grandInfo['data']['user_id'];
					$addInfo['money']=$grand_money;
					$lib_deal_record->addRecord($addInfo);
				}	
			}
		}

		/*
		 * 处理诊所的佣金
		 * */
		//查询诊所的佣金
		if($orderInfo['data']['clinic_id']){
			if(!class_exists('lib_clinic')) include 'model/store/lib_clinic.php';
		    $lib_clinic = new lib_clinic();
			$clinicInfo=$lib_clinic->getGoods($orderInfo['data']['clinic_id']);
			$clinic_money=$clinicInfo['data']['clinic_ratio'];
			$clinic_id=$orderInfo['data']['clinic_id'];
			$sql="update store_clinic set money=money+{$clinic_money},total_fee=total_fee+{$clinic_money} where id={$clinic_id}";
			$res3=$lib_clinic->runSql($sql);
			if($res3){
				$addInfo['distributor_name']=$clinicInfo['data']['name'];
				$addInfo['distributor_id']=$clinic_id;
				$addInfo['distributor_type']=2;
				$addInfo['user_id']=$orderInfo['data']['clinic_worker']; //订单处理人员id
				$addInfo['money']=$clinic_money;
				$lib_deal_record->addRecord($addInfo);
			}
		}
		
		/*
		//处理代理的分成
		$where['province'] =$clinicInfo['data']['province'];
		$where['city'] =$clinicInfo['data']['city'];
		$where['area'] =$clinicInfo['data']['area'];

		if(!class_exists('lib_agent')) include 'model/store/lib_agent.php';
	    $lib_agent = new lib_agent();
	    $agentInfo=$lib_agent->getAgentInfo($where);
	    $agent_money=round($agentInfo['data']['rate']*$orderInfo['data']['total_price']/100,2);
	    $sql="update store_agent set money=money+{$agent_money} where id={$agentInfo['data']['id']}";
	    $res6=$lib_agent->runSql($sql);
	    if($res6){
	    	$addInfo['distributor_name']=$agentInfo['data']['name'];
			$addInfo['distributor_id']=$agentInfo['data']['id'];
			$addInfo['distributor_type']=4;   //代理
			$addInfo['user_id']='';   //没有订单用户id(或处理用户id)
			$addInfo['money']=$agent_money;
			$lib_deal_record->addRecord($addInfo);
	    }
		*/
		
		/*
		 * 处理医院的佣金
		 * */
		//查询医院的佣金比例
		if($orderInfo['data']['hospital_id']){
			if(!class_exists('lib_hospital')) include 'model/store/lib_hospital.php';
		    $lib_hospital = new lib_hospital();
			$hospitalInfo=$lib_hospital->getGoods($orderInfo['data']['hospital_id']);
			$hospital_money=round($hospitalInfo['data']['hospital_ratio']*$orderInfo['data']['total_price']/100,2);
			$hospital_id=$orderInfo['data']['hospital_id'];
			$sql="update store_hospital set money=money+{$hospital_money},total_fee=total_fee+{$hospital_money} where id={$hospital_id}";
			$res4=$lib_hospital->runSql($sql);
			if($res4){
				$addInfo['distributor_name']=$hospitalInfo['data']['name'];
				$addInfo['distributor_id']=$hospital_id;
				$addInfo['distributor_type']=3;
				$addInfo['user_id']=$orderInfo['data']['hospital_worker']; //订单处理人员id
				$addInfo['money']=$hospital_money;
				$lib_deal_record->addRecord($addInfo);
			}
		}
	}
	
	/* 
	 * 查看报告(通过订单id)
	 * 
	 * */
	function showReport(){
		$this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');
		$oid=$this->spArgs("oid");
		if(!class_exists('lib_report')) include 'model/store/lib_report.php';
		$lib_report=new lib_report();
		$reportInfo=$lib_report->getReport($oid);
		$reportInfo['data']['imgInfo']=json_decode($reportInfo['data']['img_str'],true);
		$this->reportInfo=$reportInfo['data'];
		$this->signPackage = JsSdk::getSignPackage();
		$this->log(__CLASS__, __FUNCTION__, "查看报告", 1, "view");
	    $this->display("../template/front/store/{$this->theme}/page/order/showReport.html");
	}
	
	/*
	 * 生成二维码
	 * */
	function qrcode(){
		//生成订单二维码
		include_once 'include/UtilQRcode.php';
        $qrcode = new UtilQRcode();
		
		$oid=$this->spArgs("oid");
		$url=ROOT_URL."/qrcode.html?oid={$oid}";
        $qrcode->getQRcode($url);
	}
	
	/*
	 * 扫一扫(扫描核销订单)
	 * 
	 * */
	function scanOrder(){
		header("content-type:text/html;charset=utf-8");
		$oid=$this->spArgs('oid');
		$uid=$_SESSION['user']['id'];
		if(!class_exists('lib_user')) include 'model/base/lib_user.php';
		$lib_user=new lib_user();
		$userInfo=$lib_user->findUser(array("id"=>$uid));

		if(!class_exists('lib_order')) include 'model/store/lib_order.php';
		$lib_order = new lib_order();
		$orderInfo=$lib_order->getOrderInfo(array('id'=>$oid));
		if($orderInfo['data']['state']==5){
			echo "<script>alert('订单已完成')</script>";
			die;
		}
		
		//诊所
		if($userInfo['data']['user_type']==1){
			if(!class_exists('lib_clinic')) include 'model/store/lib_clinic.php';
			$lib_clinic=new lib_clinic();
			$sql="select * from store_clinic where find_in_set({$uid},uid)";
			//查询诊所信息
			$result=$lib_clinic->findSql($sql);
			if($result){
				$info['clinic_id']=$result[0]['id'];
				$info['clinic_area']=$result[0]['province'].$result[0]['city'].$result[0]['area'];
				$info['clinic_worker']=$uid;
				$info['clinic_worker_name']=$userInfo['data']['nick_name'];
				$info['state']=2;
				$this->printOrderTicket($oid,$result[0]['name']);
				$res=$this->updateOrderState($oid,$info);
				if($res['errorCode']==0){
					echo "<script>alert('已核销')</script>";
					$this->jump("index.php?c=store&a=clinicCenter");
				}
			}
		}
		//医院
		else if($userInfo['data']['user_type']==2){
			if(!class_exists('lib_hospital')) include 'model/store/lib_hospital.php';
			$lib_hospital=new lib_hospital();
			$sql="select * from store_hospital where find_in_set({$uid},uid)";
			$result=$lib_hospital->findSql($sql);
			if($result){
				$info['hospital_id']=$result[0]['id'];
				$info['hospital_worker']=$uid;
				$info['hospital_worker_name']=$userInfo['data']['nick_name'];
				$info['state']=4;
				$res=$this->updateOrderState($oid,$info);
				if($res['errorCode']==0){
					//微信模板通知用户订单已送到医院检测
					if(!class_exists('lib_order')) include 'model/store/lib_order.php';
					$lib_order = new lib_order();
					$orderInfo=$lib_order->getOrderInfo(array('id'=>$oid));
					
					$url=ROOT_URL.'/index.php?c=store&a=orderList';
					//微信模板通知
					$data = array(
		              	'first'=>array('value'=>'您好,您的体检订单有了新进展', 'color'=>'#000000'),
		               	'keyword1'=>array('value'=>$orderInfo['data']['order_num'], 'color'=>'#173177'),
		             	'keyword2'=>array('value'=>date('Y-m-d'), 'color'=>'#173177'),
		             	'keyword3'=>array('value'=>'已受理', 'color'=>'#173177'),
		             	'keyword4'=>array('value'=>$result[0]['name'], 'color'=>'#173177'),
		             	'keyword5'=>array('value'=>$result[0]['phone'], 'color'=>'#173177'),
		             	'remark'=>array('value'=>'如有疑问,请致电,谢谢', 'color'=>'#1A1AC9'),
		      		);
					
					$touser = $orderInfo['data']['account'];
					$templateId="3HaJ_pDicrVNWch2GIJkvvWNTPRwZ6WzkQO9DpQ-Ehg";
					if(!class_exists('TemplateMessage')) include 'include/wechatUtil/TemplateMessage.php';
				 	TemplateMessage::sendTemplateMessage($data, $touser, $templateId,$url);
					echo "<script>alert('已核销')</script>";
					$this->jump("index.php?c=store&a=hospitalCenter");
				}
			}
		}
		//物流
		else if($userInfo['data']['user_type']==3){
			$info['logistics_worker']=$uid;
			$info['logistics_worker_name']=$_SESSION['user']['nick_name'];
			$info['state']=3;
			$res=$this->updateOrderState($oid,$info);
			if($res['errorCode']==0){
				echo "<script>alert('已核销')</script>";
				$this->jump("index.php?c=store&a=logistics");
			}
		}
	}
	
	/*
	 * 订单状态改变(核销订单)
	 * 参数: $oid =>订单id
	 * 		 $info => 更改的数据
	 * */
	function updateOrderState($oid,$info){
		$state=$info["state"];    //获取传来的要改变的状态值
		
		if(!class_exists('lib_order')) include 'model/store/lib_order.php';
		$lib_order = new lib_order();
		$orderInfo=$lib_order->getOrderInfo(array('id'=>$oid));
		
		//订单用户到诊所采样成功(诊所核销)(待送检)
		if($state==2){
			$info['sample_time']=date("Y-m-d H:i:s");		
			$result=$lib_order->updateOrder(array('id'=>$oid),$info);
		}
		
		//物流人员核销后(送检中)
		if($state==3){
			$info['check_time']=date("Y-m-d H:i:s");
			$result=$lib_order->updateOrder(array('id'=>$oid),$info);
		}

		//医院核销后(检测中)
		if($state==4){
			$result=$lib_order->updateOrder(array('id'=>$oid),$info);
		}

		return result;
	}

	/**
	 * 订单支付后打印订单小票
	 */
	function printOrderTicket($oid,$name){
		//查配置信息
		if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
		$lib_config = new UtilConfig('store_config');
		$configInfoResult = $lib_config->findConfigKeyValue();
		
		//查订单信息
		if(!class_exists('lib_order')) include 'model/store/lib_order.php';
	    $lib_order = new lib_order();
		$orderResult = $lib_order->getOrderInfo(array('id'=>$oid));
		
		//采样诊所名称
		$orderResult['data']['clinic_name']=$name;
		
		//根据检查人员id查询检查人员信息
		if(!class_exists('lib_check_member')) include 'model/store/lib_check_member.php';
		$lib_check_member=new lib_check_member();
		if($orderResult['data']['member_info']){
			$memberInfo = json_decode($orderResult['data']['member_info'],true);
		}
		else{
			$memberInfo = $lib_check_member->getGoods($orderResult['data']['checkMember_id']);
			$memberInfo = $memberInfo['data'];
		}
		$orderResult['data']['name']=$memberInfo['name'];
		$orderResult['data']['sex']=$memberInfo['sex'];
		$orderResult['data']['idCard']=$memberInfo['idCard'];
		
		$qrcode=ROOT_URL."/qrcode.html?oid={$oid}";
		$orderResult['data']['qrcode']= $qrcode;
		
		$this->ticketNotify($orderResult,$configInfoResult);
	}

	/**
	 * 小票打印机通知
	 * @param array $orderInfoResult
	 * @param array $configInfoResult
	 */
	public function ticketNotify($orderInfoResult,$configInfoResult){
		if($configInfoResult['data']['order_ticket_notify']){//小票打印机通知
			include_once 'include/PrintTicket.php';
			$printTicket = new PrintTicket();
			$mesageInfo = array(
				'ticket_title'=>$configInfoResult['data']['ticket_title'],
				'num'=>time(),
				'order_num'=>$orderInfoResult['data']['order_num'],
				'time'=>$orderInfoResult['data']['add_time'],
				'name'=>$orderInfoResult['data']['name'],
				'sex'=>$orderInfoResult['data']['sex'],
				'idCard'=>$orderInfoResult['data']['idCard'],
				'total_price'=>$orderInfoResult['data']['total_price'],
				'goodsList'=>$orderInfoResult['data']['goods_list'],
				'clinic_name'=>$orderInfoResult['data']['clinic_name'],
				'clinicID'=>$orderInfoResult['data']['clinicID'],
				'ticket_phone'=>$configInfoResult['data']['ticket_phone'],
				'ticket_ad'=>$configInfoResult['data']['ticket_ad'],
				'ticket_qcode'=>$orderInfoResult['data']['qrcode'],
				'ticket_is_double'=>$configInfoResult['data']['ticket_is_double']
			);
			
			if($orderInfoResult['data']['pay_method'] == 1){
				$mesageInfo['pay_method'] ="微信支付";
			}else if($orderInfoResult['data']['pay_method'] == 2){
				$mesageInfo['pay_method'] ="支付宝支付";
			}else if($orderInfoResult['data']['pay_method'] == 3){
				$mesageInfo['pay_method'] ="货到付款";
			}
			$deviceInfo['ticket_device_num'] = $configInfoResult['data']['ticket_device_num'];
			$deviceInfo['ticket_device_key'] = $configInfoResult['data']['ticket_device_key'];
			
			$result = $printTicket->sendNewPrint($mesageInfo,$deviceInfo);//新版小票打印机通知
			if($result['errorCode'] != 0){
				$log = Log::getInstance();
				$log->log($result['errorInfo'],'ERROR');
			}
		}
	}

	/**
	 * 根据坐标获取所在地信息
	 */
	function getLocation(){
	    if(!$_SESSION['area']['longitude']){
	        $result = $this->getLocationByLonAndLat($this->spArgs('longitude'), $this->spArgs('latitude'));
	        $_SESSION['area'] = $result;
	    }else{
	        $result = $_SESSION['area'];
	    }
	    echo json_encode(common::errorArray(0, '定位成功', $result));
	}

	/**
	 * 百度地图：根据经纬度来获取地址信息(转坐标、定位城市)
	 */
	public function getLocationByLonAndLat($longitude,$latitude){
	    //转为百度地图坐标
	    $result = $this->gcjToBaidu($latitude,$longitude);
	    $latitude = $result['lat'];
	    $longitude = $result['lng'];
		$url="http://api.map.baidu.com/geocoder?location=".$latitude.",".$longitude."&output=json&key=E8585e29607347015477b67178b530ab";
	    $str=file_get_contents($url);
	    $res=json_decode($str,true);
	    // dump($res);
	    if($res['status'] == 'OK'){//成功
	        $area['city'] = $res['result']['addressComponent']['city'];
	        $area['provinces'] = $res['result']['addressComponent']['province'];//省
	        $area['citys'] = $res['result']['addressComponent']['city'];//市
	        $area['districts'] = $res['result']['addressComponent']['district'];//县、区
	        $city_arr=explode("市",$area['city']);
			$area['city']=$city_arr[0];
	        $area['address'] = $res['result']['formatted_address'];
	    }else{//失败
	        $area['city'] = "未知城市";
	        $area['address'] = "地址获取失败";
	    }
	    $area['longitude'] = $longitude;
	    $area['latitude'] = $latitude;
	    // dump($area);
	    return $area;
	}
	
	/**
	 * 火星坐标转为百度坐标
	 * @param string $lat 纬度
	 * @param string $lng 经度
	 */
	private function gcjToBaidu($lat,$lng){
	    $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
	    $z = sqrt($lng * $lng+ $lat * $lat) + 0.00002 * sin($lat * $x_pi);
	    $theta = atan2($lat, $lng) + 0.000003 * cos($lng * $x_pi);
	    $lng = $z * cos($theta) + 0.0065;
	    $lat = $z * sin($theta) + 0.006;
	    return array('lat' => $lat,'lng' => $lng);
	}
	/**
	 * 医院展示
	 * @return 筛选后的医院
	 */
	
	function haspitalintroduce(){
		
		$this->verifyLogin($this);

	    if(!class_exists('m_base_hospital')) include 'model/store/table/m_base_hospital.php';
	    
	    $store_hospital=new m_base_hospital();

		if($_SESSION['area']['districts'] != null){
		
			$array=$store_hospital->findAll(array('area'=>$_SESSION['area']['districts']));
			
			if($array == null){
				
				$city=str_replace('市','',$_SESSION['area']['citys']);

				$array=$store_hospital->findAll(array('city'=>$city));

				if($array == null){
					
					$array='';
					
				}
			}
		}
		// $array=$store_hospital->findAll();
		$this->lib_common->getMobileCommonDataFront($this,'store_theme');

		$this->haspitalintroduce=$array;

		$this->display("../template/front/store/{$this->theme}/page/hospitalintroduce/hospitalintrduce.html");
	}
	/*
	 * 
	 *医院详情
	 * @param $id传入的医院id
	 * @return 医院详情
	 */
	function haspitaldetail(){
		
		$this->verifyLogin($this);
		
		$id = $this->spArgs('id');
		
		if(!class_exists('m_base_hospital')) include 'model/store/table/m_base_hospital.php';
	    
	    $store_hospital=new m_base_hospital();

		$data=$store_hospital->find(array('id'=>$id));
		
		$this->hospitaldetail=$data;
		
		$this->signPackage = JsSdk::getSignPackage();
		
		$this->lib_common->getMobileCommonDataFront($this,'store_theme');

		$this->display("../template/front/store/{$this->theme}/page/hospitalintroduce/hospitaldetail.html");
		
	}
	/*
	 *用户切换地址
	 *
     **/
     function modifyCity(){
     	$this->verifyLogin($this);
     	$site=$this->spArgs('site');
     	$site=explode(' ',$site);
     	$_SESSION['area']['provinces']=$site['0'];
     	$_SESSION['area']['citys']=$site['1'];
     	// 判断是否有区县
     	if(!array_key_exists('2',$site)){

     		$site['2']=0;

     	}

     	if($site['2']=='0'){

     		$_SESSION['area']['districts']=$site['1'];
     	}else{

     		$_SESSION['area']['districts']=$site['2'];

     	}

     }
}