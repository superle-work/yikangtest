<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_category')) require 'model/store/lib_category.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
/**
 *商城分类管理
 * @name store_category.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class store_category extends admin_controller{
    private $lib_category;
	private $store_config;
	private $category;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	    $this->lib_category = new lib_category();

		//传递分类信息		
		$this->store_config = new UtilConfig('store_config');
		$cateConfig = $this->store_config->getConfigValue('cate_level');
		if($cateConfig['data'] == 1){
			$this->category = 'category1';
		}else if($cateConfig['data'] == 2){
			$this->category = 'category2';
		}else if($cateConfig['data'] == 3){
			$this->category = 'category3';
		}else{//默认原来的
			$this->category = 'category';
		}
		$this->cate = $this->category;
	}

	/**
	 * 类别列表页面
	 */
	function categoryList(){
		$this->getSetMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "类别列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/{$this->category}/categoryList.html");
	}
	
	/**
	 * 分类商品列表页面
	 */
	function cateGoodsList(){
	    $this->getMenu($this);
	    $cateResult = $this->lib_category->findCategory(array('id' => $this->spArgs('id')));
	    $cateInfo = $cateResult['data'];
	    $result =  $this->lib_category->getCateGids($this->spArgs('id'));
	    $cateInfo['gids'] = $result['data'];
	    $this->cateInfo = $cateInfo;
	    $this->log(__CLASS__, __FUNCTION__, "分类商品列表页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/store/page/{$this->category}/cateGoodsList.html");
	}
	

	/**
	 * 添加分类页面
	 */
	function addCategory(){
	    $this->getMenu($this);
	    $this->rank = $this->spArgs('rank');
	    if($this->spArgs('pid')){
	        $this->pid = $this->spArgs('pid');
	        $cateResult = $this->lib_category->findCategory(array('id' => $this->spArgs('pid')));
	        $this->pCateInfo = $cateResult['data'];
	    }
	    //日志记录
	    $this->log(__CLASS__, __FUNCTION__, "添加分类页面", 1, 'add');
	    $this->display("../template/admin/{$this->theme}/store/page/{$this->category}/addCategory.html");
	}
	
	/**
	 * 编辑分类
	 */
	function editCategory(){
	    $this->getMenu($this);
	    $this->id = $this->spArgs('id');
	    $cateResult = $this->lib_category->findCategory(array('id' => $this->spArgs('id')));
	    $this->cateInfo = $cateResult['data'];
		$this->rank = $this->spArgs('rank');
	    //日志记录
	    $this->log(__CLASS__, __FUNCTION__, "编辑抢购商品分类页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/store/page/{$this->category}/editCategory.html");
	}
	
	/**
	 * 绑定商品页面
	 **/
	function showBindGoods(){
	    $this->getMenu($this);
	    $this->log(__CLASS__, __FUNCTION__, "绑定商品页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/store/page/label/bindGoods.html");
	}
	
	/**
	 * 添加分类
	 */
	function insertCategory(){
	    //验证图片能否上传
	    if($_FILES) $verify = UtilImage::verifyImage();
	    if($verify['errorCode'] == 1) exit(json_encode($verify));
	    
	    $valueList = $this->getArgsList($this, array('name', 'order_index', 'rank', 'is_use', 'gids'));
// 	    $result = $this->lib_category->isCategoryExisit($valueList['name']);
// 	    if($result['errorCode'] == '1'){//判断是否分类名重复
// 	        echo json_encode($result);
// 	        exit;
// 	    }
	    $cid = $this->spArgs('pid');//分类id
	    if($cid != '' && $cid != null){
	        $cateResult = $this->lib_category->findCategory(array('id' => $cid));
	        $pCateInfo = $cateResult['data'];
	        $valueList['fir'] = $pCateInfo['fir'];
	        $valueList['sec'] = $pCateInfo['sec'];
	        $valueList['thr'] = $pCateInfo['thr'];
	    }
	    //获取最大分类数字
	    $result = $this->lib_category->getCategoryNum ( $valueList );
	    
	    if($result['errorCode'] != 0){
	        echo json_encode($result);
	        exit;
	    }
	    if($valueList['rank'] == 1){
	        $valueList['fir'] = $result['data'];
	    }else if($valueList['rank'] == 2){
	        $valueList['sec'] = $result['data'];//预置当前子分类sec编号
	    }else if($valueList['rank'] == 3){
	        $valueList['thr'] = $result['data'];//预置当前子分类thr编号
	    }else if($valueList['rank'] == 4){//若当前子分类级别为四级
	        $valueList['fou'] = $result['data'];//预置当前子分类fou编号
	    }
	    //上传图片
	    $resultImg = UtilImage::uploadPhoto('imgurl', 'upload/image/store/category/main/' . date('Ymd'), 300, 300);
	    if($resultImg){
	        $valueList['img_url'] = $resultImg['url'];
	        $valueList['thumb'] = $resultImg['thumb'];
	    }
	    //上传图标
	    $resultImg = UtilImage::uploadPhotoJust('icon', 'upload/image/store/category/icon/' . date('Ymd'), 300, 300);
	    if($resultImg){
	        $valueList['icon'] = $resultImg;
	    }
	    $result = $this->lib_category->addCategory($valueList);
	    //日志记录
	    $this->log(__CLASS__, __FUNCTION__, "添加分类", 0, 'add');
	    echo json_encode ( $result );
	}
	
	/**
	 * 修改分类
	 */
	function updateCategory(){
	    //验证图片能否上传
	    if($_FILES) $verify = UtilImage::verifyImage();
	    if($verify['errorCode'] == 1) exit(json_encode($verify));
	    
	    $valueList = $this->getArgsList($this, array('pid', 'name', 'order_index', 'is_use', 'gids','id'));
	    $conditions = array(
	        'id' => $this->spArgs('id')
	    );
	    if($this->spArgs('imgFlag') == 1){//判断是否需要重新上传图片
	        //更新图片
	        $prevUrl = $this->spArgs('prevurl');
	        
            $resultImg = UtilImage::uploadPhoto('imgurl', 'upload/image/store/category/main/' . date('Ymd'), 300, 300);
            if($resultImg){
                $valueList['img_url'] = $resultImg['url'];
                $valueList['thumb'] = $resultImg['thumb'];
                if($prevUrl){
	                $prevThumbUrl = substr($prevUrl, 0,strripos($prevUrl,'.')) . "_thumb" .  substr($prevUrl, strripos($prevUrl,'.'));
	                unlink($prevUrl);//delete url
	                unlink($prevThumbUrl);//delete thumb
                }
            }else{
                echo json_encode(common::errorArray(1, "修改分类图片失败", 0));
                exit;
            }
	    }
	    if($this->spArgs('icon_imgFlag') == 1){//判断是否需要重新上传图片
	        //更新图片
	        $prevUrl = $this->spArgs('icon_prevurl');
            $resultImg = UtilImage::uploadPhotoJust('icon', 'upload/image/store/category/icon/' . date('Ymd'), 300, 300);
            if($resultImg){
                $valueList['icon'] = $resultImg;
                if($prevUrl){
//                     $prevThumbUrl = substr($prevUrl, 0,strripos($prevUrl,'.')) . "_thumb" .  substr($prevUrl, strripos($prevUrl,'.'));
                    unlink($prevUrl);//delete url
//                     unlink($prevThumbUrl);//delete thumb
                }
            }else{
                echo json_encode(common::errorArray(1, "修改分类图标失败", 0));
                exit;
            }
	    }
	    $result = $this->lib_category->updateCategory($conditions,$valueList);
	    //日志记录
	    $this->log(__CLASS__, __FUNCTION__, "修改分类", 0, 'edit');
	    echo json_encode($result);
	}
	
	/**
	 * 根据类别等级获取类别信息
	 */
	function getCategorys(){
	    $rank = $this->spArgs('rank');
	    $sortInfo = 'order_index asc';
	    if($rank==1){
	        $conditions = array(
	            'rank' => $rank
	        );
	        $sort = ', fir desc';
	    }else if($rank==2){
	        $conditions = array(
	            'rank' => $rank,
	            'fir' => $this->spArgs('fir')
	        );
	        $sort = ', sec desc';
	    }else if($rank==3){
	        $conditions = array(
	            'rank' => $rank,
	            'fir' => $this->spArgs('fir'),
	            'sec' => $this->spArgs('sec')
	        );
	        $sort = ', thr desc';
	    }else if($rank==4){
	        $conditions = array(
	            'rank' => $rank,
	            'fir' => $this->spArgs('fir'),
	            'sec' => $this->spArgs('sec'),
	            'thr' => $this->spArgs('thr')
	        );
	        $sort = ', fou desc';
	    }else {
	        $conditions['rank'] = $rank;
	    }
	    
	    $result = $this->lib_category->getCategorys($conditions,$sortInfo.$sort);
	    echo json_encode($result);
	}
	
	function getCategorysByFir(){
	    $fir = $this->spArgs('fir');
	    $lib_caterogy = new lib_category();
	    $result = $this->lib_category->getCategorysByFir($fir);
	    echo json_encode($result);
	}
	
	/**
	 * 通过一级分类获取二级和对应三级子分类属性（数据格式为对象）
	 * @param string $fir
	 * @return array
	 */
	function getCategorysByFirObj(){
	    $fir = $this->spArgs('fir');
	    $result = $this->lib_category->getCategorysByFirObj($fir);
	    echo json_encode($result);
	}
	
	/**
	 * 判断类名存在
	 */
	function isCategoryExisit(){
	    $name = $this->spArgs('name');
	    $result = $this->lib_category->isCategoryExisit($name);
	    echo json_encode($result);
	}
	
	/**
	 * 删除分类
	 */
	function deleteCategory() {
	    $result = $this->lib_category->deleteCategory ( $this->spArgs ( 'id' ) );
	    //日志记录
	    $this->log(__CLASS__, __FUNCTION__, "删除分类", 0, 'del');
	    echo json_encode ( $result );
	}
	
	/**
	 * 删除分类By
	 */
	function deleteCategoryByNum() {
	    $conditions ['fir'] = $this->spArgs ( 'fir' );
	    if ($this->spArgs ( 'sec' ) != "") {
	        $conditions ['sec'] = $this->spArgs ( 'sec' );
	        if ($this->spArgs ( 'thr' ) != "") {
	            $conditions ['thr'] = $this->spArgs ( 'thr' );
	            if ($this->spArgs ( 'fou' ) != "") {
	                $conditions ['fou'] = $this->spArgs ( 'fou' );
	            }
	        }
	    }
	    $result = $this->lib_category->deleteCategoryByNum ( $conditions );
	    //日志记录
	    $this->log(__CLASS__, __FUNCTION__, "删除抢购分类", 0, 'del');
	    echo json_encode ( $result );
	}
	
	/**
	 * 根据类别信息获取指定子类别最大编号
	 */
	function getCategoryMax() {
	    $conditions = array (
	        'rank' => $this->spArgs ( 'rank' ),
	        'fir' => $this->spArgs ( 'fir' ),
	        'sec' => $this->spArgs ( 'sec' ),
	        'thr' => $this->spArgs ( 'thr' )
	    );
	    $result = $this->lib_category->getCategoryNum ( $conditions );
	    echo json_encode ( $result );
	}
	
	/**
	 * 单个保存分类信息
	 **/
	function saveSingleCategory(){
	    $flag = $this->spArgs('flag');//当前操作标识：0表示添加，1表示更新
	    $category = $this->spArgs('category');
	
	    $result = $this->lib_category->singleSaveCategory($flag,$category);
	    //日志记录
	    $this->log(__CLASS__, __FUNCTION__, "单个保存分类信息", 0, 'edit');
	    echo json_encode ( $result );
	}
	/**
	 * 批量保存全部分类信息
	 */
	function batchSaveCategory(){
	    $addData = $this->spArgs('addData');
	    $updateData = $this->spArgs('updateData');
		
	    $result = $this->lib_category->batchCategory ($addData,$updateData);
	    //日志记录
	    $this->log(__CLASS__, __FUNCTION__, "批量保存全部分类信息", 0, 'edit');
	    echo json_encode($result);
	}
	
	/**
	 * 开启、关闭分类
	 */
	function changeStatus(){
	    $id = $this->spArgs('id');
	    $isUse = $this->spArgs('isuse');
		
	    $result = $this->lib_category->changeStatus ($id,$isUse);
	    //日志记录
	    $this->log(__CLASS__, __FUNCTION__, "开启、关闭分类", 0, 'edit');
	    echo json_encode($result);
	}
}