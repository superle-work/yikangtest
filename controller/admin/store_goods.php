<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_goods')) require 'model/store/lib_goods.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';

if(!class_exists('PHPExcel')) require 'include/PHPExcel.php';
if(!class_exists('Excel2007')) require 'include/PHPExcel/Reader/Excel2007.php';
if(!class_exists('Excel5')) require 'include/PHPExcel/Reader/Excel5.php';
if(!class_exists('IOFactory')) include 'include/PHPExcel/IOFactory.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
/**
 *商城商品管理
 * @name store_goods.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class store_goods extends admin_controller{
    private $lib_goods;

    /**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	    $this->lib_goods = new lib_goods();
	}
	
	/**
	 * 商品选择类别
	 */
	function selectCategory(){
		$this->getMenu($this);
		
		if(!class_exists('lib_category')) include 'model/store/lib_category.php';
		$lib_category = new lib_category();
	    $cateResult = $lib_category->findAllCategory(array('is_use' => 1, 'rank' => 1), 'order_index asc');
	    $this->firCateList = $cateResult['data'];
	    $cateResult = $lib_category->findAllCategory(array('is_use' => 1, 'rank' => 2), 'fir asc, order_index asc');
	    $this->secCateList = $cateResult['data'];
	    $cateResult = $lib_category->findAllCategory(array('is_use' => 1, 'rank' => 3), 'sec asc, order_index asc');
	    $this->thrCateList = $cateResult['data'];
	    $cateResult = $lib_category->findAllCategory(array('is_use' => 1, 'rank' => 4), 'thr asc, order_index asc');
	    
	    $this->fouCateList = $cateResult['data'];
		$this->log(__CLASS__, __FUNCTION__, "体检项目选择类别", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/goods/selectCategory.html");
	}

	/**
	 * 跳转添加商品信息页面
	 */
	function addGoods(){
		$this->getMenu($this);
        $cids = $this->spArgs('cids');
        if(!class_exists('lib_category')) include 'model/store/lib_category.php';
        $lib_category = new lib_category();
        $cateResult = $lib_category->getCategorys("id in ({$cids}) and is_use = 1", "fir asc, sec asc, thr asc, fou asc",null);
		
        //获取微商城相关配置
        $lib_config = new UtilConfig('store_config');
	    $result = $lib_config->findConfigKeyValue();
	    $config = $result['data'];
	    $this->configInfo = $config;
	    //基础配置是否开启分销配置
	    $base_config = new UtilConfig('base_config');
	    $base_config = $base_config->findConfigKeyValue();
	    if($result['data']['is_fen'] == 1 && $base_config['data']['plat_is_fen'] == 1){
	        $fen_config = new UtilConfig('fen_config');
	        $fen_config = $fen_config->findConfigKeyValue();
	        if($fen_config['data']['cash_type'] == 1){
    	        $this->goods_fen = 1;
	        }
	    }
        $this->cateList = $cateResult['data'];      
        $this->cids = $cids;
        $this->log(__CLASS__, __FUNCTION__, "跳转添加体检项目信息页面", 1, 'add');
		$this->display("../template/admin/{$this->theme}/store/page/goods/addGoods.html");
	}

	/**
	 * 跳转商品列表信息页面
	 */
	function goodsList(){
		$this->getSetMenu($this);
        //一级分类
        $conditions = array('rank' => 1);
        if(!class_exists('lib_category')) include 'model/store/lib_category.php';
        $lib_category = new lib_category();
        $firCateResult = $lib_category->getCategorys($conditions, "order_index asc");
        $this->categoryList = $firCateResult['data'];
        $this->log(__CLASS__, __FUNCTION__, "跳转体检项目列表信息页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/goods/goodsList.html");
	}

	private $arr = ['category','name','updown','detail_desc','good_type','ori_price','discount','price','sample_vessel','time_length','apply','sort_num','recommend','province','city','area'];
//	private $arr = ['category'=>'体检项目分类','name'=>'体检项目名称','updown'=>'上下架','detail_desc'=>'体检项目详情简述','good_type'=>'体检项目类别','ori_price'=>'原价','discount'=>'优惠比例','price'=>'优惠价','sample_vessel'=>'采样容器','time_length'=>'时长','apply'=>'适用内容','sort_num'=>'排序系数','recommend'=>'首页推荐','province'=>'省','city'=>'市','area'=>'区县'];

    /**
     * 导入体检项目
     */
    function importExcel(){
        $aa = $_FILES['test'];
        $file = iconv("utf-8", "gb2312", $aa['tmp_name']);   //转码
        if(empty($file) OR !file_exists($file)) {
            die('file not exists!');
        }
        $objRead = new PHPExcel_Reader_Excel2007();   //建立reader对象
        if(!$objRead->canRead($file)){
            $objRead = new PHPExcel_Reader_Excel5();
            if(!$objRead->canRead($file)){
                die('No Excel!');
            }
        }
        //$cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $objRead = \PHPExcel_IOFactory::createReader('Excel2007');
        $obj = $objRead->load($aa['tmp_name']);  //建立excel对象
        $currSheet = $obj->getSheet(0)->toArray();   //获取指定的sheet表
//        $columnH = $currSheet->getHighestColumn();   //取得最大的列号
//        $columnCnt = array_search($columnH, $cellName);
//        $rowCnt = $currSheet->getHighestRow();   //获取总行数
//        $data = array();
//        for($_row=1; $_row<=$rowCnt; $_row++){  //读取内容
//            for($_column=0; $_column<=$columnCnt; $_column++){
//                $cellId = $cellName[$_column].$_row;
//                $cellValue = $currSheet->getCell($cellId)->getValue();
////                array_search($cellValue,$this->arr);
//                //$cellValue = $currSheet->getCell($cellId)->getCalculatedValue();  #获取公式计算的值
//                if($cellValue instanceof PHPExcel_RichText){   //富文本转换字符串
//                    $cellValue = $cellValue->__toString();
//                }
//                $data[$_row][$cellName[$_column]] = $cellValue;
//            }
//        }
        $data = array();
        foreach($currSheet as $key =>$val){
            $data[] = array_combine(array_values($this->arr), $val);
        }
        unset($data[0]);
        for($i = 1; $i <= count($data); $i++){
            $data[$i]["add_time"] = date ('Y-m-d H:i:s',strtotime('+8hour'));
            $data[$i]["creator"] = $_SESSION['admin']['account'];
            $resultGoods = $this->lib_goods->addGoods ($data[$i]);
            if(!class_exists('lib_category')) include 'model/store/lib_category.php';
            $lib_category = new lib_category();
            $cateResult = $lib_category->getCategorys("name = '{$data[$i]['category']}' and is_use = 1", "fir asc, sec asc, thr asc, fou asc",null);
            $cateResult['data'][0]['gids'] = $cateResult['data'][0]['gids'].','.$resultGoods['data']['gid'];
            $conditions = array(
                'id' => $cateResult['data'][0]['id']
            );
            $valueList['gids'] = $cateResult['data'][0]['gids'];
            $result = $lib_category->updateCategory($conditions,$valueList);
        }
        return $result;
    }
	
	/**
     * 商品详情页面
     */
    function goodsDetail(){
        $this->getMenu($this);
        //商品基本信息
        $result = $this->lib_goods->getGoods($this->spArgs('id'));
        $this->goodsInfo = $result['data'];
        //辅图信息
        $imgListResult = $this->lib_goods->getGoodsImageList($this->spArgs('id'));
        $totalImage = count($imgListResult['data']);
        for($i = 0;$i  <  $totalImage ;$i++){
            $j = $i +1;
            $str = "sideImg{$j}";
            $this->$str= $imgListResult['data'][$i];
        }
        
        //判断是否开启了积分模式
        $lib_config = new UtilConfig('store_config');
        $result = $lib_config->findConfigKeyValue();
        $this->isPoints = $result['data']['mall_points'];
        
        //基础配置是否开启分销配置
        $base_config = new UtilConfig('base_config');
        $base_config = $base_config->findConfigKeyValue();
        if($result['data']['is_fen'] == 1 && $base_config['data']['plat_is_fen'] == 1){
            $fen_config = new UtilConfig('fen_config');
            $fen_config = $fen_config->findConfigKeyValue();
            if($fen_config['data']['cash_type'] == 1){
                $this->goods_fen = 1;
            }
        }
        $this->log(__CLASS__, __FUNCTION__, "体检项目详情页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/store/page/goods/goodsDetail.html");
    }
	
	/**
	 * 编辑商品页面
	 */
	function editGoods(){
	    $this->getMenu($this);
	    $goodsResult = $this->lib_goods->getGoods($this->spArgs('id'));
	    $this->goods = $goodsResult['data'];
	    if(!class_exists('lib_categoty')) include 'model/store/lib_category.php';
	    $lib_category = new lib_category();
	    $gid = $goodsResult['data']['id'];
	    $cateResult = $lib_category->getCategorys("is_use = 1 and (gids = '{$gid}' OR gids like '%,{$gid},%' OR gids like '%,{$gid}' OR gids like '{$gid},%')", "fir asc, sec asc, thr asc, fou asc",null);
	    $this->cateList = $cateResult['data'];
	    if(!class_exists('lib_template')) include 'model/store/lib_template.php';
	    $lib_template = new lib_template();
	    $templateResult = $lib_template->findAllTemplate();
	    $idList = array();
	    foreach($templateResult['data'] as $template){
	        array_push($idList, $template['id']);
	    }
	    $this->templateList = $templateResult['data'];
	    //辅图信息
	    $imgListResult = $this->lib_goods->getGoodsImageList($this->spArgs('id'));
	    $totalImage = count($imgListResult['data']);
	    for($i = 0;$i  <  $totalImage ;$i++){
	        $j = $i +1;
	        $str = "sideImg{$j}";
	        $this->$str= $imgListResult['data'][$i];
	    }
        //获取微商城相关配置
        $lib_config = new UtilConfig('store_config');
	    $result = $lib_config->findConfigKeyValue();
	    $config = $result['data'];
	    $this->configInfo = $config;
	    //基础配置是否开启分销配置
	    $base_config = new UtilConfig('base_config');
	    $base_config = $base_config->findConfigKeyValue();
	    if($result['data']['is_fen'] == 1 && $base_config['data']['plat_is_fen'] == 1){
	        $fen_config = new UtilConfig('fen_config');
	        $fen_config = $fen_config->findConfigKeyValue();
	        if($fen_config['data']['cash_type'] == 1){
	            $this->goods_fen = 1;
	        }
	    }	
	    $this->log(__CLASS__, __FUNCTION__, "编辑体检项目页面", 1, 'edit');
	    $this->display("../template/admin/{$this->theme}/store/page/goods/editGoods.html");
	}
	
	/**
	 * 设置推荐商品
	 */
	function recommendGoods(){
	    $conditions = array('id' => $this->spArgs('id'));
	    $updown = array('recommend' => $this->spArgs('recommend'));
	    $result = $this->lib_goods->updownGoods($conditions,$updown);
	    $this->log(__CLASS__, __FUNCTION__, "设置推荐体检项目", 0, 'edit');
	    echo json_encode($result);
	}

    /**
     * 复制商品信息
     */
    function cloneGoods(){
        $id = $this->spArgs('id');
        $result = $this->lib_goods->getGoods($id);
        unset($result['data']['id']);
        $result['data']["add_time"] = date ('Y-m-d H:i:s',strtotime('+8hour'));
        $result['data']["creator"] = $_SESSION['admin']['account'];
        $resultGoods = $this->lib_goods->addGoods ($result['data']);
        if(!class_exists('lib_category')) include 'model/store/lib_category.php';
        $lib_category = new lib_category();
        $cateResult = $lib_category->getCategorys(" (gids = '{$id}' OR gids like '%,{$id},%' OR gids like '%,{$id}' OR gids like '{$id},%') and is_use = 1", "fir asc, sec asc, thr asc, fou asc",null);
        $cateResult['data'][0]['gids'] = $cateResult['data'][0]['gids'].','.$resultGoods['data']['gid'];
        $conditions = array(
            'id' => $cateResult['data'][0]['id']
        );
        $valueList['gids'] = $cateResult['data'][0]['gids'];
        $result = $lib_category->updateCategory($conditions,$valueList);
        echo json_encode($result);
    }

    /**
	 * 添加商品
	 */
	function insertGoods(){
	    //验证图片能否上传
	    //if($_FILES) $verify = UtilImage::verifyImage();
	    //if($verify['errorCode'] == 1) exit(json_encode($verify));
	    
	    $goodsInfo = $this->getArgsList($this, array(name,updown,recommend,price,detail_desc,good_type,apply,time_length,ori_price,sample_vessel,discount,sort_num,province,city,area));
	    $goodsInfo["add_time"] = date ('Y-m-d H:i:s',strtotime('+8hour') );
	    $goodsInfo["creator"] = $_SESSION['admin']['account'];

		//上传图片
		//$resultImg = UtilImage::uploadPhoto('imgurl', 'upload/image/store/goods/',300,300);
		//if($resultImg){
			//$goodsInfo['img_url'] = $resultImg['url'];
			//$goodsInfo['thumb'] = $resultImg['thumb'];
			$resultGoods = $this->lib_goods->addGoods ($goodsInfo);
			//处理分类
			if(!class_exists('lib_category')) include 'model/store/lib_category.php';
			$lib_category = new lib_category();
			$cateResult = $lib_category->updateGids($resultGoods['data']['gid'], $this->spArgs('cids'));
			if($cateResult['errorCode'] != '0'){
			    $this->lib_goods->deleteGoods($resultGoods['data']['gid']);
			    echo json_encode(common::errorArray(1, '处理体检项目分类失败', $cateResult));
			}
			//上传辅图
			$imgInfo['gid'] = $resultGoods['data']['gid'];
			$resultImg = UtilImage::uploadPhoto('sideImg1', 'upload/image/store/side/',300,300);
			if($resultImg){
				$imgInfo['img_url'] = $resultImg['url'];
				$imgInfo['thumb'] = $resultImg['thumb'];
				$this->lib_goods->addGoodsImage($imgInfo);
			}
			$resultImg = UtilImage::uploadPhoto('sideImg2', 'upload/image/store/side/',300,300);
			if($resultImg){
				$imgInfo['img_url'] = $resultImg['url'];
				$imgInfo['thumb'] = $resultImg['thumb'];
				$this->lib_goods->addGoodsImage($imgInfo);
			}
			$resultImg = UtilImage::uploadPhoto('sideImg3', 'upload/image/store/side/',300,300);
			if($resultImg){
				$imgInfo['img_url'] = $resultImg['url'];
				$imgInfo['thumb'] = $resultImg['thumb'];
				$this->lib_goods->addGoodsImage($imgInfo);
			}
			
			$this->log(__CLASS__, __FUNCTION__, "添加商品", 0, 'add');
			echo json_encode($resultGoods);
		// }else{
		// 	$this->log(__CLASS__, __FUNCTION__, "添加商品", 0, 'add');
		// 	echo json_encode(common::errorArray(1, "上传主图失败", 0));
		// }
	}
	
	/**
	 * 分页查询商品
	 */
	function pagingGoods(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('name' => 'like','recommend' => '=','updown' => '=','id' => 'in','from_add_time' => '>=','to_add_time' => '<=');
		$conditions = $this->getPagingList($this, $keyValueList);
		$sort = "updown desc,recommend desc,add_time desc";

		$lib_goods = new lib_goods();
		$result = $lib_goods->pagingGoods ( $page, $conditions, $sort);
		echo json_encode($result);
	}
	
	/**
	 * 删除商品属性
	 
	function deleteGoodsProperty(){
		$gpid = $this->spArgs('gpid');
		//查找pgids
		if(!class_exists('lib_goods_property')) include 'model/store/lib_goods_property.php';
		$lib_goods_property = new lib_goods_property();
		$gpResult =$lib_goods_property->findGoodsProperty(array('id' => $gpid));
		$gpResultDelete = $lib_goods_property->deleteGoodsProperty(array('id' => $gpid));
		$this->log(__CLASS__, __FUNCTION__, "删除商品属性", 0, 'del');
		if($gpResultDelete['data']['errorCode'] == 0){
			echo json_encode(common::errorArray(0, "删除成功", false));
		}else{
			echo json_encode(common::errorArray(1, "删除商品属性失败", false));
		}	
	}
	*/
	
	/**
	 * 修改商品信息
	 */
	function  updateGoods(){
	    //验证图片能否上传
	    //if($_FILES) $verify = UtilImage::verifyImage();
	    //if($verify['errorCode'] == 1) exit(json_encode($verify));
		$conditions = array('id' => $this->spArgs('id'));
		
		//更新商品基本信息
		$goodsInfo = $this->getArgsList($this, array(good_type,apply,time_length,name,price,recommend,updown,detail_desc,ori_price,sample_vessel,discount,sort_num,province,city,area));
		
		if($this->spArgs('imgFlag') == 1){//判断是否需要重新上传图片
		    //更新图片
			$prevUrl = $this->spArgs('prevurl');
			if($prevUrl){
				$resultImg = UtilImage::uploadPhoto('imgurl', 'upload/image/store/goods/',300,300);
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
		$this->updateGoodsImage($this->lib_goods,  $this->spArgs('id'));//辅图修改
		$result = $this->lib_goods->updateGoods($conditions,$goodsInfo);

		if($result['errorCode']==0){
			//如果更改了价格，则同步更改购物车中该商品的价格
			if(!class_exists("lib_cart"))include "model/store/lib_cart.php";
			$lib_cart=new lib_cart();
			$lib_cart->updateCartGoods(array('gid'=>$this->spArgs('id')),array('price'=>$goodsInfo['price']));
		}

		$this->log(__CLASS__, __FUNCTION__, "修改体检项目信息", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 三个辅图业务处理
	 * @param object $lib_goods
	 * @param int $gid
	 */
	private function updateGoodsImage($lib_goods,$gid){
		if($this->spArgs('imgFlag1')){//0不变,1修改,2新增,3删除
			$this->perImage('imgFlag1', 'prevSideImg1','sideImg1' ,'sid1', $lib_goods, $gid);
		}
		if($this->spArgs('imgFlag2')){//0不变,1修改,2新增,3删除
			$this->perImage('imgFlag2', 'prevSideImg2', 'sideImg2' ,'sid2', $lib_goods, $gid);
		}
		if($this->spArgs('imgFlag3')){//0不变,1修改,2新增,3删除
			$this->perImage('imgFlag3', 'prevSideImg3', 'sideImg3' ,'sid3', $lib_goods, $gid);
		}
	}
	
	/**
	 * 每个辅图业务处理
	 * @param string $imgFlag
	 * @param string $prevprevSideImg 原辅图地址
	 * @param string $sideImg 文件流名称
	 * @param string $sid 辅图主键id
	 * @param object $lib_goods
	 * @param int $gid 商品id
	 */
	private function perImage($imgFlag,$prevprevSideImg,$sideImg,$sid,$lib_goods,$gid){
	    //验证图片能否上传
	    if($_FILES) $verify = UtilImage::verifyImage();
	    if($verify['errorCode'] == 1) exit(json_encode($verify));
		if($this->spArgs($imgFlag) == 1){//修改
			//更新图片
			$prevUrl = $this->spArgs($prevprevSideImg);
			$resultImg = UtilImage::uploadPhoto($sideImg, 'upload/image/store/side/',300,300);
			if($resultImg){
				$prevThumbUrl = substr($prevUrl, 0,strripos($prevUrl,'.')) . "_thumb" .  substr($prevUrl, strripos($prevUrl,'.'));
				@unlink($prevUrl);//delete url
				@unlink($prevThumbUrl);//delete thumb
				$lib_goods->updateGoodsImage(array('id'=>$this->spArgs($sid)),array('img_url'=>$resultImg['url'],"thumb"=>$resultImg['thumb']));
			}else{
				echo json_encode(common::errorArray(1, "修改主图失败", 0));
				exit;
			}
		}else if($this->spArgs($imgFlag) == 2){//新增
			$resultImg = UtilImage::uploadPhoto($sideImg, 'upload/image/store/side/',300,300);
			if($resultImg){
				$imgInfo['gid'] = $gid;
				$imgInfo['img_url'] = $resultImg['url'];
				$imgInfo['thumb'] = $resultImg['thumb'];
				$lib_goods->addGoodsImage($imgInfo);
			}
		}else if($this->spArgs($imgFlag) == 3){//删除
			$prevUrl = $this->spArgs($prevprevSideImg);
			$prevThumbUrl = substr($prevUrl, 0,strripos($prevUrl,'.')) . "_thumb" .  substr($prevUrl, strripos($prevUrl,'.'));
			@unlink($prevUrl);//delete url
			@unlink($prevThumbUrl);//delete thumb
			$lib_goods->deleteGoodsImage(array('id'=>$this->spArgs($sid)));
		}
	}
	
	/**
	 * 商品上下架
	 */
	function updownGoods() {
		$conditions = array('id' => $this->spArgs('id'));
		$updown = array('updown' => $this->spArgs('updown'));
		$lib_goods = new lib_goods();
		$result = $lib_goods->updownGoods($conditions,$updown);
		$this->log(__CLASS__, __FUNCTION__, "体检项目上下架", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 根据id获取商品信息
	 */
	function getGoodsDetail() {
		$result = $this->lib_goods->getGoods($this->spArgs('id'));
		$this->log(__CLASS__, __FUNCTION__, "根据id获取体检项目信息", 0, 'view');
		echo json_encode($result);
	}

	/**
	 * 删除商品
	 */
	function deleteGoods(){		
		$id = $this->spArgs('id');
		$result = $this->lib_goods->deleteComplete($id);
		if($result['errorCode'] != 0){
			echo json_encode($result);
			return;
		}
		//删除商品图片
		$imgUrl = $this->spArgs('imgurl');
		@unlink($imgUrl);//delete url
		$thumbUrl = substr($imgUrl, 0,strripos($imgUrl,'.')) . "_thumb" .  substr($imgUrl, strripos($imgUrl,'.'));
		@unlink($thumbUrl);//delete thumb
		//删除商品辅图
		$imageListResult = $this->lib_goods->getGoodsImageList($id);
		if($imageListResult['errorCode'] == 0){
			foreach ($imageListResult['data'] as $per){
				@unlink($per['img_url']);
				@unlink($per['thumb']);
			}
		}
		$this->lib_goods->deleteGoodsImage(array('gid'=>$id));
		$this->log(__CLASS__, __FUNCTION__, "删除体检项目", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 批量删除商品
	 */
	function batchDelete(){
		$gids = $this->spArgs('ids');
		$result = $this->lib_goods->batchDelete($gids);
		$this->log(__CLASS__, __FUNCTION__, "批量删除体检项目", 0, 'del');
		echo json_encode($result);
	}
}