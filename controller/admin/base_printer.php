<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_clinic')) require 'model/base/lib_printer.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
/**
 *诊所管理
 * @name base_printer.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class base_printer extends admin_controller{
    private $lib_printer;

    /**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	    $this->lib_printer = new lib_printer();
	}
	
	/**
	 * 跳转添加打印机信息页面
	 */
	function addPrinter(){
		$this->getMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "跳转添加打印机信息页面", 1, 'add');
		$this->display("../template/admin/{$this->theme}/base/printer/page/addPrinter.html");
	}

	/**
	 * 打印机列表信息页面
	 */
	function printerList(){
		$this->getSetMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "跳转打印机列表信息页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/printer/page/printerList.html");
	}
	
	/**
     * 打印机详情页面
     */
    function printerDetail(){
        $this->getMenu($this);
        //商品基本信息
        $result = $this->lib_printer->getPrinter($this->spArgs('id'));
        $this->goodsInfo = $result['data'];
        $this->log(__CLASS__, __FUNCTION__, "商品详情页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/base/printer/page/printerDetail.html");
    }
	
	/**
	 * 编辑打印机页面
	 */
	function editPrinter(){
	    $this->getMenu($this);
	    $goodsResult = $this->lib_printer->getPrinter($this->spArgs('id'));
	    $this->goods = $goodsResult['data'];
	    $this->log(__CLASS__, __FUNCTION__, "编辑打印机页面", 1, 'edit');
	    $this->display("../template/admin/{$this->theme}/base/printer/page/editPrinter.html");
	}
	
	
	/**
	 * 添加打印机
	 */
	function insertPrinter(){
	    
	    $goodsInfo = $this->getArgsList($this, array(num,printer_key,province,city,area));
        $resultGoods = $this->lib_printer->addPrinter ($goodsInfo);

        $this->log(__CLASS__, __FUNCTION__, "添加打印机", 0, 'add');
        echo json_encode($resultGoods);
	}
	
	/**
	 * 分页查询打印机
	 */
	function pagingPrinter(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('num' => 'like', 'province' => 'like', 'city' => 'like', 'area' => 'like');
		$conditions = $this->getPagingList($this, $keyValueList);
		$sort = "id desc";

		$lib_printer = new lib_printer();
		$result = $lib_printer->pagingGoods ( $page, $conditions, $sort);//dump($result);die;
		echo json_encode($result);
	}
	
	/**
	 * 修改打印机信息
	 */
	function  updatePrinter(){
		$conditions = array('id' => $this->spArgs('id'));
		
		//更新商品基本信息
		$goodsInfo = $this->getArgsList($this, array(num,printer_key,province,city,area));
		$result = $this->lib_printer->updatePrinter($conditions,$goodsInfo);
		
		$this->log(__CLASS__, __FUNCTION__, "修改打印机信息", 0, 'edit');
		echo json_encode($result);
	}
	
	
	/**
	 * 根据id获取打印机信息
	 */
	function getPrinterDetail() {
		$result = $this->lib_printer->getPrinter($this->spArgs('id'));
		$this->log(__CLASS__, __FUNCTION__, "根据id获取打印机信息", 0, 'view');
		echo json_encode($result);
	}

	/**
	 * 删除打印机
	 */
	function deletePrinter(){
		$id = $this->spArgs('id');
		
		$result = $this->lib_printer->deleteComplete($id);
		if($result['errorCode'] != 0){
			echo json_encode($result);
			return;
		}
		$this->log(__CLASS__, __FUNCTION__, "删除商品", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 批量删除打印机
	 */
	function batchDelete(){
		$gids = $this->spArgs('ids');
		$result = $this->lib_printer->batchDelete($gids);
		$this->log(__CLASS__, __FUNCTION__, "批量删除诊所", 0, 'del');
		echo json_encode($result);
	}
}